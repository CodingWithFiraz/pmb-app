<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Pengaturan;

class BantuanController extends Controller
{
    public function panduan()
    {
        return view('public.bantuan.panduan');
    }

    public function faq()
    {
        $faq = Faq::orderBy('urutan')->get()->groupBy('kategori');

        return view('public.bantuan.faq', compact('faq'));
    }

    public function kontak()
    {
        $pengaturan = Pengaturan::many([
            'nama_kampus', 'nama_singkat', 'alamat_kampus', 'email_kampus',
            'telepon_kampus', 'wa_kampus', 'jam_operasional', 'maps_embed',
        ]);

        return view('public.bantuan.kontak', compact('pengaturan'));
    }
}
