<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DokumenJalur;
use App\Models\DokumenPendaftar;
use App\Models\JalurMasuk;
use App\Models\Pendaftaran;
use App\Models\PeriodePmb;
use App\Services\TagihanPendaftaranService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PendaftaranController extends Controller
{
    /**
     * Status pendaftaran yang tersedia (sesuai enum tabel `pendaftaran`).
     */
    private const STATUS = [
        'draft'               => 'Draft',
        'menunggu_verifikasi' => 'Menunggu Verifikasi',
        'terverifikasi'       => 'Terverifikasi',
        'ditolak'             => 'Ditolak',
    ];

    public function index(Request $request)
    {
        $query = Pendaftaran::with(['user', 'prodi', 'jalur', 'periode'])->latest();

        if ($request->filled('periode_id')) {
            $query->where('periode_id', $request->periode_id);
        }

        if ($request->filled('jalur_id')) {
            $query->where('jalur_id', $request->jalur_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('nomor_pendaftaran', 'like', "%{$search}%")
                    ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%"));
            });
        }

        $pendaftaran = $query->paginate(20)->withQueryString();

        $periode = PeriodePmb::orderByDesc('tahun_akademik')->get();
        $jalur = JalurMasuk::orderBy('urutan')->get();

        return view('admin.pendaftaran.index', compact('pendaftaran', 'periode', 'jalur'));
    }

    public function show(Pendaftaran $pendaftaran)
    {
        $pendaftaran->load([
            'user.profile.golongan',
            'periode',
            'jalur',
            'prodi.fakultas',
            'prodiAlternatif',
            'golongan',
            'dokumen.dokumen',
            'pembayaran',
        ]);

        $dokumen = $this->daftarDokumen($pendaftaran);

        return view('admin.pendaftaran.show', [
            'pendaftaran'   => $pendaftaran,
            'dokumen'       => $dokumen,
            'siapVerifikasi' => $this->siapVerifikasi($dokumen),
        ]);
    }

    /**
     * Verifikasi / tolak satu dokumen pendaftar.
     */
    public function statusDokumen(Request $request, DokumenPendaftar $dokumen)
    {
        $validated = $request->validate(
            [
                'status'  => ['required', 'in:valid,ditolak'],
                'catatan' => ['nullable', 'string', 'max:1000'],
            ],
            [
                'status.required' => 'Status dokumen wajib dipilih.',
                'status.in'       => 'Status dokumen tidak valid.',
                'catatan.max'     => 'Catatan maksimal 1000 karakter.',
            ],
            [
                'status'  => 'status dokumen',
                'catatan' => 'catatan',
            ]
        );

        $dokumen->status = $validated['status'];
        $dokumen->catatan_admin = $validated['catatan'] ?? null;
        $dokumen->save();

        if ($validated['status'] === 'ditolak') {
            // Pendaftaran ditolak otomatis agar pendaftar dapat merevisi dokumen.
            $pendaftaran = $dokumen->pendaftaran;

            $catatan = 'Dokumen '.$dokumen->dokumen->nama.' ditolak'
                .($validated['catatan'] ? ': '.$validated['catatan'] : '.');

            $pendaftaran->update([
                'status'       => 'ditolak',
                'catatan_admin' => $catatan,
                'verified_by'  => auth()->id(),
                'verified_at'  => now(),
            ]);

            return back()->with('success', 'Dokumen ditolak. Pendaftar dapat merevisi dokumen lalu mengajukan ulang.');
        }

        return back()->with('success', 'Status dokumen '.$dokumen->dokumen->nama.' diperbarui.');
    }

    /**
     * Verifikasi pendaftaran pendaftar + pembuatan tagihan otomatis.
     */
    public function verifikasi(Pendaftaran $pendaftaran, TagihanPendaftaranService $tagihanService)
    {
        if ($pendaftaran->status === 'terverifikasi') {
            return back()->with('info', 'Pendaftaran ini sudah berstatus terverifikasi.');
        }

        $dokumen = $this->daftarDokumen($pendaftaran);

        if (! $this->siapVerifikasi($dokumen)) {
            $belumValid = $dokumen
                ->filter(fn (array $item): bool => ($item['wajib'] || $item['upload'] !== null) && $item['status'] !== 'valid')
                ->pluck('master.nama')
                ->all();

            $pesan = $belumValid !== []
                ? 'Dokumen belum valid: '.implode(', ', $belumValid).'.'
                : 'Dokumen persyaratan belum lengkap atau belum valid.';

            return back()->with('error', $pesan);
        }

        DB::transaction(function () use ($pendaftaran, $tagihanService) {
            $pendaftaran->update([
                'status'        => 'terverifikasi',
                'verified_by'   => auth()->id(),
                'verified_at'   => now(),
                'catatan_admin' => null,
            ]);

            $tagihanService->buat($pendaftaran->fresh());
        });

        return back()->with('success', 'Pendaftar berhasil diverifikasi dan tagihan otomatis dibuat.');
    }

    /**
     * Tolak pendaftaran pendaftar.
     */
    public function tolak(Request $request, Pendaftaran $pendaftaran)
    {
        $validated = $request->validate(
            [
                'catatan' => ['required', 'string', 'max:1000'],
            ],
            [
                'catatan.required' => 'Catatan penolakan wajib diisi.',
                'catatan.max'      => 'Catatan maksimal 1000 karakter.',
            ],
            [
                'catatan' => 'catatan',
            ]
        );

        $pendaftaran->update([
            'status'        => 'ditolak',
            'catatan_admin' => $validated['catatan'],
            'verified_by'   => auth()->id(),
            'verified_at'   => now(),
        ]);

        return back()->with('success', 'Pendaftaran ditolak. Pendaftar dapat merevisi dokumen lalu mengajukan ulang.');
    }

    /**
     * Unduh berkas dokumen pendaftar.
     */
    public function unduhDokumen(DokumenPendaftar $dokumen)
    {
        if (! $dokumen->file_path || ! Storage::disk('local')->exists($dokumen->file_path)) {
            abort(404);
        }

        return Storage::disk('local')->download(
            $dokumen->file_path,
            $dokumen->dokumen->nama.'.'.pathinfo($dokumen->file_path, PATHINFO_EXTENSION)
        );
    }

    public function export(Request $request)
    {
        $query = Pendaftaran::with(['user', 'prodi', 'jalur', 'periode']);

        if ($request->filled('periode_id')) {
            $query->where('periode_id', $request->periode_id);
        }

        if ($request->filled('jalur_id')) {
            $query->where('jalur_id', $request->jalur_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pendaftaran = $query->get();

        $headers = ['Content-Type' => 'text/csv; charset=UTF-8'];
        $filename = 'pendaftar-' . now()->format('Y-m-d') . '.csv';

        $callback = function () use ($pendaftaran) {
            $file = fopen('php://output', 'w');

            // BOM agar Microsoft Excel dapat membaca UTF-8.
            fwrite($file, "\xEF\xBB\xBF");

            fputcsv($file, [
                'No. Pendaftaran', 'Nama', 'Email', 'Program Studi', 'Jalur',
                'Periode', 'Status', 'Tgl Verifikasi',
            ]);

            foreach ($pendaftaran as $p) {
                fputcsv($file, [
                    $p->nomor_pendaftaran,
                    $p->user->name,
                    $p->user->email,
                    $p->prodi->nama,
                    $p->jalur->nama,
                    $p->periode->nama,
                    self::STATUS[$p->status] ?? $p->status,
                    $p->verified_at?->format('d/m/Y H:i'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, array_merge($headers, [
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]));
    }

    /**
     * Daftar dokumen sesuai jalur pendaftaran beserta status unggahannya.
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

                return [
                    'master' => $item->dokumen,
                    'upload' => $upload,
                    'status' => $upload?->status ?? 'belum',
                    'wajib'  => (bool) $item->dokumen->is_wajib,
                ];
            })
            ->values();
    }

    /**
     * @param  \Illuminate\Support\Collection<int, array<string, mixed>>  $dokumen
     */
    private function siapVerifikasi($dokumen): bool
    {
        // Jika tidak ada persyaratan dokumen sama sekali di jalur ini
        if ($dokumen->isEmpty()) {
            return true;
        }

        $wajib = $dokumen->filter(fn (array $item): bool => $item['wajib']);
        $uploaded = $dokumen->filter(fn (array $item): bool => $item['upload'] !== null);

        // Jika ada dokumen wajib, semua dokumen wajib harus sudah diunggah dan berstatus valid
        if ($wajib->isNotEmpty() && ! $wajib->every(fn (array $item): bool => $item['status'] === 'valid')) {
            return false;
        }

        // Semua berkas yang diunggah oleh pendaftar harus berstatus 'valid' (tidak boleh 'menunggu' atau 'ditolak')
        if ($uploaded->isNotEmpty() && ! $uploaded->every(fn (array $item): bool => $item['status'] === 'valid')) {
            return false;
        }

        // Jika tidak ada dokumen wajib, setidaknya harus ada berkas yang diunggah dan berstatus valid
        if ($wajib->isEmpty() && $uploaded->isEmpty()) {
            return false;
        }

        return true;
    }
}
