<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Models\BiayaPendaftaran;
use App\Models\DokumenJalur;
use App\Models\Pendaftaran;
use App\Models\PeriodePmb;
use App\Models\TarifUkt;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        /** @var User $user */
        $user = Auth::user();

        $activePeriod = PeriodePmb::getActive();
        $profile = $user->profile()->first();

        $notifikasi = auth()->user()->unreadNotifications
            ->take(5)
            ->map(fn ($n) => [
                'message' => $n->data['message'] ?? $n->data['title'] ?? 'Notifikasi baru',
                'url'     => $n->data['url'] ?? null,
                'created' => $n->created_at->translatedFormat('d M Y H:i'),
            ]);

        $pendaftaran = $user->pendaftaran()
            ->with(['periode', 'jalur', 'prodi', 'prodiAlternatif', 'golongan'])
            ->when($activePeriod, fn ($query) => $query->where('periode_id', $activePeriod->id))
            ->latest()
            ->first();

        $profilPersen = $profile?->completionPercentage() ?? 0;

        $dokumenWajib = 0;
        $dokumenUpload = 0;

        if ($pendaftaran) {
            $dokumenWajib = DokumenJalur::where('jalur_id', $pendaftaran->jalur_id)->count();
            $dokumenUpload = $pendaftaran->dokumen()->count();
        }

        $statusVerifikasi = $pendaftaran?->statusLabel() ?? 'Belum mendaftar';
        $pembayaran = $this->ringkasanPembayaran($pendaftaran);

        $ukt = null;

        if ($pendaftaran) {
            $ukt = TarifUkt::query()
                ->where('periode_id', $pendaftaran->periode_id)
                ->where('jalur_id', $pendaftaran->jalur_id)
                ->where('prodi_id', $pendaftaran->prodi_1_id)
                ->where('golongan_id', $pendaftaran->golongan_id)
                ->value('nominal');
        }

        $checklist = [
            [
                'label'      => 'Profil pendaftar lengkap',
                'selesai'    => $profilPersen === 100,
                'keterangan' => $profilPersen.'% terisi',
                'url'        => route('pendaftar.profil'),
                'aksi'       => 'Lengkapi profil',
            ],
            [
                'label'      => 'Pilihan jalur & program studi',
                'selesai'    => (bool) $pendaftaran,
                'keterangan' => $pendaftaran
                    ? $pendaftaran->jalur?->nama.' - '.$pendaftaran->prodi?->nama
                    : 'Belum dipilih',
                'url'        => route('pendaftar.pilihan'),
                'aksi'       => 'Pilih sekarang',
            ],
            [
                'label'      => 'Dokumen persyaratan',
                'selesai'    => $dokumenWajib > 0 && $dokumenUpload >= $dokumenWajib,
                'keterangan' => $dokumenWajib > 0
                    ? $dokumenUpload.'/'.$dokumenWajib.' dokumen terunggah'
                    : 'Menunggu pilihan jalur',
                'url'        => route('pendaftar.dokumen'),
                'aksi'       => 'Kelola dokumen',
            ],
            [
                'label'      => 'Pembayaran biaya pendaftaran',
                'selesai'    => $pembayaran['lunas'],
                'keterangan' => $pembayaran['label'],
                'url'        => route('pendaftar.pembayaran'),
                'aksi'       => 'Bayar sekarang',
            ],
            [
                'label'      => 'Verifikasi panitia',
                'selesai'    => $pendaftaran?->status === 'terverifikasi',
                'keterangan' => $statusVerifikasi,
                'url'        => null,
                'aksi'       => null,
            ],
        ];

        return view('pendaftar.dashboard', compact(
            'activePeriod',
            'profile',
            'pendaftaran',
            'profilPersen',
            'dokumenWajib',
            'dokumenUpload',
            'statusVerifikasi',
            'pembayaran',
            'ukt',
            'checklist',
            'notifikasi'
        ));
    }

    /**
     * Ringkasan status pembayaran biaya pendaftaran.
     *
     * @return array{label: string, lunas: bool, nominal: int|null}
     */
    private function ringkasanPembayaran(?Pendaftaran $pendaftaran): array
    {
        if (! $pendaftaran) {
            return ['label' => 'Belum ada tagihan', 'lunas' => false, 'nominal' => null];
        }

        if (! $pendaftaran->jalur || ! $pendaftaran->jalur->punya_biaya_pendaftaran) {
            return ['label' => 'Gratis', 'lunas' => true, 'nominal' => null];
        }

        $nominal = BiayaPendaftaran::query()
            ->where('periode_id', $pendaftaran->periode_id)
            ->where('jalur_id', $pendaftaran->jalur_id)
            ->value('nominal');

        $pembayaran = $pendaftaran->pembayaran()
            ->where('jenis', 'pendaftaran')
            ->latest()
            ->first();

        $label = match ($pembayaran?->status) {
            'paid'    => 'Lunas',
            'pending' => 'Menunggu pembayaran',
            'expired' => 'Kedaluwarsa',
            'failed'  => 'Gagal',
            default   => 'Belum dibayar',
        };

        return [
            'label'   => $label,
            'lunas'   => $pembayaran?->status === 'paid',
            'nominal' => $nominal === null ? null : (int) $nominal,
        ];
    }
}
