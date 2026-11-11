<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BeritaController extends Controller
{
    public function index(Request $request): View
    {
        $query = Berita::query()->latest('published_at')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where('judul', 'like', '%'.$request->search.'%');
        }

        $berita = $query->paginate(15)->withQueryString();

        return view('admin.berita.index', compact('berita'));
    }

    public function create(): View
    {
        return view('admin.berita.form', ['berita' => new Berita()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'judul'        => 'required|string|max:255',
            'slug'         => 'nullable|string|max:255',
            'image_url'    => 'nullable|url|max:255',
            'deskripsi'    => 'required|string',
            'author'       => 'required|string|max:255',
            'published_at' => 'nullable|date',
            'status'       => 'required|in:draft,publish',
        ], [
            'judul.required'     => 'Judul berita wajib diisi.',
            'deskripsi.required' => 'Isi berita wajib diisi.',
            'author.required'    => 'Penulis wajib diisi.',
            'status.required'    => 'Status berita wajib dipilih.',
            'status.in'          => 'Status berita tidak valid.',
            'image_url.url'      => 'Link gambar harus berupa URL yang valid.',
            'published_at.date'  => 'Tanggal publikasi tidak valid.',
        ]);

        $validated['slug'] = $this->slugUnik(($validated['slug'] ?? '') ?: $validated['judul']);
        $validated['published_at'] = $this->tentukanTanggalPublish($validated);

        Berita::create($validated);

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil disimpan.');
    }

    public function edit(Berita $berita): View
    {
        return view('admin.berita.form', compact('berita'));
    }

    public function update(Request $request, Berita $berita): RedirectResponse
    {
        $validated = $request->validate([
            'judul'        => 'required|string|max:255',
            'slug'         => 'nullable|string|max:255',
            'image_url'    => 'nullable|url|max:255',
            'deskripsi'    => 'required|string',
            'author'       => 'required|string|max:255',
            'published_at' => 'nullable|date',
            'status'       => 'required|in:draft,publish',
        ], [
            'judul.required'     => 'Judul berita wajib diisi.',
            'deskripsi.required' => 'Isi berita wajib diisi.',
            'author.required'    => 'Penulis wajib diisi.',
            'status.required'    => 'Status berita wajib dipilih.',
            'status.in'          => 'Status berita tidak valid.',
            'image_url.url'      => 'Link gambar harus berupa URL yang valid.',
            'published_at.date'  => 'Tanggal publikasi tidak valid.',
        ]);

        $validated['slug'] = $this->slugUnik(($validated['slug'] ?? '') ?: $validated['judul'], $berita->id);
        $validated['published_at'] = $this->tentukanTanggalPublish($validated, $berita);

        $berita->update($validated);

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil diperbarui.');
    }

    public function destroy(Berita $berita): RedirectResponse
    {
        $berita->delete();

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil dihapus.');
    }

    /**
     * Slug unik. Dibuat otomatis dari judul bila slug tidak diisi.
     */
    private function slugUnik(string $sumber, ?int $ignoreId = null): string
    {
        $base = Str::slug($sumber);
        $slug = $base;
        $urut = 2;

        while (Berita::where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $base.'-'.$urut++;
        }

        return $slug;
    }

    /**
     * Berita berstatus publish otomatis memakai waktu sekarang bila tanggalnya kosong.
     *
     * @param  array<string, mixed>  $validated
     */
    private function tentukanTanggalPublish(array $validated, ?Berita $berita = null): ?string
    {
        if (! empty($validated['published_at'] ?? null)) {
            return $validated['published_at'];
        }

        if ($validated['status'] === 'publish') {
            return now()->format('Y-m-d H:i:s');
        }

        // Berstatus draft: tanggal publikasi lama (jika ada) tetap dipertahankan.
        return $berita?->published_at?->format('Y-m-d H:i:s');
    }
}
