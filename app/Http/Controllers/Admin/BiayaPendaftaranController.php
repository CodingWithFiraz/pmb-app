<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BiayaPendaftaran;
use App\Models\JalurMasuk;
use App\Models\PeriodePmb;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BiayaPendaftaranController extends Controller
{
    public function index(Request $request): View
    {
        $query = BiayaPendaftaran::with(['periode', 'jalur'])->latest();

        if ($request->filled('periode_id')) {
            $query->where('periode_id', $request->periode_id);
        }

        $biaya = $query->paginate(15)->withQueryString();

        $periodeList = PeriodePmb::orderByDesc('tahun_akademik')->get();
        $jalurList = JalurMasuk::orderBy('urutan')->get();

        return view('admin.biaya.index', compact('biaya', 'periodeList', 'jalurList'));
    }

    public function create(): View
    {
        return view('admin.biaya.form', [
            'biaya'      => new BiayaPendaftaran(),
            'periodeList' => PeriodePmb::orderByDesc('tahun_akademik')->get(),
            'jalurList'  => JalurMasuk::orderBy('urutan')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'periode_id' => 'required|exists:periode_pmb,id',
            'jalur_id'   => 'required|exists:jalur_masuk,id',
            'nominal'    => 'nullable|integer|min:0',
        ], [
            'periode_id.required' => 'Periode wajib dipilih.',
            'jalur_id.required'   => 'Jalur masuk wajib dipilih.',
            'nominal.integer'     => 'Nominal harus berupa angka.',
            'nominal.min'         => 'Nominal tidak boleh negatif.',
        ]);

        $validated['nominal'] = ($validated['nominal'] ?? null) === '' ? null : ($validated['nominal'] ?? null);

        BiayaPendaftaran::updateOrCreate(
            [
                'periode_id' => $validated['periode_id'],
                'jalur_id'   => $validated['jalur_id'],
            ],
            ['nominal' => $validated['nominal']]
        );

        return redirect()
            ->route('admin.biaya-pendaftaran.index')
            ->with('success', 'Biaya pendaftaran berhasil disimpan.');
    }

    public function edit(BiayaPendaftaran $biaya): View
    {
        return view('admin.biaya.form', [
            'biaya'       => $biaya,
            'periodeList' => PeriodePmb::orderByDesc('tahun_akademik')->get(),
            'jalurList'   => JalurMasuk::orderBy('urutan')->get(),
        ]);
    }

    public function update(Request $request, BiayaPendaftaran $biaya): RedirectResponse
    {
        $validated = $request->validate([
            'periode_id' => 'required|exists:periode_pmb,id',
            'jalur_id'   => 'required|exists:jalur_masuk,id',
            'nominal'    => 'nullable|integer|min:0',
        ], [
            'periode_id.required' => 'Periode wajib dipilih.',
            'jalur_id.required'   => 'Jalur masuk wajib dipilih.',
            'nominal.integer'     => 'Nominal harus berupa angka.',
            'nominal.min'         => 'Nominal tidak boleh negatif.',
        ]);

        $validated['nominal'] = ($validated['nominal'] ?? null) === '' ? null : ($validated['nominal'] ?? null);

        $biaya->update($validated);

        return redirect()
            ->route('admin.biaya-pendaftaran.index')
            ->with('success', 'Biaya pendaftaran berhasil diperbarui.');
    }

    public function destroy(BiayaPendaftaran $biaya): RedirectResponse
    {
        $biaya->delete();

        return redirect()
            ->route('admin.biaya-pendaftaran.index')
            ->with('success', 'Biaya pendaftaran berhasil dihapus.');
    }
}
