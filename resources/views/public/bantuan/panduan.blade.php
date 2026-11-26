@extends('layouts.app')
@section('title', 'Panduan Pendaftaran')
@section('body-class', '!bg-[#F8F6F9]')

@section('full-width-content')
<section class="w-full bg-[#F8F6F9] pt-12 pb-14 lg:pt-16 lg:pb-20">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
        {{-- Header halaman: teks tanpa container --}}
        <div class="mb-10 max-w-2xl">
            <h1 class="hero-enter font-heading text-4xl font-extrabold leading-[1.08] tracking-[-0.035em] text-[#14141F] sm:text-5xl">
                Panduan Pendaftaran
            </h1>
            <p class="hero-enter-delay-1 mt-4 text-base leading-relaxed text-[#3E465C] sm:text-lg">
                Langkah-langkah pendaftaran mahasiswa baru di PNM Jakarta.
            </p>
        </div>

        <article class="glass-card-jalur rounded-2xl p-7 sm:p-10">
            <div class="text-sm font-semibold uppercase tracking-wider text-[#4E566E]">Tahapan Pendaftaran</div>
            <h2 class="mt-2 font-heading text-2xl font-bold leading-snug tracking-tight text-[#14141F] sm:text-3xl">
                Alur Lengkap Calon Mahasiswa Baru
            </h2>

            <ol class="mt-8 divide-y divide-white/75">
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
                        'judul' => 'Memilih Jalur Masuk dan Program Studi',
                        'isi'   => 'Pilih jalur masuk yang sesuai dengan prestasi dan kemampuan Anda, kemudian tentukan program studi pilihan utama dan pilihan kedua. Golongan UKT ditetapkan otomatis berdasarkan penghasilan orang tua/wali.',
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
                    <li class="flex items-start gap-4 py-5 first:pt-0 last:pb-0 sm:gap-5">
                        <span class="mt-0.5 inline-flex size-10 shrink-0 items-center justify-center rounded-xl bg-gradient-to-r from-[#BF8AFC] to-[#FA7F94] font-numeric text-sm font-bold text-white shadow-xs">
                            {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                        </span>
                        <div>
                            <h3 class="font-heading text-lg font-bold leading-snug tracking-tight text-[#14141F] sm:text-xl">
                                {{ $langkah['judul'] }}
                            </h3>
                            <p class="mt-2 text-base leading-relaxed text-[#3E465C]">
                                {{ $langkah['isi'] }}
                            </p>
                        </div>
                    </li>
                @endforeach
            </ol>

            <div class="mt-8 flex flex-wrap gap-3 border-t border-white/75 pt-7">
                <a href="{{ route('register') }}"
                   class="inline-flex h-12 items-center justify-center rounded-xl bg-gradient-to-r from-[#BF8AFC] to-[#FA7F94] px-6 text-base font-semibold text-white shadow-xs transition duration-150 hover:opacity-95 hover:-translate-y-0.5 active:translate-y-0 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#BF8AFC] focus-visible:ring-offset-2">
                    Buat Akun Pendaftar
                </a>
                <a href="{{ route('public.informasi-pmb') }}"
                   class="inline-flex h-12 items-center justify-center rounded-xl border border-[#DDE1F5] bg-white px-6 text-base font-semibold text-[#14141F] transition duration-150 hover:-translate-y-0.5 hover:bg-[#F8F9FF] active:translate-y-0">
                    Lihat Informasi PMB
                </a>
                <a href="{{ route('public.bantuan.faq') }}"
                   class="inline-flex h-12 items-center justify-center rounded-xl border border-[#DDE1F5] bg-white px-6 text-base font-semibold text-[#14141F] transition duration-150 hover:-translate-y-0.5 hover:bg-[#F8F9FF] active:translate-y-0">
                    Baca Pertanyaan Umum
                </a>
            </div>
        </article>
    </div>
</section>
@endsection
