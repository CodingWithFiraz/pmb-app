<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GolonganUkt;
use App\Models\JalurMasuk;
use App\Models\PeriodePmb;
use App\Models\Prodi;
use App\Models\TarifUkt;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TarifUktController extends Controller
{
    /**
     * Matrix tarif UKT (jalur x prodi x golongan).
     */
    public function index(Request $request): View
    {
        $periodeList = PeriodePmb::orderByDesc('tahun_akademik')->get();

        $periode = null;

        if ($request->filled('periode_id')) {
            $periode = PeriodePmb::findOrFail($request->periode_id);
        } else {
            $periode = PeriodePmb::getActive() ?? $periodeList->first();
        }

        $jalur = JalurMasuk::orderBy('urutan')->get();
        $prodi = Prodi::with('fakultas')->orderBy('nama')->get();
        $golongan = GolonganUkt::orderBy('kode')->get();

        $tarif = TarifUkt::where('periode_id', $periode?->id)
            ->get()
            ->keyBy(fn (TarifUkt $t): string => $t->jalur_id.'-'.$t->prodi_id.'-'.$t->golongan_id);

        return view('admin.tarif.index', compact('periode', 'periodeList', 'jalur', 'prodi', 'golongan', 'tarif'));
    }

    /**
     * Simpan nominal matrix hasil edit inline.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'periode_id' => 'required|exists:periode_pmb,id',
            'tarif'      => 'required|array',
            'tarif.*'    => 'nullable|integer|min:0',
        ], [
            'periode_id.required' => 'Periode wajib dipilih.',
            'tarif.required'      => 'Minimal satu nominal harus diisi.',
            'tarif.*.integer'     => 'Nominal harus berupa angka.',
            'tarif.*.min'         => 'Nominal tidak boleh negatif.',
        ]);

        $periodeId = (int) $validated['periode_id'];

        foreach ($validated['tarif'] as $kunci => $nominal) {
            if ($nominal === null || $nominal === '') {
                continue;
            }

            $bagian = explode('-', (string) $kunci);

            if (count($bagian) !== 3) {
                continue;
            }

            [$jalurId, $prodiId, $golonganId] = array_map('intval', $bagian);

            TarifUkt::updateOrCreate(
                [
                    'periode_id'  => $periodeId,
                    'jalur_id'    => $jalurId,
                    'prodi_id'    => $prodiId,
                    'golongan_id' => $golonganId,
                ],
                ['nominal' => (int) $nominal]
            );
        }

        return redirect()
            ->route('admin.tarif-ukt.index', ['periode_id' => $periodeId])
            ->with('success', 'Tarif UKT berhasil disimpan.');
    }
}
