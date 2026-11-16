<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GolonganUkt;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GolonganUktController extends Controller
{
    public function index(): View
    {
        $golongan = GolonganUkt::orderBy('kode')->paginate(15);

        return view('admin.golongan.index', compact('golongan'));
    }

    public function create(): View
    {
        return view('admin.golongan.form', ['golongan' => new GolonganUkt()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kode'       => 'required|string|max:10|unique:golongan_ukt,kode',
            'nama'       => 'required|string|max:255',
            'deskripsi'  => 'nullable|string|max:1000',
        ]);

        GolonganUkt::create($validated);

        return redirect()->route('admin.golongan-ukt.index')->with('success', 'Golongan UKT berhasil ditambahkan.');
    }

    public function edit(GolonganUkt $golongan): View
    {
        return view('admin.golongan.form', compact('golongan'));
    }

    public function update(Request $request, GolonganUkt $golongan): RedirectResponse
    {
        $validated = $request->validate([
            'kode'      => 'required|string|max:10|unique:golongan_ukt,kode,'.$golongan->id,
            'nama'      => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:1000',
        ]);

        $golongan->update($validated);

        return redirect()->route('admin.golongan-ukt.index')->with('success', 'Golongan UKT berhasil diperbarui.');
    }

    public function destroy(GolonganUkt $golongan): RedirectResponse
    {
        if ($golongan->pendaftaran()->exists() || $golongan->pendaftarProfiles()->exists()) {
            return back()->with('error', 'Golongan UKT sudah dipakai data pendaftar dan tidak dapat dihapus.');
        }

        $golongan->delete();

        return redirect()->route('admin.golongan-ukt.index')->with('success', 'Golongan UKT berhasil dihapus.');
    }
}
