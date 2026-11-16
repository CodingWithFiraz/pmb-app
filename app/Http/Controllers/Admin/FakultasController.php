<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fakultas;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class FakultasController extends Controller
{
    public function index(): View
    {
        $fakultas = Fakultas::withCount('prodi')->orderBy('nama')->paginate(15);

        return view('admin.fakultas.index', compact('fakultas'));
    }

    public function create(): View
    {
        return view('admin.fakultas.form', ['fakultas' => new Fakultas]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kode'      => 'required|string|max:20|unique:fakultas,kode',
            'nama'      => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:1000',
        ]);

        Fakultas::create($validated + ['slug' => $this->slugUnik($validated['nama'])]);

        return redirect()->route('admin.fakultas.index')->with('success', 'Fakultas berhasil ditambahkan.');
    }

    public function edit(Fakultas $fakultas): View
    {
        return view('admin.fakultas.form', ['fakultas' => $fakultas]);
    }

    public function update(Request $request, Fakultas $fakultas): RedirectResponse
    {
        $validated = $request->validate([
            'kode'      => 'required|string|max:20|unique:fakultas,kode,'.$fakultas->id,
            'nama'      => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:1000',
        ]);

        $fakultas->update($validated + ['slug' => $this->slugUnik($validated['nama'], $fakultas->id)]);

        return redirect()->route('admin.fakultas.index')->with('success', 'Fakultas berhasil diperbarui.');
    }

    public function destroy(Fakultas $fakultas): RedirectResponse
    {
        if ($fakultas->prodi()->exists()) {
            return back()->with('error', 'Fakultas masih memiliki program studi dan tidak dapat dihapus.');
        }

        $fakultas->delete();

        return redirect()->route('admin.fakultas.index')->with('success', 'Fakultas berhasil dihapus.');
    }

    /**
     * Slug unik untuk URL halaman publik fakultas.
     */
    private function slugUnik(string $nama, ?int $ignoreId = null): string
    {
        // Nama fakultas tidak perlu awalan "Fakultas" pada slug URL.
        $base = Str::slug(preg_replace('/^fakultas\s+/iu', '', $nama));
        $slug = $base;
        $urut = 2;

        while (Fakultas::where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $base.'-'.$urut++;
        }

        return $slug;
    }
}
