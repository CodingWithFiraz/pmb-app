<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fakultas;
use App\Models\Prodi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProdiController extends Controller
{
    public function index(): View
    {
        $programs = Prodi::with('fakultas')->orderBy('kode')->paginate(15);

        return view('admin.programs.index', compact('programs'));
    }

    public function create(): View
    {
        $fakultas = Fakultas::orderBy('nama')->get();

        return view('admin.programs.create', compact('fakultas'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kode'        => 'required|string|max:20|unique:prodi,kode',
            'nama'        => 'required|string|max:255',
            'fakultas_id' => 'required|exists:fakultas,id',
            'jenjang'     => 'required|string|max:10',
            'deskripsi'   => 'nullable|string|max:1000',
            'kuota'       => 'nullable|integer|min:1',
        ]);

        Prodi::create($validated + ['slug' => $this->slugUnik($validated['nama'])]);

        return redirect()->route('admin.program-studi.index')->with('success', 'Program studi berhasil ditambahkan.');
    }

    public function edit(Prodi $prodi): View
    {
        $fakultas = Fakultas::orderBy('nama')->get();

        return view('admin.programs.edit', compact('prodi', 'fakultas'));
    }

    public function update(Request $request, Prodi $prodi): RedirectResponse
    {
        $validated = $request->validate([
            'kode'        => 'required|string|max:20|unique:prodi,kode,'.$prodi->id,
            'nama'        => 'required|string|max:255',
            'fakultas_id' => 'required|exists:fakultas,id',
            'jenjang'     => 'required|string|max:10',
            'deskripsi'   => 'nullable|string|max:1000',
            'kuota'       => 'nullable|integer|min:1',
        ]);

        $prodi->update($validated + ['slug' => $this->slugUnik($validated['nama'], $prodi->id)]);

        return redirect()->route('admin.program-studi.index')->with('success', 'Program studi berhasil diperbarui.');
    }

    public function destroy(Prodi $prodi): RedirectResponse
    {
        if ($prodi->pendaftaran()->exists() || $prodi->pendaftaranAlternatif()->exists()) {
            return back()->with('error', 'Program studi sudah dipakai pendaftar dan tidak dapat dihapus.');
        }

        $prodi->delete();

        return redirect()->route('admin.program-studi.index')->with('success', 'Program studi berhasil dihapus.');
    }

    /**
     * Slug unik untuk URL halaman publik program studi.
     */
    private function slugUnik(string $nama, ?int $ignoreId = null): string
    {
        $base = Str::slug($nama);
        $slug = $base;
        $urut = 2;

        while (Prodi::where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $base.'-'.$urut++;
        }

        return $slug;
    }
}
