@extends('layouts.public')
@section('title', 'Panduan Pendaftaran')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Panduan Pendaftaran</h1>
    <p class="text-gray-600 mt-1">Langkah-langkah pendaftaran mahasiswa baru di PNM Jakarta.</p>
</div>

<div class="bg-white shadow rounded-xl p-6 md:p-8">
    <ol class="space-y-6">
        @foreach([
            [
                'judul' => 'Membuat Akun Pendaftar',
                'isi'   => 'Klik tombol "Daftar" pada pojok kanan atas halaman. Isi nama, email, dan password Anda. Akun ini digunakan untuk memantau seluruh proses pendaftaran dan dapat digunakan untuk masuk kembali di kemudian hari.',
            ],
            [
                'judul' => 'Melengkapi Data Diri',
                'isi'   => 'Setelah masuk ke dashboard, lengkapi data pribadi dan data pendidikan Anda, seperti NIK, NISN, tempat dan tanggal lahir, alamat lengkap, asal sekolah, serta data orang tua dan penghasilan orang tua. Data ini menentukan golongan UKT Anda.',
            ],
            [
                'judul' => 'Memilih Jalur Masuk, Program Studi, dan Golongan UKT',
                'isi'   => 'Pilih jalur masuk yang sesuai dengan prestasi dan kemampuan Anda, kemudian tentukan program studi pilihan utama dan pilihan kedua. Pastikan Anda memilih golongan UKT yang benar sesuai penghasilan orang tua/wali.',
            ],
            [
                'judul' => 'Mengunggah Dokumen Persyaratan',
                'isi'   => 'Unggah dokumen sesuai daftar yang diwajibkan oleh jalur masuk Anda. Jalur SNBP memerlukan rapor dan piagam prestasi, jalur SNBT memerlukan rapor, sedangkan jalur Mandiri memerlukan KTP, KK, pas foto, ijazah atau surat keterangan lulus, rapor, dan piagam prestasi.',
            ],
            [
                'judul' => 'Melakukan Pembayaran',
                'isi'   => 'Lakukan pembayaran biaya pendaftaran (khusus jalur Mandiri) dan biaya UKT sesuai tarif yang tertera pada halaman Informasi PMB. Simpan bukti pembayaran Anda dengan baik.',
            ],
            [
                'judul' => 'Menunggu Verifikasi dan Mengikuti Ujian',
                'isi'   => 'Panitia PMB akan memeriksa kelengkapan data dan dokumen Anda. Setelah dinyatakan terverifikasi, Anda akan menerima jadwal ujian dan surat panggilan melalui email serta dashboard Anda.',
            ],
            [
                'judul' => 'Pengumuman Kelulusan',
                'isi'   => 'Hasil seleksi diumumkan melalui dashboard dan email. Calon mahasiswa yang dinyatakan lulus wajib melakukan daftar ulang sesuai jadwal yang ditentukan.',
            ],
        ] as $index => $langkah)
            <li class="flex gap-4">
                <div class="shrink-0 w-9 h-9 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold">
                    {{ $index + 1 }}
                </div>
                <div>
                    <h2 class="font-bold text-gray-800 text-lg">{{ $langkah['judul'] }}</h2>
                    <p class="text-gray-600 mt-1 leading-relaxed">{{ $langkah['isi'] }}</p>
                </div>
            </li>
        @endforeach
    </ol>

    <div class="mt-8 pt-6 border-t border-gray-200 flex flex-wrap gap-3">
        <a href="{{ route('public.informasi-pmb') }}" class="bg-blue-600 text-white px-5 py-2 rounded-lg font-medium hover:bg-blue-700">
            Lihat Informasi PMB
        </a>
        <a href="{{ route('public.bantuan.faq') }}" class="border border-blue-600 text-blue-600 px-5 py-2 rounded-lg font-medium hover:bg-blue-50">
            Baca Pertanyaan Umum
        </a>
        <a href="{{ route('register') }}" class="border border-gray-300 text-gray-600 px-5 py-2 rounded-lg hover:bg-gray-50">
            Buat Akun Pendaftar
        </a>
    </div>
</div>
@endsection
