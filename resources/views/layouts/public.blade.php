<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PMB') — Penerimaan Mahasiswa Baru</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-800">

<header class="w-full bg-white border-b border-[#DDE1F5] sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 h-16 flex items-center justify-between gap-4">
        {{-- Logo --}}
        <a href="{{ route('home') }}" class="inline-flex min-h-11 lg:min-h-0 items-center font-heading text-xl sm:text-2xl font-bold text-black tracking-tight hover:opacity-90 transition shrink-0">
            PMB Online
        </a>

        {{-- Center Desktop Navigation --}}
        <nav aria-label="Navigasi utama" class="hidden lg:flex items-center gap-6 text-[15px] font-medium text-black">
            <a href="{{ route('home') }}" class="text-black hover:text-[#2563EB] transition">Beranda</a>
            <a href="{{ route('public.tentang-kampus') }}" class="text-black hover:text-[#2563EB] transition">Tentang Kampus</a>
            <a href="{{ route('public.fakultas') }}" class="text-black hover:text-[#2563EB] transition">Fakultas</a>
            <a href="{{ route('public.informasi-pmb') }}" class="text-black hover:text-[#2563EB] transition">Informasi PMB</a>
            <a href="{{ route('public.berita') }}" class="text-black hover:text-[#2563EB] transition">Berita</a>

            <div class="relative group">
                <button type="button" class="inline-flex items-center gap-1 text-[15px] font-medium text-black hover:text-[#2563EB] transition cursor-pointer">
                    <span>Bantuan</span>
                    <span class="text-xs leading-none">▾</span>
                </button>
                <div class="invisible opacity-0 group-hover:visible group-hover:opacity-100 group-focus-within:visible group-focus-within:opacity-100 absolute left-0 top-full pt-2 w-48 z-50 transition-all duration-150">
                    <div class="bg-white text-black rounded-xl border border-[#DDE1F5] shadow-lg py-1.5 text-[15px] font-medium">
                        <a href="{{ route('public.bantuan.panduan') }}" class="block px-4 py-2 text-black hover:bg-[#F2F5FF] hover:text-[#2563EB] transition">Panduan</a>
                        <a href="{{ route('public.bantuan.faq') }}" class="block px-4 py-2 text-black hover:bg-[#F2F5FF] hover:text-[#2563EB] transition">FAQ</a>
                        <a href="{{ route('public.bantuan.kontak') }}" class="block px-4 py-2 text-black hover:bg-[#F2F5FF] hover:text-[#2563EB] transition">Kontak</a>
                    </div>
                </div>
            </div>
        </nav>

        {{-- Right Actions (Masuk & Daftar / Dashboard & Keluar) --}}
        <div class="hidden lg:flex items-center gap-4 shrink-0 text-[15px]">
            @auth
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="text-[15px] font-medium text-black hover:text-[#2563EB] transition">Admin</a>
                @else
                    <a href="{{ route('pendaftar.dashboard') }}" class="text-[15px] font-medium text-black hover:text-[#2563EB] transition">Dashboard Saya</a>
                @endif
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="bg-[#2563EB] text-white hover:bg-[#1D4ED8] inline-flex items-center justify-center h-10 px-4 rounded-xl text-[15px] font-semibold transition duration-150">
                        Keluar
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="text-[15px] font-medium text-black hover:text-[#2563EB] transition">Masuk</a>
                <a href="{{ route('register') }}" class="bg-[#2563EB] text-white hover:bg-[#1D4ED8] inline-flex items-center justify-center h-10 px-4 rounded-xl text-[15px] font-semibold transition duration-150">
                    Daftar
                </a>
            @endauth
        </div>

        {{-- Mobile Hamburger --}}
        <div class="lg:hidden flex items-center">
            <button id="public-mobile-menu-btn" type="button" aria-label="Buka menu navigasi" aria-expanded="false" aria-controls="public-mobile-menu" class="inline-flex items-center justify-center h-11 w-11 text-black hover:bg-[#F2F5FF] rounded-xl focus:outline-none focus-visible:ring-2 focus-visible:ring-[#2563EB]">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>
        </div>
    </div>

    {{-- Navigasi mobile --}}
    <div id="public-mobile-menu" class="hidden lg:hidden px-6 pb-4 bg-white border-t border-[#DDE1F5]">
        <div class="pt-3 space-y-1 text-[15px] font-medium text-black">
            <a href="{{ route('home') }}" class="block px-3 py-2.5 rounded-xl text-black hover:bg-[#F2F5FF]">Beranda</a>
            <a href="{{ route('public.tentang-kampus') }}" class="block px-3 py-2.5 rounded-xl text-black hover:bg-[#F2F5FF]">Tentang Kampus</a>
            <a href="{{ route('public.fakultas') }}" class="block px-3 py-2.5 rounded-xl text-black hover:bg-[#F2F5FF]">Fakultas</a>
            <a href="{{ route('public.informasi-pmb') }}" class="block px-3 py-2.5 rounded-xl text-black hover:bg-[#F2F5FF]">Informasi PMB</a>
            <a href="{{ route('public.berita') }}" class="block px-3 py-2.5 rounded-xl text-black hover:bg-[#F2F5FF]">Berita</a>
            <div class="pt-1 pb-1">
                <div class="px-3 py-1.5 text-xs font-semibold uppercase tracking-wider text-[#3E465C]">Bantuan</div>
                <a href="{{ route('public.bantuan.panduan') }}" class="block pl-6 pr-3 py-2 rounded-xl text-black hover:bg-[#F2F5FF]">Panduan</a>
                <a href="{{ route('public.bantuan.faq') }}" class="block pl-6 pr-3 py-2 rounded-xl text-black hover:bg-[#F2F5FF]">FAQ</a>
                <a href="{{ route('public.bantuan.kontak') }}" class="block pl-6 pr-3 py-2 rounded-xl text-black hover:bg-[#F2F5FF]">Kontak</a>
            </div>
            @auth
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2.5 rounded-xl text-black hover:bg-[#F2F5FF]">Admin</a>
                @else
                    <a href="{{ route('pendaftar.dashboard') }}" class="block px-3 py-2.5 rounded-xl text-black hover:bg-[#F2F5FF]">Dashboard Saya</a>
                @endif
                <form method="POST" action="{{ route('logout') }}" class="pt-2">
                    @csrf
                    <button type="submit" class="w-full h-11 text-center bg-[#2563EB] text-white rounded-xl font-semibold">
                        Keluar
                    </button>
                </form>
            @else
                <div class="pt-3 flex gap-2">
                    <a href="{{ route('login') }}" class="flex-1 inline-flex items-center justify-center h-11 border border-[#DDE1F5] rounded-xl text-black font-medium">Masuk</a>
                    <a href="{{ route('register') }}" class="flex-1 inline-flex items-center justify-center h-11 bg-[#2563EB] text-white rounded-xl font-semibold">Daftar</a>
                </div>
            @endauth
        </div>
    </div>
</header>
<script>
    document.getElementById('public-mobile-menu-btn')?.addEventListener('click', function() {
        const menu = document.getElementById('public-mobile-menu');
        if (!menu) return;
        const isHidden = menu.classList.toggle('hidden');
        this.setAttribute('aria-expanded', String(!isHidden));
    });
</script>

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
