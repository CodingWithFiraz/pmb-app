<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PMB') — Penerimaan Mahasiswa Baru</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-800">

<nav class="bg-blue-700 text-white shadow">
    <div class="max-w-6xl mx-auto px-4 py-3 flex justify-between items-center">
        <a href="{{ route('home') }}" class="font-bold text-lg">🎓 PMB Online</a>
        <div class="flex gap-4 items-center text-sm">
            <a href="{{ route('public.info') }}" class="hover:underline">Informasi</a>
            <a href="{{ route('public.programs') }}" class="hover:underline">Program Studi</a>
            @auth
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="hover:underline">Admin</a>
                @else
                    <a href="{{ route('applicant.dashboard') }}" class="hover:underline">Dashboard Saya</a>
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

<footer class="mt-16 bg-gray-800 text-gray-400 text-sm text-center py-6">
    <p>© {{ date('Y') }} PMB Online. Sistem Penerimaan Mahasiswa Baru.</p>
</footer>

</body>
</html>