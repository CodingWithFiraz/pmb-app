<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FaqController extends Controller
{
    public function index(Request $request): View
    {
        $query = Faq::query()->orderBy('kategori')->orderBy('urutan');

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $faq = $query->paginate(15)->withQueryString();

        $kategori = Faq::distinct()->orderBy('kategori')->pluck('kategori');

        return view('admin.faq.index', compact('faq', 'kategori'));
    }

    public function create(): View
    {
        return view('admin.faq.form', ['faq' => new Faq()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'pertanyaan' => 'required|string|max:1000',
            'jawaban'    => 'required|string',
            'kategori'   => 'required|string|max:100',
            'urutan'     => 'required|integer|min:0',
        ], [
            'pertanyaan.required' => 'Pertanyaan wajib diisi.',
            'jawaban.required'    => 'Jawaban wajib diisi.',
            'kategori.required'   => 'Kategori wajib diisi.',
            'urutan.required'     => 'Urutan wajib diisi.',
            'urutan.integer'      => 'Urutan harus berupa angka.',
        ]);

        Faq::create($validated);

        return redirect()->route('admin.faq.index')->with('success', 'Pertanyaan berhasil disimpan.');
    }

    public function edit(Faq $faq): View
    {
        return view('admin.faq.form', compact('faq'));
    }

    public function update(Request $request, Faq $faq): RedirectResponse
    {
        $validated = $request->validate([
            'pertanyaan' => 'required|string|max:1000',
            'jawaban'    => 'required|string',
            'kategori'   => 'required|string|max:100',
            'urutan'     => 'required|integer|min:0',
        ], [
            'pertanyaan.required' => 'Pertanyaan wajib diisi.',
            'jawaban.required'    => 'Jawaban wajib diisi.',
            'kategori.required'   => 'Kategori wajib diisi.',
            'urutan.required'     => 'Urutan wajib diisi.',
            'urutan.integer'      => 'Urutan harus berupa angka.',
        ]);

        $faq->update($validated);

        return redirect()->route('admin.faq.index')->with('success', 'Pertanyaan berhasil diperbarui.');
    }

    public function destroy(Faq $faq): RedirectResponse
    {
        $faq->delete();

        return redirect()->route('admin.faq.index')->with('success', 'Pertanyaan berhasil dihapus.');
    }
}
