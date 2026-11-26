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

        $biayaRecord = BiayaPendaftaran::updateOrCreate(
            [
                'periode_id' => $validated['periode_id'],
                'jalur_id'   => $validated['jalur_id'],
            ],
            ['nominal' => $validated['nominal']]
        );

        $this->sinkronkanTagihanPending($biayaRecord);

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

        $this->sinkronkanTagihanPending($biaya);

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

    /**
     * Perbarui juga nominal tagihan biaya pendaftaran yang masih belum lunas (pending/failed/expired)
     * agar sesuai dengan tarif terbaru yang diubah admin.
     */
    private function sinkronkanTagihanPending(BiayaPendaftaran $biaya): void
    {
        if ($biaya->nominal === null) {
            return;
        }

        \App\Models\Pembayaran::where('jenis', 'pendaftaran')
            ->where('status', '!=', 'paid')
            ->whereHas('pendaftaran', function ($q) use ($biaya) {
                $q->where('periode_id', $biaya->periode_id)
                    ->where('jalur_id', $biaya->jalur_id);
            })
            ->update([
                'nominal'    => (int) $biaya->nominal,
                'snap_token' => null,
                'snap_url'   => null,
            ]);
    }
}
