<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JalurMasuk;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JalurMasukController extends Controller
{
    public function index(): View
    {
        $jalur = JalurMasuk::withCount('dokumen')->orderBy('urutan')->paginate(15);

        return view('admin.jalur.index', compact('jalur'));
    }

    public function create(): View
    {
        return view('admin.jalur.form', ['jalur' => new JalurMasuk()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kode'                    => 'required|string|max:20|unique:jalur_masuk,kode',
            'nama'                    => 'required|string|max:255',
            'deskripsi'               => 'nullable|string|max:1000',
            'urutan'                  => 'required|integer|min:1',
            'punya_biaya_pendaftaran' => 'boolean',
        ]);

        JalurMasuk::create($validated + [
            'punya_biaya_pendaftaran' => $request->boolean('punya_biaya_pendaftaran'),
        ]);

        return redirect()->route('admin.jalur-masuk.index')->with('success', 'Jalur masuk berhasil ditambahkan.');
    }

    public function edit(JalurMasuk $jalur): View
    {
        return view('admin.jalur.form', compact('jalur'));
    }

    public function update(Request $request, JalurMasuk $jalur): RedirectResponse
    {
        $validated = $request->validate([
            'kode'                    => 'required|string|max:20|unique:jalur_masuk,kode,'.$jalur->id,
            'nama'                    => 'required|string|max:255',
            'deskripsi'               => 'nullable|string|max:1000',
            'urutan'                  => 'required|integer|min:1',
            'punya_biaya_pendaftaran' => 'boolean',
        ]);

        $jalur->update($validated + [
            'punya_biaya_pendaftaran' => $request->boolean('punya_biaya_pendaftaran'),
        ]);

        return redirect()->route('admin.jalur-masuk.index')->with('success', 'Jalur masuk berhasil diperbarui.');
    }

    public function destroy(JalurMasuk $jalur): RedirectResponse
    {
        if ($jalur->pendaftaran()->exists()) {
            return back()->with('error', 'Jalur masuk sudah dipakai pendaftar dan tidak dapat dihapus.');
        }

        $jalur->delete();

        return redirect()->route('admin.jalur-masuk.index')->with('success', 'Jalur masuk berhasil dihapus.');
    }
}
