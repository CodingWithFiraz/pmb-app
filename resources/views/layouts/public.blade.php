<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PMB') — Penerimaan Mahasiswa Baru</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-800">

<nav class="bg-blue-700 text-white shadow sticky top-0 z-30">
    <div class="max-w-6xl mx-auto px-4 py-3 flex justify-between items-center">
        <a href="{{ route('home') }}" class="font-bold text-lg shrink-0">🎓 PMB Online</a>

        <div class="hidden lg:flex gap-5 items-center text-sm">
            <a href="{{ route('home') }}" class="hover:underline">Beranda</a>
            <a href="{{ route('public.tentang-kampus') }}" class="hover:underline">Tentang Kampus</a>
            <a href="{{ route('public.fakultas') }}" class="hover:underline">Fakultas</a>
            <a href="{{ route('public.programs') }}" class="hover:underline">Program Studi</a>
            <a href="{{ route('public.informasi-pmb') }}" class="hover:underline">Informasi PMB</a>
            <a href="{{ route('public.berita') }}" class="hover:underline">Berita</a>

            <div class="relative group">
                <button class="hover:underline flex items-center gap-1">
                    Bantuan
                    <span class="text-xs">▾</span>
                </button>
                <div class="invisible opacity-0 group-hover:visible group-hover:opacity-100 absolute left-0 top-full mt-2 w-44 bg-white text-gray-800 rounded-lg shadow-lg py-1 transition-all">
                    <a href="{{ route('public.bantuan.panduan') }}" class="block px-4 py-2 hover:bg-blue-50">Panduan</a>
                    <a href="{{ route('public.bantuan.faq') }}" class="block px-4 py-2 hover:bg-blue-50">FAQ</a>
                    <a href="{{ route('public.bantuan.kontak') }}" class="block px-4 py-2 hover:bg-blue-50">Kontak</a>
                </div>
            </div>
        </div>

        <div class="flex gap-3 items-center text-sm">
            @auth
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="hover:underline">Admin</a>
                @else
                    <a href="{{ route('pendaftar.dashboard') }}" class="hover:underline">Dashboard Saya</a>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="bg-white text-blue-700 px-3 py-1 rounded text-sm font-medium hover:bg-blue-50">Keluar</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="hover:underline">Masuk</a>
                <a href="{{ route('register') }}" class="bg-white text-blue-700 px-3 py-1 rounded font-medium hover:bg-blue-50">Daftar</a>
            @endauth
        </div>
    </div>

    {{-- Navigasi mobile --}}
    <div class="lg:hidden bg-blue-800 px-4 py-2 text-sm">
        <div class="flex flex-wrap gap-x-4 gap-y-1">
            <a href="{{ route('home') }}" class="hover:underline">Beranda</a>
            <a href="{{ route('public.tentang-kampus') }}" class="hover:underline">Tentang Kampus</a>
            <a href="{{ route('public.fakultas') }}" class="hover:underline">Fakultas</a>
            <a href="{{ route('public.programs') }}" class="hover:underline">Program Studi</a>
            <a href="{{ route('public.informasi-pmb') }}" class="hover:underline">Informasi PMB</a>
            <a href="{{ route('public.berita') }}" class="hover:underline">Berita</a>
            <a href="{{ route('public.bantuan.panduan') }}" class="hover:underline">Panduan</a>
            <a href="{{ route('public.bantuan.faq') }}" class="hover:underline">FAQ</a>
            <a href="{{ route('public.bantuan.kontak') }}" class="hover:underline">Kontak</a>
        </div>
    </div>
</nav>

<main class="max-w-6xl mx-auto px-4 py-8">
    @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-800 px-4 py-3 rounded">
            {{ session('error') }}
        </div>
    @endif
    @if(session('info'))
        <div class="mb-4 bg-blue-100 border border-blue-400 text-blue-800 px-4 py-3 rounded">
            {{ session('info') }}
        </div>
    @endif

    @yield('content')
</main>

<footer class="mt-16 bg-gray-800 text-gray-400 text-sm">
    <div class="max-w-6xl mx-auto px-4 py-10 grid grid-cols-1 md:grid-cols-3 gap-8">
        <div>
            <div class="text-white font-bold text-lg mb-2">🎓 PMB Online</div>
            <p class="leading-relaxed">Portal resmi Penerimaan Mahasiswa Baru PNM Jakarta.</p>
        </div>
        <div>
            <div class="text-white font-semibold mb-2">Menu</div>
            <ul class="space-y-1">
                <li><a href="{{ route('public.tentang-kampus') }}" class="hover:text-white">Tentang Kampus</a></li>
                <li><a href="{{ route('public.fakultas') }}" class="hover:text-white">Fakultas</a></li>
                <li><a href="{{ route('public.programs') }}" class="hover:text-white">Program Studi</a></li>
                <li><a href="{{ route('public.informasi-pmb') }}" class="hover:text-white">Informasi PMB</a></li>
                <li><a href="{{ route('public.berita') }}" class="hover:text-white">Berita</a></li>
            </ul>
        </div>
        <div>
            <div class="text-white font-semibold mb-2">Bantuan</div>
            <ul class="space-y-1">
                <li><a href="{{ route('public.bantuan.panduan') }}" class="hover:text-white">Panduan Pendaftaran</a></li>
                <li><a href="{{ route('public.bantuan.faq') }}" class="hover:text-white">Pertanyaan Umum</a></li>
                <li><a href="{{ route('public.bantuan.kontak') }}" class="hover:text-white">Hubungi Kami</a></li>
            </ul>
        </div>
    </div>
    <div class="border-t border-gray-700">
        <div class="max-w-6xl mx-auto px-4 py-4 text-center">
            <p>© {{ date('Y') }} PMB Online. Sistem Penerimaan Mahasiswa Baru.</p>
        </div>
    </div>
</footer>

</body>
</html>
