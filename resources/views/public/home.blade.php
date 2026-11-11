@extends('layouts.public')
@section('title', 'Beranda')

@section('content')
{{-- 1. Hero --}}
<section class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-700 via-blue-800 to-indigo-900 text-white">
    <div class="pointer-events-none absolute -right-20 -top-20 h-64 w-64 rounded-full bg-white/5"></div>
    <div class="pointer-events-none absolute -left-16 bottom-0 h-56 w-56 rounded-full bg-white/5"></div>

    <div class="relative max-w-3xl px-6 py-14 md:px-12 md:py-20">
        <div class="mb-5 inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3 py-1 text-xs font-medium">
            <span class="h-2 w-2 rounded-full {{ $statusPeriode === 'buka' ? 'bg-green-400' : 'bg-amber-400' }}"></span>
            @if($statusPeriode === 'buka' && $activePeriod)
                Pendaftaran dibuka &middot; {{ $activePeriod->tahun_akademik }}
            @elseif($statusPeriode === 'belum' && $activePeriod)
                Pendaftaran dibuka mulai {{ $activePeriod->tanggal_mulai->translatedFormat('d F Y') }}
            @elseif($statusPeriode === 'tutup' && $activePeriod)
                Pendaftaran periode {{ $activePeriod->tahun_akademik }} telah ditutup
            @else
                Informasi periode pendaftaran menyusul
            @endif
        </div>

        <h1 class="text-3xl font-bold leading-tight md:text-5xl">
            {{ $pengaturan['nama_kampus'] ?? 'Penerimaan Mahasiswa Baru' }}
        </h1>

        <p class="mt-4 max-w-2xl text-lg leading-relaxed text-blue-100 md:text-xl">
            {{ $pengaturan['tagline'] ?? 'Portal resmi penerimaan mahasiswa baru.' }}
        </p>

        <div class="mt-8 flex flex-wrap gap-3">
            <a href="{{ route('register') }}"
                class="rounded-lg bg-white px-6 py-3 font-semibold text-blue-700 transition hover:bg-blue-50">
                Daftar Sekarang
            </a>
            <a href="{{ route('public.bantuan.panduan') }}"
                class="rounded-lg border border-white/40 px-6 py-3 font-semibold text-white transition hover:bg-white/10">
                Lihat Panduan
            </a>
        </div>

        <div class="mt-8 flex flex-wrap gap-x-6 gap-y-2 text-sm text-blue-200">
            <span><strong class="text-white">{{ $fakultas->count() }}</strong> fakultas</span>
            <span><strong class="text-white">{{ $fakultas->sum('prodi_count') }}</strong> program studi</span>
            <span><strong class="text-white">{{ $jalur->count() }}</strong> jalur masuk</span>
        </div>
    </div>
</section>

{{-- 2. Keunggulan --}}
<section class="mt-14">
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-gray-800 md:text-3xl">
            Mengapa Memilih {{ $pengaturan['nama_singkat'] ?? 'Kami' }}?
        </h2>
        <p class="mt-2 text-gray-600">Lingkungan belajar yang menghubungkan akademik, industri, dan karya nyata.</p>
    </div>

    <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-4">
        @foreach([
            ['judul' => 'Kurikulum Berbasis Industri', 'isi' => 'Materi dan proyek disusun bersama mitra industri agar relevan dengan kebutuhan dunia kerja.'],
            ['judul' => 'Dosen Praktisi & Akademisi', 'isi' => 'Belajar langsung dari dosen berpengalaman serta praktisi yang aktif di bidangnya.'],
            ['judul' => 'Laboratorium Modern', 'isi' => 'Fasilitas studio dan laboratorium mendukung praktik grafika, desain, animasi, dan multimedia.'],
            ['judul' => 'Jaringan Mitra Kreatif', 'isi' => 'Akses magang, kolaborasi proyek, dan penyaluran kerja melalui jejaring mitra kampus.'],
        ] as $keunggulan)
            <div class="rounded-xl bg-white p-6 shadow">
                <div class="mb-4 h-1 w-12 rounded bg-blue-600"></div>
                <h3 class="font-bold text-gray-800">{{ $keunggulan['judul'] }}</h3>
                <p class="mt-2 text-sm leading-relaxed text-gray-600">{{ $keunggulan['isi'] }}</p>
            </div>
        @endforeach
    </div>
