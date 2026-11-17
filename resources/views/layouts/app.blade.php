<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PMB') — Penerimaan Mahasiswa Baru</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Sora:wght@100..800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="@yield('body-class', 'bg-[#F2F5FF]') text-black font-sans min-h-screen flex flex-col antialiased">

{{-- Navigation Bar --}}
<header class="w-full @yield('header-class', 'bg-transparent')">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 py-5 flex items-center justify-between">
        {{-- Logo --}}
        <a href="{{ route('home') }}" class="text-xl sm:text-2xl font-bold @yield('logo-class', 'text-black') tracking-tight hover:opacity-90 transition">
            PMB Online
        </a>

        {{-- Desktop Navigation --}}
        <div class="hidden md:flex items-center gap-3">
            {{-- Nav links pill --}}
            <nav class="bg-white rounded-full border border-gray-200/90 shadow-xs px-5 py-2 flex items-center gap-5 text-sm font-medium text-gray-700">
                <a href="{{ route('home') }}" class="hover:text-black transition">Home</a>
                <a href="{{ route('public.informasi-pmb') }}" class="hover:text-black transition">Informasi</a>
                <a href="{{ route('public.programs') }}" class="hover:text-black transition">Program studi</a>

                {{-- Menu halaman baru sistem (TODO Frontend untuk dirapikan ke dropdown/pill) --}}
                <a href="{{ route('public.fakultas') }}" class="hover:text-black transition inline-flex items-center gap-1">
                    Fakultas
                    <span class="text-[10px] bg-amber-100 text-amber-800 px-1.5 py-0.5 rounded font-semibold">Baru</span>
                </a>
                <a href="{{ route('public.berita') }}" class="hover:text-black transition inline-flex items-center gap-1">
                    Berita
                    <span class="text-[10px] bg-amber-100 text-amber-800 px-1.5 py-0.5 rounded font-semibold">Baru</span>
                </a>
                <a href="{{ route('public.tentang-kampus') }}" class="hover:text-black transition">Tentang</a>

                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="hover:text-black transition">Dashboard</a>
                    @else
                        <a href="{{ route('pendaftar.dashboard') }}" class="hover:text-black transition">Dashboard</a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="hover:text-black transition">Masuk</a>
                @endauth
            </nav>

            {{-- Action Button (Keluar or Daftar) --}}
            @auth
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="@yield('nav-action-btn', 'bg-black text-white hover:bg-gray-800') px-5 py-2 rounded-xl text-sm font-semibold transition">
                        Keluar
                    </button>
                </form>
            @else
                <a href="{{ route('register') }}" class="@yield('nav-action-btn', 'bg-black text-white hover:bg-gray-800') px-5 py-2 rounded-xl text-sm font-semibold transition">
                    Daftar
                </a>
            @endauth
        </div>

        {{-- Mobile Hamburger --}}
        <div class="md:hidden flex items-center">
            <button id="mobile-menu-btn" type="button" class="p-2 @yield('hamburger-class', 'text-gray-700 hover:bg-white') rounded-lg focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>
        </div>
    </div>

    {{-- Mobile Menu Dropdown --}}
    <div id="mobile-menu" class="hidden md:hidden px-6 pb-4">
        <div class="bg-white rounded-2xl border border-gray-200 p-4 space-y-2 shadow-sm text-sm font-medium">
            <a href="{{ route('home') }}" class="block px-3 py-2 rounded-lg text-gray-700 hover:bg-[#F2F5FF]">Home</a>
            <a href="{{ route('public.informasi-pmb') }}" class="block px-3 py-2 rounded-lg text-gray-700 hover:bg-[#F2F5FF]">Informasi</a>
            <a href="{{ route('public.programs') }}" class="block px-3 py-2 rounded-lg text-gray-700 hover:bg-[#F2F5FF]">Program studi</a>
            <a href="{{ route('public.fakultas') }}" class="block px-3 py-2 rounded-lg text-gray-700 hover:bg-[#F2F5FF]">Fakultas <span class="text-[10px] bg-amber-100 text-amber-800 px-1.5 py-0.5 rounded">TODO UI</span></a>
            <a href="{{ route('public.berita') }}" class="block px-3 py-2 rounded-lg text-gray-700 hover:bg-[#F2F5FF]">Berita <span class="text-[10px] bg-amber-100 text-amber-800 px-1.5 py-0.5 rounded">TODO UI</span></a>
            <a href="{{ route('public.tentang-kampus') }}" class="block px-3 py-2 rounded-lg text-gray-700 hover:bg-[#F2F5FF]">Tentang Kampus <span class="text-[10px] bg-amber-100 text-amber-800 px-1.5 py-0.5 rounded">TODO UI</span></a>
            <a href="{{ route('public.bantuan.panduan') }}" class="block px-3 py-2 rounded-lg text-gray-700 hover:bg-[#F2F5FF]">Panduan &amp; Bantuan <span class="text-[10px] bg-amber-100 text-amber-800 px-1.5 py-0.5 rounded">TODO UI</span></a>
            @auth
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded-lg text-gray-700 hover:bg-[#F2F5FF]">Dashboard</a>
                @else
                    <a href="{{ route('pendaftar.dashboard') }}" class="block px-3 py-2 rounded-lg text-gray-700 hover:bg-[#F2F5FF]">Dashboard</a>
                @endif
                <form method="POST" action="{{ route('logout') }}" class="pt-2">
                    @csrf
                    <button type="submit" class="w-full text-center bg-black text-white py-2 rounded-xl font-semibold">
                        Keluar
                    </button>
                </form>
            @else
                <div class="pt-2 flex gap-2">
                    <a href="{{ route('login') }}" class="flex-1 text-center border border-gray-300 py-2 rounded-xl text-gray-700 font-medium">Masuk</a>
                    <a href="{{ route('register') }}" class="flex-1 text-center bg-black text-white py-2 rounded-xl font-semibold">Daftar</a>
                </div>
            @endauth
        </div>
    </div>
</header>

<main class="flex-1">
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
            <div class="bg-[#F2F5FF] border border-[#5F4AF7]/30 text-[#5F4AF7] px-5 py-3 rounded-xl text-sm font-medium">
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
<footer class="mt-auto border-t border-[#E5EAFC]/60 bg-transparent py-8 text-center text-sm text-[#818CA2]">
    <div class="max-w-7xl mx-auto px-6 space-y-3">
        {{-- Tautan Halaman Bantuan & Informasi Baru --}}
        <div class="flex flex-wrap justify-center gap-4 text-xs font-medium text-gray-600">
            <a href="{{ route('public.fakultas') }}" class="hover:text-[#5F4AF7]">Fakultas</a>
            <span>&middot;</span>
            <a href="{{ route('public.berita') }}" class="hover:text-[#5F4AF7]">Berita PMB</a>
            <span>&middot;</span>
            <a href="{{ route('public.tentang-kampus') }}" class="hover:text-[#5F4AF7]">Tentang Kampus</a>
            <span>&middot;</span>
            <a href="{{ route('public.bantuan.panduan') }}" class="hover:text-[#5F4AF7]">Panduan Pendaftaran</a>
            <span>&middot;</span>
            <a href="{{ route('public.bantuan.faq') }}" class="hover:text-[#5F4AF7]">FAQ</a>
            <span>&middot;</span>
            <a href="{{ route('public.bantuan.kontak') }}" class="hover:text-[#5F4AF7]">Hubungi Kami</a>
        </div>
        <p>© {{ date('Y') }} PMB Online. Sistem Penerimaan Mahasiswa Baru.</p>
    </div>
</footer>

<script>
    document.getElementById('mobile-menu-btn')?.addEventListener('click', function() {
        document.getElementById('mobile-menu')?.classList.toggle('hidden');
    });
</script>

@stack('scripts')

</body>
</html>