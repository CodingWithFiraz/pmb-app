<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') — PMB Pendaftar</title>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'%3E%3Crect width='32' height='32' rx='8' fill='%232563EB'/%3E%3Ctext x='16' y='22' text-anchor='middle' font-family='sans-serif' font-weight='700' font-size='16' fill='white'%3EP%3C/text%3E%3C/svg%3E">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="!bg-[#F8F6F9] text-[#14141F] font-sans antialiased">

@php
    $user          = auth()->user();
    $userName      = $user?->name ?? 'Pendaftar';
    $userEmail     = $user?->email ?? '';
    $userInitials  = collect(explode(' ', trim($userName)))
        ->filter()
        ->take(2)
        ->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))
        ->implode('') ?: 'P';

    $navLinkClass = function (string $pattern): string {
        $active = request()->routeIs($pattern);
        return $active
            ? 'group flex items-center gap-3 rounded-xl bg-[#F5EFFB] px-3 py-2.5 text-[15px] font-semibold text-[#7E22CE] transition-colors'
            : 'group flex items-center gap-3 rounded-xl px-3 py-2.5 text-[15px] font-normal text-[#4E566E] transition-colors hover:bg-[#FAF8FC] hover:text-[#14141F]';
    };
@endphp

<div class="flex min-h-screen w-full">
    {{-- Backdrop mobile sidebar --}}
    <div id="pendaftar-sidebar-backdrop"
         class="fixed inset-0 z-30 hidden bg-[#14141F]/40 backdrop-blur-[2px] md:hidden"
         aria-hidden="true"></div>

    {{-- Left Sidebar --}}
    <aside id="pendaftar-sidebar"
           class="fixed inset-y-0 left-0 z-40 flex w-68 -translate-x-full flex-col border-r border-[#E6E1EC] bg-white transition-transform duration-200 md:sticky md:top-0 md:h-screen md:translate-x-0 shrink-0">
        {{-- Sidebar Header --}}
        <div class="flex items-center justify-between gap-3 px-5 py-5">
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 min-w-0">
                <span class="flex size-8 shrink-0 items-center justify-center rounded-lg bg-gradient-to-r from-[#BF8AFC] to-[#FA7F94] font-heading text-sm font-extrabold text-white">
                    P
                </span>
                <span class="font-heading text-xl font-extrabold tracking-[-0.03em] text-[#14141F] truncate">
                    PNM
                </span>
            </a>

            <button id="pendaftar-sidebar-close" type="button" aria-label="Tutup menu"
                    class="inline-flex size-8 items-center justify-center rounded-lg text-[#4E566E] hover:bg-[#F8F6F9] hover:text-[#14141F] md:hidden">
                <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Navigation --}}
        <nav aria-label="Navigasi Pendaftar" class="flex-1 overflow-y-auto px-4 pb-4 space-y-1">
            <div class="px-3 py-1 text-[13px] font-medium text-[#4E566E]/80">Portal Pendaftar</div>

            <a href="{{ route('pendaftar.dashboard') }}" class="{{ $navLinkClass('pendaftar.dashboard') }}"
               @if(request()->routeIs('pendaftar.dashboard')) aria-current="page" @endif>
                <svg class="size-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                </svg>
                <span class="truncate">Dashboard</span>
            </a>

            <a href="{{ route('pendaftar.profil') }}" class="{{ $navLinkClass('pendaftar.profil*') }}"
               @if(request()->routeIs('pendaftar.profil*')) aria-current="page" @endif>
                <svg class="size-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                </svg>
                <span class="truncate">Profil</span>
            </a>

            <a href="{{ route('pendaftar.pilihan') }}" class="{{ $navLinkClass('pendaftar.pilihan*') }}"
               @if(request()->routeIs('pendaftar.pilihan*')) aria-current="page" @endif>
                <svg class="size-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342" />
                </svg>
                <span class="truncate">Pilihan Jurusan</span>
            </a>

            <a href="{{ route('pendaftar.dokumen') }}" class="{{ $navLinkClass('pendaftar.dokumen*') }}"
               @if(request()->routeIs('pendaftar.dokumen*')) aria-current="page" @endif>
                <svg class="size-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                </svg>
                <span class="truncate">Dokumen</span>
            </a>

            <a href="{{ route('pendaftar.pembayaran') }}" class="{{ $navLinkClass('pendaftar.pembayaran*') }}"
               @if(request()->routeIs('pendaftar.pembayaran*')) aria-current="page" @endif>
                <svg class="size-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" />
                </svg>
                <span class="truncate">Pembayaran</span>
            </a>

            <a href="{{ route('pendaftar.pengumuman') }}" class="{{ $navLinkClass('pendaftar.pengumuman') }}"
               @if(request()->routeIs('pendaftar.pengumuman')) aria-current="page" @endif>
                <svg class="size-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 1 1 0-9h.75c.704 0 1.402-.03 2.09-.09m0 9.18c.253.962.584 1.892.985 2.783.247.55.06 1.21-.463 1.511l-.657.38c-.551.318-1.26.117-1.527-.461a20.845 20.845 0 0 1-1.44-4.282m3.102.069a18.03 18.03 0 0 1-.59-4.59c0-1.586.205-3.124.59-4.59m0 9.18a23.848 23.848 0 0 1 8.835 2.535M10.34 6.66a23.847 23.847 0 0 0 8.835-2.535m0 0A23.74 23.74 0 0 0 18.795 3m.38 1.125a23.91 23.91 0 0 1 1.014 5.395m-1.014 8.855c-.118.38-.245.754-.38 1.125m.38-1.125a23.91 23.91 0 0 0 1.014-5.395m0-3.46c.495.413.811 1.035.811 1.73 0 .695-.316 1.317-.811 1.73m0-3.46a24.347 24.347 0 0 1 0 3.46" />
                </svg>
                <span class="truncate">Pengumuman</span>
            </a>

            <a href="{{ route('pendaftar.kartu') }}" class="{{ $navLinkClass('pendaftar.kartu') }}"
               @if(request()->routeIs('pendaftar.kartu')) aria-current="page" @endif>
                <svg class="size-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Zm6-10.125a1.875 1.875 0 1 1-3.75 0 1.875 1.875 0 0 1 3.75 0Zm1.294 6.336a6.721 6.721 0 0 1-3.17.789 6.721 6.721 0 0 1-3.168-.789 3.376 3.376 0 0 1 6.338 0Z" />
                </svg>
                <span class="truncate">Kartu Peserta</span>
            </a>
        </nav>

        {{-- Sidebar Footer: Account --}}
        <div class="border-t border-[#EFEBF4] px-4 py-3 space-y-2">
            <div class="px-2 text-[12px] font-medium text-[#4E566E]/80">Akun</div>
            <div class="flex items-center gap-2.5 rounded-xl px-2 py-1.5">
                <span class="flex size-9 shrink-0 items-center justify-center rounded-full border border-[#E6E1EC] bg-[#F5EFFB] font-heading text-xs font-bold text-[#7E22CE]" aria-hidden="true">
                    {{ $userInitials }}
                </span>
                <div class="min-w-0 flex-1">
                    <div class="truncate text-sm font-semibold text-[#14141F]">{{ $userName }}</div>
                    @if($userEmail)
                        <div class="truncate text-xs text-[#4E566E]">{{ $userEmail }}</div>
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-2 pt-1">
                <a href="{{ route('home') }}"
                   class="inline-flex h-9 flex-1 items-center justify-center gap-1.5 rounded-lg border border-[#E6E1EC] bg-white px-3 text-xs font-semibold text-[#4E566E] transition hover:border-[#BF8AFC] hover:text-[#14141F]">
                    Beranda
                </a>
                <form method="POST" action="{{ route('logout') }}" class="flex-1">
                    @csrf
                    <button type="submit"
                            class="inline-flex h-9 w-full items-center justify-center gap-1.5 rounded-lg border border-rose-200 bg-rose-50 px-3 text-xs font-semibold text-rose-700 transition hover:bg-rose-100">
                        Keluar
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- Main Content --}}
    <div class="flex min-w-0 flex-1 flex-col">
        {{-- Mobile topbar --}}
        <header class="sticky top-0 z-20 flex items-center justify-between border-b border-[#E6E1EC] bg-white px-4 py-3 md:hidden">
            <div class="flex items-center gap-3">
                <button id="pendaftar-sidebar-open" type="button" aria-label="Buka menu navigasi"
                        class="inline-flex size-10 items-center justify-center rounded-xl border border-[#E6E1EC] text-[#14141F] hover:bg-[#F8F6F9]">
                    <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
                <span class="font-heading text-base font-bold text-[#14141F]">@yield('title', 'Dashboard')</span>
            </div>
            <span class="text-xs font-semibold text-[#4E566E]">{{ $userName }}</span>
        </header>

        <main class="flex-1 px-4 py-6 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-5xl">
                @if(session('success'))
                    <div class="mb-5 rounded-xl border border-[#007A55]/20 bg-[#ECFDF5] px-4 py-3 text-sm font-medium text-[#007A55]">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-5 rounded-xl border border-[#C10007]/20 bg-[#FEF2F2] px-4 py-3 text-sm font-medium text-[#C10007]">
                        {{ session('error') }}
                    </div>
                @endif
                @if(session('info'))
                    <div class="mb-5 rounded-xl border border-[#E6D8FA] bg-[#F5EFFB] px-4 py-3 text-sm font-medium text-[#581C87]">
                        {{ session('info') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
</div>

<script>
    (function () {
        const sidebar  = document.getElementById('pendaftar-sidebar');
        const backdrop = document.getElementById('pendaftar-sidebar-backdrop');
        const openBtn  = document.getElementById('pendaftar-sidebar-open');
        const closeBtn = document.getElementById('pendaftar-sidebar-close');

        function openSidebar() {
            sidebar?.classList.remove('-translate-x-full');
            backdrop?.classList.remove('hidden');
        }
        function closeSidebar() {
            sidebar?.classList.add('-translate-x-full');
            backdrop?.classList.add('hidden');
        }

        openBtn?.addEventListener('click', openSidebar);
        closeBtn?.addEventListener('click', closeSidebar);
        backdrop?.addEventListener('click', closeSidebar);
    })();
</script>

</body>
</html>
