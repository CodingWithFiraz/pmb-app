<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PMB') — Penerimaan Mahasiswa Baru</title>
    <meta name="description" content="@yield('meta_description', 'Portal resmi Penerimaan Mahasiswa Baru secara daring, transparan, dan terintegrasi. Lihat jalur masuk, fakultas, program studi, jadwal, dan biaya UKT.')">
    <meta name="theme-color" content="#2563EB">
    <meta property="og:type" content="website">
    <meta property="og:title" content="@yield('title', 'PMB') — Penerimaan Mahasiswa Baru">
    <meta property="og:description" content="@yield('meta_description', 'Portal resmi Penerimaan Mahasiswa Baru secara daring, transparan, dan terintegrasi.')">
    <meta name="twitter:card" content="summary_large_image">
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'%3E%3Crect width='32' height='32' rx='8' fill='%232563EB'/%3E%3Ctext x='16' y='22' text-anchor='middle' font-family='sans-serif' font-weight='700' font-size='16' fill='white'%3EP%3C/text%3E%3C/svg%3E">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Sora:wght@100..800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="@yield('body-class', '!bg-[#F8F6F9]') text-[#14141F] font-sans min-h-screen flex flex-col antialiased">

{{-- Navigation Bar --}}
<header class="w-full bg-white border-b border-[#DDE1F5] sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 h-16 flex items-center justify-between gap-4">
        {{-- Skip link for keyboard navigation --}}
        <a href="#main-content"
           class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:left-4 focus:z-50 focus:rounded-xl focus:bg-[#2563EB] focus:px-4 focus:py-2.5 focus:text-sm focus:font-semibold focus:text-white focus:shadow-lg focus:outline-none">
            Lewati ke konten utama
        </a>

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
                    <button type="submit" class="bg-black text-white hover:bg-[#14141F]/85 inline-flex items-center justify-center h-10 px-4 rounded-xl text-[15px] font-semibold transition duration-150">
                        Keluar
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="text-[15px] font-medium text-black hover:text-[#2563EB] transition">Masuk</a>
                <a href="{{ route('register') }}" class="bg-black text-white hover:bg-[#14141F]/85 inline-flex items-center justify-center h-10 px-4 rounded-xl text-[15px] font-semibold transition duration-150">
                    Daftar
                </a>
            @endauth
        </div>

        {{-- Mobile Hamburger --}}
        <div class="lg:hidden flex items-center">
            <button id="mobile-menu-btn" type="button" aria-label="Buka menu navigasi" aria-expanded="false" aria-controls="mobile-menu" class="inline-flex items-center justify-center h-11 w-11 text-black hover:bg-[#F2F5FF] rounded-xl focus:outline-none focus-visible:ring-2 focus-visible:ring-[#2563EB]">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>
        </div>
    </div>

    {{-- Mobile Menu Dropdown --}}
    <div id="mobile-menu" class="hidden lg:hidden px-6 pb-4 bg-white border-t border-[#DDE1F5]">
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
                    <button type="submit" class="w-full h-11 text-center bg-black hover:bg-[#14141F]/85 text-white rounded-xl font-semibold">
                        Keluar
                    </button>
                </form>
            @else
                <div class="pt-3 flex gap-2">
                    <a href="{{ route('login') }}" class="flex-1 inline-flex items-center justify-center h-11 border border-[#DDE1F5] rounded-xl text-black font-medium">Masuk</a>
                    <a href="{{ route('register') }}" class="flex-1 inline-flex items-center justify-center h-11 bg-black hover:bg-[#14141F]/85 text-white rounded-xl font-semibold">Daftar</a>
                </div>
            @endauth
        </div>
    </div>
</header>

<main id="main-content" class="flex-1">
    {{-- Flash Notifications --}}
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 mt-4">
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-3 rounded-xl text-sm font-medium">
                {{ session('success') }}
            </div>
        </div>
    @endif
    @if(session('error'))
        <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 mt-4">
            <div class="bg-red-50 border border-red-200 text-red-800 px-5 py-3 rounded-xl text-sm font-medium">
                {{ session('error') }}
            </div>
        </div>
    @endif
    @if(session('info'))
        <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 mt-4">
            <div class="bg-[#F5EFFB] border border-[#E6D8FA] text-[#581C87] px-5 py-3 rounded-xl text-sm font-medium">
                {{ session('info') }}
            </div>
        </div>
    @endif

    {{-- Full-width content slot (e.g. Homepage Hero) --}}
    @hasSection('full-width-content')
        @yield('full-width-content')
    @endif

    {{-- Standard content container for all sub-pages --}}
    @hasSection('content')
        <div class="max-w-6xl mx-auto px-6 sm:px-8 lg:px-12 py-8">
            @yield('content')
        </div>
    @endif
</main>

{{-- Footer --}}
<footer class="mt-auto border-t border-[#DDE1F5] bg-white py-10 text-sm text-[#3E465C]">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
        <div class="space-y-1">
            <a href="{{ route('home') }}" class="inline-flex min-h-11 sm:min-h-0 items-center font-heading text-lg font-bold tracking-tight text-[#14141F]">
                PMB Online
            </a>
            <p class="text-sm text-[#3E465C]">&copy; {{ date('Y') }} Sistem Penerimaan Mahasiswa Baru.</p>
        </div>

        <nav aria-label="Tautan footer" class="flex flex-wrap gap-x-6 gap-y-1 text-sm font-medium text-[#3E465C]">
            <a href="{{ route('public.fakultas') }}" class="inline-flex min-h-11 sm:min-h-0 items-center hover:text-[#2563EB] transition">Fakultas</a>
            <a href="{{ route('public.berita') }}" class="inline-flex min-h-11 sm:min-h-0 items-center hover:text-[#2563EB] transition">Berita PMB</a>
            <a href="{{ route('public.tentang-kampus') }}" class="inline-flex min-h-11 sm:min-h-0 items-center hover:text-[#2563EB] transition">Tentang Kampus</a>
            <a href="{{ route('public.bantuan.panduan') }}" class="inline-flex min-h-11 sm:min-h-0 items-center hover:text-[#2563EB] transition">Panduan</a>
            <a href="{{ route('public.bantuan.faq') }}" class="inline-flex min-h-11 sm:min-h-0 items-center hover:text-[#2563EB] transition">FAQ</a>
            <a href="{{ route('public.bantuan.kontak') }}" class="inline-flex min-h-11 sm:min-h-0 items-center hover:text-[#2563EB] transition">Hubungi Kami</a>
        </nav>
    </div>
</footer>

<script>
    document.getElementById('mobile-menu-btn')?.addEventListener('click', function() {
        const menu = document.getElementById('mobile-menu');
        if (!menu) return;
        const isHidden = menu.classList.toggle('hidden');
        this.setAttribute('aria-expanded', String(!isHidden));
    });
</script>

@stack('scripts')

</body>
</html>