</section>

{{-- 3. Jalur Masuk --}}
<section class="mt-14">
    <div class="mb-6 flex items-end justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 md:text-3xl">Jalur Masuk</h2>
            <p class="mt-1 text-gray-600">Pilih jalur yang paling sesuai dengan prestasi dan kemampuanmu.</p>
        </div>
        <a href="{{ route('public.informasi-pmb') }}" class="shrink-0 text-sm text-blue-600 hover:underline">
            Informasi lengkap &rarr;
        </a>
    </div>

    <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
        @forelse($jalur as $j)
            @php
                $b = $biaya->get($j->id);
                $minimal = $uktMulai->get($j->id);
            @endphp

            <div class="flex flex-col rounded-xl border-t-4 border-blue-600 bg-white p-6 shadow">
                <span class="w-fit rounded bg-blue-50 px-2 py-0.5 font-mono text-xs font-bold text-blue-600">
                    {{ $j->kode }}
                </span>

                <h3 class="mt-3 text-lg font-bold text-gray-800">{{ $j->nama }}</h3>
                <p class="mt-2 flex-1 text-sm leading-relaxed text-gray-600">{{ $j->deskripsi }}</p>

                <dl class="mt-5 space-y-2 border-t border-gray-100 pt-4 text-sm">
                    <div class="flex items-center justify-between gap-3">
                        <dt class="text-gray-500">Biaya pendaftaran</dt>
                        <dd class="font-semibold text-gray-800">
                            @if($b && $b->nominal !== null)
                                Rp {{ number_format($b->nominal, 0, ',', '.') }}
                            @else
                                <span class="text-green-600">Gratis</span>
                            @endif
                        </dd>
                    </div>

                    @if($minimal !== null)
                        <div class="flex items-center justify-between gap-3">
                            <dt class="text-gray-500">UKT mulai</dt>
                            <dd class="font-semibold text-gray-800">
                                Rp {{ number_format($minimal, 0, ',', '.') }}
                            </dd>
                        </div>
                    @endif
                </dl>
            </div>
        @empty
            <p class="text-gray-500 md:col-span-3">Data jalur masuk belum tersedia.</p>
        @endforelse
    </div>
</section>

