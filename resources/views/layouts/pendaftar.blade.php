<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') — PMB Pendaftar</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-800">

<div class="flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <aside class="w-64 bg-blue-800 text-white hidden md:flex flex-col">
        <div class="p-6 flex-shrink-0">
            <a href="{{ route('home') }}" class="font-bold text-lg mb-6 block">🎓 PMB Online</a>
            <nav class="flex-1 space-y-2">
                <a href="{{ route('pendaftar.dashboard') }}"
                    class="block px-4 py-3 rounded-lg font-medium {{ request()->routeIs('pendaftar.dashboard') ? 'bg-blue-700' : 'hover:bg-blue-700/50' }}">
                    Dashboard
                </a>
                <a href="{{ route('pendaftar.profil') }}"
                    class="block px-4 py-3 rounded-lg font-medium {{ request()->routeIs('pendaftar.profil*') ? 'bg-blue-700' : 'hover:bg-blue-700/50' }}">
                    Profil
                </a>
                <a href="{{ route('pendaftar.pilihan') }}"
                    class="block px-4 py-3 rounded-lg font-medium {{ request()->routeIs('pendaftar.pilihan*') ? 'bg-blue-700' : 'hover:bg-blue-700/50' }}">
                    Pilihan Jurusan
                </a>
                <a href="{{ route('pendaftar.dokumen') }}"
                    class="block px-4 py-3 rounded-lg font-medium {{ request()->routeIs('pendaftar.dokumen*') ? 'bg-blue-700' : 'hover:bg-blue-700/50' }}">
                    Dokumen
                </a>
                <a href="{{ route('pendaftar.pembayaran') }}"
                    class="block px-4 py-3 rounded-lg font-medium {{ request()->routeIs('pendaftar.pembayaran*') ? 'bg-blue-700' : 'hover:bg-blue-700/50' }}">
                    Pembayaran
                </a>
                <a href="{{ route('pendaftar.pengumuman') }}"
                    class="block px-4 py-3 rounded-lg font-medium {{ request()->routeIs('pendaftar.pengumuman') ? 'bg-blue-700' : 'hover:bg-blue-700/50' }}">
                    Pengumuman
                </a>
                <a href="{{ route('pendaftar.kartu') }}"
                    class="block px-4 py-3 rounded-lg font-medium {{ request()->routeIs('pendaftar.kartu') ? 'bg-blue-700' : 'hover:bg-blue-700/50' }}">
                    Kartu Peserta
                </a>
            </nav>
        </div>
        <div class="p-4 border-t border-blue-700 flex-shrink-0">
            <form method="POST" action="{{ route('logout') }}" class="block">
                @csrf
                <button class="w-full px-4 py-3 text-left bg-transparent text-white hover:bg-blue-700/50 rounded-lg font-medium">Keluar</button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto bg-gray-50">
        <!-- Topbar -->
        <header class="bg-white border-b border-gray-200 px-6 py-4 flex justify-between items-center">
            <div>
                <h1 class="text-lg font-bold text-gray-800">@yield('title', 'Dashboard')</h1>
            </div>
            <div class="text-sm text-gray-600">
                Selamat datang, {{ auth()->user()->name }}
            </div>
        </header>

        <!-- Flash messages -->
        <div class="px-6 py-4">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-800 px-4 py-3 rounded">{{ session('error') }}</div>
            @endif
            @if(session('info'))
                <div class="mb-4 bg-blue-100 border border-blue-400 text-blue-800 px-4 py-3 rounded">{{ session('info') }}</div>
            @endif

            @yield('content')
        </div>
    </main>
</div>

</body>
</html>
