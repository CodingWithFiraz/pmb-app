<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') — PMB Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-800">

<div class="flex h-screen overflow-hidden">
    <!-- Topbar -->
    <header class="bg-blue-800 text-white fixed w-full z-20 hidden md:block">
        <div class="max-w-6xl mx-auto px-4 py-3 flex justify-between items-center">
            <a href="{{ route('admin.dashboard') }}" class="font-bold text-lg">🎓 PMB Admin</a>
            <div class="flex gap-4 items-center text-sm">
                <a href="{{ route('admin.pendaftaran.index') }}" class="hover:underline text-blue-100">Pendaftar</a>
                <a href="{{ route('admin.pendaftaran.export') }}" class="hover:underline text-blue-100">Export</a>
                <span class="text-white font-medium">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button class="bg-white text-blue-800 px-3 py-1 rounded text-sm font-medium hover:bg-blue-50">Keluar</button>
                </form>
            </div>
        </div>
    </header>

    <div class="flex flex-row h-full pt-16 overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-64 bg-blue-900 text-white fixed md:relative h-full md:h-auto overflow-y-auto flex-shrink-0">
            <div class="p-6 flex-shrink-0">
                <a href="{{ route('home') }}" class="font-bold text-lg mb-6 block text-blue-100">🎓 PMB Online</a>
            </div>
            <nav class="flex-1 space-y-1 p-4">
                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-3 rounded-lg bg-blue-800 font-medium text-white">Dashboard</a>
                <a href="{{ route('admin.pendaftaran.index') }}" class="block px-4 py-3 rounded-lg hover:bg-blue-800/50 text-blue-100">Daftar Pendaftar</a>

                <p class="px-4 pt-4 pb-1 text-xs uppercase tracking-wider text-blue-300">Master Data</p>
                <a href="{{ route('admin.periode.index') }}" class="block px-4 py-3 rounded-lg hover:bg-blue-800/50 text-blue-100">Periode Pendaftaran</a>
                <a href="{{ route('admin.fakultas.index') }}" class="block px-4 py-3 rounded-lg hover:bg-blue-800/50 text-blue-100">Fakultas</a>
                <a href="{{ route('admin.program-studi.index') }}" class="block px-4 py-3 rounded-lg hover:bg-blue-800/50 text-blue-100">Program Studi</a>
                <a href="{{ route('admin.jalur-masuk.index') }}" class="block px-4 py-3 rounded-lg hover:bg-blue-800/50 text-blue-100">Jalur Masuk</a>
                <a href="{{ route('admin.golongan-ukt.index') }}" class="block px-4 py-3 rounded-lg hover:bg-blue-800/50 text-blue-100">Golongan UKT</a>
                <a href="{{ route('admin.dokumen.index') }}" class="block px-4 py-3 rounded-lg hover:bg-blue-800/50 text-blue-100">Dokumen Persyaratan</a>

                <p class="px-4 pt-4 pb-1 text-xs uppercase tracking-wider text-blue-300">Biaya &amp; Tarif</p>
                <a href="{{ route('admin.tarif-ukt.index') }}" class="block px-4 py-3 rounded-lg hover:bg-blue-800/50 text-blue-100">Tarif UKT</a>
                <a href="{{ route('admin.biaya-pendaftaran.index') }}" class="block px-4 py-3 rounded-lg hover:bg-blue-800/50 text-blue-100">Biaya Pendaftaran</a>

                <p class="px-4 pt-4 pb-1 text-xs uppercase tracking-wider text-blue-300">Konten</p>
                <a href="{{ route('admin.berita.index') }}" class="block px-4 py-3 rounded-lg hover:bg-blue-800/50 text-blue-100">Berita</a>
                <a href="{{ route('admin.faq.index') }}" class="block px-4 py-3 rounded-lg hover:bg-blue-800/50 text-blue-100">Pertanyaan Umum</a>
                <a href="{{ route('admin.pengaturan.index') }}" class="block px-4 py-3 rounded-lg hover:bg-blue-800/50 text-blue-100">Pengaturan Situs</a>

                <p class="px-4 pt-4 pb-1 text-xs uppercase tracking-wider text-blue-300">Pengumuman &amp; Pembayaran</p>
                <a href="{{ route('admin.pengumuman.index') }}" class="block px-4 py-3 rounded-lg hover:bg-blue-800/50 text-blue-100">Pengumuman Kelulusan</a>
                <a href="{{ route('admin.pembayaran.verifikasi.index') }}" class="block px-4 py-3 rounded-lg hover:bg-blue-800/50 text-blue-100">Verifikasi Pembayaran</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto bg-gray-50">
            <!-- Topbar for mobile -->
            <header class="bg-white border-b border-gray-200 px-6 py-4 flex justify-between items-center md:hidden">
                <div>
                    <h1 class="text-lg font-bold text-gray-800">@yield('title', 'Admin')</h1>
                </div>
                <div class="text-sm text-gray-600">
                    {{ auth()->user()->name }}
                </div>
            </header>

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
</div>

</body>
</html>