{{-- 4. Fakultas & Program Studi --}}
<section class="mt-14">
    <div class="mb-6 flex items-end justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 md:text-3xl">Fakultas &amp; Program Studi</h2>
            <p class="mt-1 text-gray-600">Temukan program studi yang sesuai dengan minat dan bakatmu.</p>
        </div>
        <a href="{{ route('public.fakultas') }}" class="shrink-0 text-sm text-blue-600 hover:underline">
            Semua fakultas &rarr;
        </a>
    </div>

    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
        @forelse($fakultas as $f)
            <div class="rounded-xl bg-white p-6 shadow">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <span class="rounded bg-blue-50 px-2 py-0.5 font-mono text-xs font-bold text-blue-600">
                            {{ $f->kode }}
                        </span>
                        <h3 class="mt-2 font-bold text-lg text-gray-800">{{ $f->nama }}</h3>
                    </div>
                    <span class="shrink-0 rounded-full bg-gray-100 px-3 py-1 text-xs text-gray-600">
                        {{ $f->prodi_count }} prodi
                    </span>
                </div>

                @if($f->deskripsi)
                    <p class="mt-2 text-sm leading-relaxed text-gray-600">{{ $f->deskripsi }}</p>
                @endif

                <ul class="mt-4 space-y-1">
                    @foreach($f->prodi as $p)
                        <li>
                            <a href="{{ route('public.prodi', $p) }}"
                                class="flex items-center justify-between gap-3 rounded-lg px-3 py-2 text-sm transition hover:bg-blue-50">
                                <span class="font-medium text-gray-700">{{ $p->nama }}</span>
                                <span class="text-xs text-gray-400">{{ $p->jenjang }} &middot; {{ $p->kode }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>

                <a href="{{ route('public.fakultas.detail', $f) }}"
                    class="mt-3 inline-block text-sm font-medium text-blue-600 hover:underline">
                    Lihat fakultas &rarr;
                </a>
            </div>
        @empty
            <p class="text-gray-500 md:col-span-2">Data fakultas belum tersedia.</p>
        @endforelse
    </div>
</section>

{{-- 5. Alur Pendaftaran --}}
<section class="mt-14">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800 md:text-3xl">Alur Pendaftaran</h2>
        <p class="mt-1 text-gray-600">Enam langkah dari pendaftaran sampai pengumuman kelulusan.</p>
    </div>

    <div class="rounded-xl bg-white p-6 shadow md:p-8">
        <ol class="relative ml-3 space-y-6 border-l-2 border-blue-100 pl-7">
            @foreach([
                ['judul' => 'Daftar Akun', 'isi' => 'Buat akun pendaftar menggunakan email aktif dan password yang aman.'],
                ['judul' => 'Lengkapi Data Diri', 'isi' => 'Isi data pribadi, data pendidikan, serta data orang tua atau wali.'],
                ['judul' => 'Pilih Jalur, Prodi & UKT', 'isi' => 'Tentukan jalur masuk, program studi pilihan, dan golongan UKT.'],
                ['judul' => 'Unggah Dokumen', 'isi' => 'Lengkapi dokumen persyaratan sesuai jalur masuk yang dipilih.'],
                ['judul' => 'Verifikasi & Pembayaran', 'isi' => 'Panitia memverifikasi berkas. Jalur Mandiri melakukan pembayaran biaya pendaftaran.'],
                ['judul' => 'Pengumuman Kelulusan', 'isi' => 'Pantau hasil seleksi melalui dashboard dan email yang terdaftar.'],
            ] as $index => $langkah)
                <li class="relative">
                    <span class="absolute -left-[41px] flex h-7 w-7 items-center justify-center rounded-full bg-blue-600 text-xs font-bold text-white">
                        {{ $index + 1 }}
                    </span>
                    <h3 class="font-semibold text-gray-800">{{ $langkah['judul'] }}</h3>
                    <p class="mt-1 text-sm leading-relaxed text-gray-600">{{ $langkah['isi'] }}</p>
                </li>
            @endforeach
        </ol>
    </div>
</section>

{{-- 6. Jadwal Penting --}}
<section class="mt-14">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800 md:text-3xl">Jadwal Penting</h2>
        <p class="mt-1 text-gray-600">Tanggal kunci periode penerimaan mahasiswa baru yang sedang aktif.</p>
    </div>

    @if($activePeriod)
        <div class="rounded-xl bg-white p-6 shadow md:p-8">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h3 class="font-bold text-lg text-gray-800">{{ $activePeriod->nama }}</h3>
                    <p class="mt-1 text-sm text-gray-500">Tahun Akademik {{ $activePeriod->tahun_akademik }}</p>
                </div>

                <span class="rounded-full px-3 py-1 text-xs font-semibold
                    {{ $statusPeriode === 'buka' ? 'bg-green-100 text-green-700' : ($statusPeriode === 'belum' ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-600') }}">
                    @if($statusPeriode === 'buka')
                        Sedang dibuka
                    @elseif($statusPeriode === 'belum')
                        Belum dibuka
                    @else
                        Telah ditutup
                    @endif
                </span>
            </div>

            <dl class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="rounded-lg bg-blue-50 p-4">
                    <dt class="text-sm text-blue-700">Pendaftaran dibuka</dt>
                    <dd class="mt-1 text-lg font-bold text-blue-900">
                        {{ $activePeriod->tanggal_mulai->translatedFormat('d F Y') }}
                    </dd>
                </div>
                <div class="rounded-lg bg-blue-50 p-4">
                    <dt class="text-sm text-blue-700">Pendaftaran ditutup</dt>
                    <dd class="mt-1 text-lg font-bold text-blue-900">
                        {{ $activePeriod->tanggal_selesai->translatedFormat('d F Y') }}
                    </dd>
                </div>
            </dl>

            <p class="mt-5 text-sm text-gray-500">
                Jadwal ujian seleksi dan pengumuman kelulusan diumumkan melalui berita resmi serta dashboard pendaftar.
            </p>

            <a href="{{ route('public.informasi-pmb') }}" class="mt-3 inline-block text-sm font-medium text-blue-600 hover:underline">
                Lihat informasi PMB &rarr;
            </a>
        </div>
    @else
        <div class="rounded-xl border border-amber-300 bg-amber-50 p-6 text-amber-800">
            Saat ini belum ada periode pendaftaran yang aktif. Pantau halaman berita untuk informasi terbaru.
        </div>
    @endif
</section>

{{-- 7. Berita Terbaru --}}
<section class="mt-14">
    <div class="mb-6 flex items-end justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 md:text-3xl">Berita Terbaru</h2>
            <p class="mt-1 text-gray-600">Informasi dan pengumuman terkini seputar PMB.</p>
        </div>
        <a href="{{ route('public.berita') }}" class="shrink-0 text-sm text-blue-600 hover:underline">
            Semua berita &rarr;
        </a>
    </div>

    <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
        @forelse($berita as $item)
            <a href="{{ route('public.berita.detail', $item) }}"
                class="block overflow-hidden rounded-xl bg-white shadow transition hover:shadow-lg">
                @if($item->image_url)
                    <img src="{{ $item->image_url }}" alt="{{ $item->judul }}" class="h-40 w-full object-cover">
                @else
                    <div class="flex h-24 items-center justify-center bg-gradient-to-br from-blue-600 to-blue-800 text-white">
                        <span class="text-sm font-medium">{{ $item->author }}</span>
                    </div>
                @endif

                <div class="p-5">
                    <div class="mb-1 text-xs text-gray-400">
                        {{ $item->published_at?->translatedFormat('d F Y') }}
                    </div>
                    <h3 class="font-bold leading-snug text-gray-800">{{ $item->judul }}</h3>
                    <p class="mt-2 text-sm leading-relaxed text-gray-600">{{ \Illuminate\Support\Str::limit($item->deskripsi, 140) }}</p>
                    <div class="mt-3 text-sm font-medium text-blue-600">Baca selengkapnya &rarr;</div>
                </div>
            </a>
        @empty
            <p class="text-gray-500 md:col-span-3">Belum ada berita yang dipublikasikan.</p>
        @endforelse
    </div>
</section>

{{-- 8. Bantuan Cepat --}}
<section class="mt-14">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800 md:text-3xl">Bantuan Cepat</h2>
        <p class="mt-1 text-gray-600">Butuh informasi lebih lanjut? Mulai dari salah satu halaman berikut.</p>
    </div>

    <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
        <a href="{{ route('public.bantuan.panduan') }}"
            class="block rounded-xl bg-white p-6 shadow transition hover:shadow-lg">
            <h3 class="font-bold text-gray-800">Panduan Pendaftaran</h3>
            <p class="mt-2 text-sm leading-relaxed text-gray-600">Langkah demi langkah membuat akun, mengisi data, hingga unggah dokumen.</p>
            <div class="mt-4 text-sm font-medium text-blue-600">Buka panduan &rarr;</div>
        </a>

        <a href="{{ route('public.bantuan.faq') }}"
            class="block rounded-xl bg-white p-6 shadow transition hover:shadow-lg">
            <h3 class="font-bold text-gray-800">Pertanyaan Umum</h3>
            <p class="mt-2 text-sm leading-relaxed text-gray-600">Jawaban atas pertanyaan yang paling sering diajukan calon mahasiswa.</p>
            <div class="mt-4 text-sm font-medium text-blue-600">Lihat FAQ &rarr;</div>
        </a>

        <a href="{{ route('public.bantuan.kontak') }}"
            class="block rounded-xl bg-white p-6 shadow transition hover:shadow-lg">
            <h3 class="font-bold text-gray-800">Hubungi Kami</h3>
            <p class="mt-2 text-sm leading-relaxed text-gray-600">Kontak panitia PMB, alamat kampus, jam operasional, dan peta lokasi.</p>
            <div class="mt-4 text-sm font-medium text-blue-600">Buka kontak &rarr;</div>
        </a>
    </div>
</section>
@endsection
