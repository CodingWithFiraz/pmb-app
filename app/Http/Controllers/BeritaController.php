<?php

namespace App\Http\Controllers;

use App\Models\Berita;

class BeritaController extends Controller
{
    public function index()
    {
        // Hanya berita yang sudah tayang (publish dan tanggalnya sudah lewat).
        $berita = Berita::tayang()
            ->latest('published_at')
            ->paginate(9);

        return view('public.berita.index', compact('berita'));
    }

    public function show(Berita $berita)
    {
        abort_unless($berita->isTayang(), 404);

        return view('public.berita.show', compact('berita'));
    }
}
