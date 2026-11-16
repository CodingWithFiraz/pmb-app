<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Models\DokumenJalur;
use App\Models\DokumenMaster;
use App\Models\DokumenPendaftar;
use App\Models\Pendaftaran;
use App\Models\PeriodePmb;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DokumenController extends Controller
{
    /**
     * MIME yang diizinkan untuk unggah dokumen.
     *
     * @var array<int, string>
     */
    private const MIME_VALID = ['jpg', 'jpeg', 'png', 'pdf'];

    /**
     * Label status dokumen untuk tampilan.
     *
     * @var array<string, string>
     */
    private const STATUS_LABELS = [
        'menunggu' => 'Menunggu Verifikasi',
        'valid'    => 'Valid',
        'ditolak'  => 'Ditolak',
    ];

    public function index(Request $request): View|RedirectResponse
    {
        $pendaftaran = $this->pendaftaranAktif($request);

        if (! $pendaftaran) {
            return redirect()
                ->route('pendaftar.pilihan')
                ->with('info', 'Pilih jalur masuk terlebih dahulu sebelum mengunggah dokumen.');
        }

        $dokumen = $this->daftarDokumen($pendaftaran);

        return view('pendaftar.dokumen', [
            'pendaftaran'   => $pendaftaran,
            'dokumen'       => $dokumen,
            'wajibLengkap'  => $this->wajibLengkap($dokumen),
            'bolehUpload'   => $this->bolehUpload($pendaftaran),
            'bolehAjukan'   => $this->bolehUpload($pendaftaran) && $this->wajibLengkap($dokumen),
            'statusLabels'  => self::STATUS_LABELS,
            'mimeValid'     => self::MIME_VALID,
        ]);
    }

    public function upload(Request $request, DokumenMaster $dokumen): RedirectResponse
    {
        $pendaftaran = $this->pendaftaranAktif($request);

        if (! $pendaftaran) {
            return redirect()
                ->route('pendaftar.pilihan')
                ->with('error', 'Pendaftaran belum tersedia.');
        }

        if (! $this->bolehUpload($pendaftaran)) {
            return back()->with('error', 'Dokumen tidak dapat diubah pada status pendaftaran saat ini.');
        }

        // Dokumen hanya boleh diunggah jika memang merupakan syarat jalur pendaftar.
        $terdaftar = DokumenJalur::where('jalur_id', $pendaftaran->jalur_id)
            ->where('dokumen_id', $dokumen->id)
            ->exists();

        if (! $terdaftar) {
            return back()->with('error', 'Dokumen tersebut bukan persyaratan jalur '.$pendaftaran->jalur->nama.'.');
        }

        $request->validate(
            [
                'file' => ['required', 'file', 'mimes:'.implode(',', self::MIME_VALID), 'max:'.$dokumen->max_size],
            ],
            [
                'file.required' => 'Berkah belum dipilih.',
                'file.mimes'    => 'Format berkas harus JPG, PNG, atau PDF.',
                'file.max'      => 'Ukuran berkas maksimal '.$dokumen->max_size.' KB.',
            ],
            [
                'file' => 'berkas',
            ]
        );

        $file = $request->file('file');

        $path = $file->storeAs(
            'dokumen/'.$pendaftaran->id,
            Str::slug($dokumen->kode).'.'.$file->extension(),
            'local'
        );

        $existing = $pendaftaran->dokumen()
            ->where('dokumen_id', $dokumen->id)
            ->first();

        if ($existing && $existing->file_path && Storage::disk('local')->exists($existing->file_path)) {
            Storage::disk('local')->delete($existing->file_path);
        }

        DokumenPendaftar::updateOrCreate(
            [
                'pendaftaran_id' => $pendaftaran->id,
                'dokumen_id'     => $dokumen->id,
            ],
            [
                'file_path'      => $path,
                'status'         => 'menunggu',
                'catatan_admin'  => null,
            ]
        );

        return back()->with('success', 'Dokumen '.$dokumen->nama.' berhasil diunggah.');
    }

    public function download(Request $request, DokumenMaster $dokumen)
    {
        $pendaftaran = $this->pendaftaranAktif($request);

        if (! $pendaftaran) {
            abort(404);
        }

        $upload = $pendaftaran->dokumen()
            ->where('dokumen_id', $dokumen->id)
            ->first();

        if (! $upload || ! $upload->file_path || ! Storage::disk('local')->exists($upload->file_path)) {
            abort(404);
        }

        return Storage::disk('local')->download(
            $upload->file_path,
            $dokumen->nama.'.'.pathinfo($upload->file_path, PATHINFO_EXTENSION)
        );
    }

    /**
     * Ajukan pendaftaran untuk diverifikasi panitia.
     */
    public function verifikasi(Request $request): RedirectResponse
    {
        $pendaftaran = $this->pendaftaranAktif($request);

        if (! $pendaftaran) {
            return redirect()
                ->route('pendaftar.pilihan')
                ->with('error', 'Pendaftaran belum tersedia.');
        }

        if (! $this->bolehUpload($pendaftaran)) {
            return back()->with('error', 'Pendaftaran sudah diajukan verifikasi.');
        }

        $dokumen = $this->daftarDokumen($pendaftaran);

        $kurang = $dokumen
            ->filter(fn ($item): bool => $item['wajib'] && ! $item['lengkap'])
            ->pluck('nama')
            ->all();

        if ($kurang !== []) {
            return back()->with('error', 'Dokumen belum lengkap: '.implode(', ', $kurang).'.');
        }

        DB::transaction(function () use ($pendaftaran) {
            $pendaftaran->nomor_pendaftaran = $this->generateNomorPendaftaran();
            $pendaftaran->status = 'menunggu_verifikasi';
            $pendaftaran->save();
        });

        return redirect()
            ->route('pendaftar.dokumen')
            ->with('success', 'Pendaftaran diajukan untuk verifikasi dengan nomor '.$pendaftaran->nomor_pendaftaran.'.');
    }

    private function pendaftaranAktif(Request $request): ?Pendaftaran
    {
        /** @var User $user */
        $user = $request->user();

        $periode = PeriodePmb::getActive();

        if (! $periode) {
            return null;
        }

        return $user->pendaftaran()
            ->with(['periode', 'jalur', 'prodi', 'prodiAlternatif', 'golongan'])
            ->where('periode_id', $periode->id)
            ->first();
    }

    /**
     * Daftar dokumen sesuai jalur yang dipilih pendaftar.
     *
     * @return \Illuminate\Support\Collection<int, array<string, mixed>>
     */
    private function daftarDokumen(Pendaftaran $pendaftaran)
    {
        return DokumenJalur::where('jalur_id', $pendaftaran->jalur_id)
            ->with('dokumen')
            ->get()
            ->map(function (DokumenJalur $item) use ($pendaftaran): array {
                $upload = $pendaftaran->dokumen()
                    ->where('dokumen_id', $item->dokumen_id)
                    ->first();

                $status = $upload?->status ?? 'belum';

                return [
                    'master'   => $item->dokumen,
                    'upload'   => $upload,
                    'status'   => $status,
                    'label'    => self::STATUS_LABELS[$status] ?? 'Belum Diunggah',
                    'wajib'    => (bool) $item->dokumen->is_wajib,
                    'lengkap'  => $upload !== null && in_array($status, ['menunggu', 'valid'], true),
                ];
            })
            ->values();
    }

    /**
     * @param  \Illuminate\Support\Collection<int, array<string, mixed>>  $dokumen
     */
    private function wajibLengkap($dokumen): bool
    {
        return $dokumen
            ->filter(fn (array $item): bool => $item['wajib'])
            ->every(fn (array $item): bool => $item['lengkap']);
    }

    private function bolehUpload(Pendaftaran $pendaftaran): bool
    {
        return in_array($pendaftaran->status, ['draft', 'ditolak'], true);
    }

    /**
     * Nomor pendaftaran format PMB-{tahun}-{urutan}, contoh PMB-2026-0001.
     */
    private function generateNomorPendaftaran(): string
    {
        $prefix = 'PMB-'.now()->year.'-';

        $terakhir = Pendaftaran::where('nomor_pendaftaran', 'like', $prefix.'%')
            ->orderByDesc('nomor_pendaftaran')
            ->lockForUpdate()
            ->value('nomor_pendaftaran');

        $urutan = $terakhir ? ((int) substr($terakhir, strlen($prefix))) + 1 : 1;

        return $prefix.str_pad((string) $urutan, 4, '0', STR_PAD_LEFT);
    }
}
