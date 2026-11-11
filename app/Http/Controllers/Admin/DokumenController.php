<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DokumenJalur;
use App\Models\DokumenMaster;
use App\Models\JalurMasuk;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DokumenController extends Controller
{
    public function index(): View
    {
        $dokumen = DokumenMaster::withCount('jalur')->orderBy('nama')->paginate(15);

        return view('admin.dokumen.index', compact('dokumen'));
    }

    public function create(): View
    {
        return view('admin.dokumen.form', ['dokumen' => new DokumenMaster()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kode'      => 'required|string|max:20|unique:dokumen_master,kode',
            'nama'      => 'required|string|max:255',
            'tipe_file' => 'required|string|in:pdf,jpg',
            'max_size'  => 'required|integer|min:100|max:10240',
            'is_wajib'  => 'boolean',
        ], [
            'kode.unique'     => 'Kode dokumen sudah digunakan.',
            'tipe_file.in'    => 'Tipe berkas harus pdf atau jpg.',
            'max_size.min'    => 'Ukuran minimal 100 KB.',
            'max_size.max'    => 'Ukuran maksimal 10240 KB.',
        ]);

        DokumenMaster::create($validated + ['is_wajib' => $request->boolean('is_wajib')]);

        return redirect()->route('admin.dokumen.index')->with('success', 'Dokumen berhasil ditambahkan.');
    }

    public function edit(DokumenMaster $dokumen): View
    {
        return view('admin.dokumen.form', compact('dokumen'));
    }

    public function update(Request $request, DokumenMaster $dokumen): RedirectResponse
    {
        $validated = $request->validate([
            'kode'      => 'required|string|max:20|unique:dokumen_master,kode,'.$dokumen->id,
            'nama'      => 'required|string|max:255',
            'tipe_file' => 'required|string|in:pdf,jpg',
            'max_size'  => 'required|integer|min:100|max:10240',
            'is_wajib'  => 'boolean',
        ], [
            'kode.unique'  => 'Kode dokumen sudah digunakan.',
            'tipe_file.in' => 'Tipe berkas harus pdf atau jpg.',
            'max_size.min' => 'Ukuran minimal 100 KB.',
            'max_size.max' => 'Ukuran maksimal 10240 KB.',
        ]);

        $dokumen->update($validated + ['is_wajib' => $request->boolean('is_wajib')]);

        return redirect()->route('admin.dokumen.index')->with('success', 'Dokumen berhasil diperbarui.');
    }

    public function destroy(DokumenMaster $dokumen): RedirectResponse
    {
        $dokumen->delete();

        return redirect()->route('admin.dokumen.index')->with('success', 'Dokumen berhasil dihapus.');
    }

    /**
     * Halaman mapping dokumen ke jalur masuk.
     */
    public function mapping(DokumenMaster $dokumen): View
    {
        $jalur = JalurMasuk::orderBy('urutan')->get();

        $terpilih = DokumenJalur::where('dokumen_id', $dokumen->id)
            ->pluck('jalur_id')
            ->all();

        return view('admin.dokumen.mapping', compact('dokumen', 'jalur', 'terpilih'));
    }

    /**
     * Simpan mapping dokumen ke jalur masuk.
     */
    public function simpanMapping(Request $request, DokumenMaster $dokumen): RedirectResponse
    {
        $request->validate([
            'jalur' => 'nullable|array',
            'jalur.*' => 'integer|exists:jalur_masuk,id',
        ]);

        $jalurIds = $request->input('jalur', []);

        DokumenJalur::where('dokumen_id', $dokumen->id)
            ->whereNotIn('jalur_id', $jalurIds ?: [0])
            ->delete();

        foreach ($jalurIds as $jalurId) {
            DokumenJalur::updateOrCreate(
                ['dokumen_id' => $dokumen->id, 'jalur_id' => (int) $jalurId],
                []
            );
        }

        return redirect()
            ->route('admin.dokumen.index')
            ->with('success', 'Mapping dokumen '.$dokumen->nama.' berhasil disimpan.');
    }
}
