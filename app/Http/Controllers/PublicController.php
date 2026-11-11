<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\BiayaPendaftaran;
use App\Models\Fakultas;
use App\Models\GolonganUkt;
use App\Models\JalurMasuk;
use App\Models\Pengaturan;
use App\Models\PeriodePmb;
use App\Models\Prodi;
use App\Models\TarifUkt;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function index(Request $request)
    {
        $activePeriod = PeriodePmb::getActive();

        $statusPeriode = null;

        if ($activePeriod) {
            $sekarang = now();

            if ($sekarang->lt($activePeriod->tanggal_mulai)) {
                $statusPeriode = 'belum';
            } elseif ($sekarang->gt($activePeriod->tanggal_selesai->copy()->endOfDay())) {
                $statusPeriode = 'tutup';
            } else {
                $statusPeriode = 'buka';
            }
        }

        $berita = Berita::tayang()
            ->latest('published_at')
            ->take(3)
            ->get();

        $fakultas = Fakultas::with(['prodi' => fn ($q) => $q->orderBy('nama')])
            ->withCount('prodi')
            ->orderBy('nama')
            ->get();

        $jalur = JalurMasuk::orderBy('urutan')->get();

        $biaya = collect();
        $uktMulai = collect();

        if ($activePeriod) {
            $biaya = BiayaPendaftaran::where('periode_id', $activePeriod->id)
                ->get()
                ->keyBy('jalur_id');

            $uktMulai = TarifUkt::where('periode_id', $activePeriod->id)
                ->selectRaw('jalur_id, MIN(nominal) as nominal_minimal')
                ->groupBy('jalur_id')
                ->pluck('nominal_minimal', 'jalur_id');
        }

        $pengaturan = Pengaturan::many(['nama_kampus', 'nama_singkat', 'tagline']);

        return view('public.home', compact(
            'activePeriod',
            'statusPeriode',
            'berita',
            'fakultas',
            'jalur',
            'biaya',
            'uktMulai',
            'pengaturan'
        ));
    }

    public function tentangKampus()
    {
        $pengaturan = Pengaturan::many([
            'nama_kampus', 'nama_singkat', 'visi', 'misi', 'sejarah',
            'sambutan', 'sambutan_nama', 'sambutan_jabatan',
            'alamat_kampus', 'email_kampus', 'telepon_kampus', 'wa_kampus',
        ]);

        $fakultas = Fakultas::withCount('prodi')->orderBy('nama')->get();

        $prodi = Prodi::count();

        return view('public.tentang-kampus', compact('pengaturan', 'fakultas', 'prodi'));
    }

    public function fakultas()
    {
        $fakultas = Fakultas::withCount('prodi')->orderBy('nama')->get();

        $pengaturan = Pengaturan::many(['nama_kampus', 'nama_singkat']);

        return view('public.fakultas.index', compact('fakultas', 'pengaturan'));
    }

    public function fakultasDetail(Fakultas $fakultas)
    {
        $fakultas->load(['prodi' => fn ($q) => $q->orderBy('nama')]);

        return view('public.fakultas.show', compact('fakultas'));
    }

    public function prodiIndex()
    {
        $programs = Prodi::with('fakultas')->orderBy('nama')->get();

        return view('public.programs', compact('programs'));
    }

    public function prodiDetail(Prodi $prodi)
    {
        $prodi->load('fakultas');

        $periode = PeriodePmb::getActive();

        $tarif = collect();

        if ($periode) {
            $tarif = TarifUkt::with(['jalur', 'golongan'])
                ->where('periode_id', $periode->id)
                ->where('prodi_id', $prodi->id)
                ->orderBy('jalur_id')
                ->orderBy('golongan_id')
                ->get()
                ->groupBy('jalur.nama');
        }

        $prodiLain = Prodi::where('fakultas_id', $prodi->fakultas_id)
            ->where('id', '!=', $prodi->id)
            ->orderBy('nama')
            ->get();

        $golongan = GolonganUkt::orderBy('kode')->get();

        return view('public.prodi.show', compact('prodi', 'periode', 'tarif', 'prodiLain', 'golongan'));
    }
}
