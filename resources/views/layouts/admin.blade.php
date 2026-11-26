<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') — PMB Admin</title>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'%3E%3Crect width='32' height='32' rx='8' fill='%232563EB'/%3E%3Ctext x='16' y='22' text-anchor='middle' font-family='sans-serif' font-weight='700' font-size='16' fill='white'%3EP%3C/text%3E%3C/svg%3E">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="!bg-[#F8F6F9] text-[#14141F] font-sans antialiased">

@php
    $adminUser     = auth()->user();
    $adminName     = $adminUser?->name ?? 'Admin';
    $adminEmail    = $adminUser?->email ?? '';
    $adminInitials = collect(explode(' ', trim($adminName)))
        ->filter()
        ->take(2)
        ->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))
        ->implode('') ?: 'A';

    $navLinkClass = function (string $pattern): string {
        $active = request()->routeIs($pattern);
        return $active
            ? 'group flex items-center gap-3 rounded-xl bg-[#F5EFFB] px-3 py-2.5 text-[15px] font-semibold text-[#7E22CE] transition-colors'
            : 'group flex items-center gap-3 rounded-xl px-3 py-2.5 text-[15px] font-normal text-[#4E566E] transition-colors hover:bg-[#FAF8FC] hover:text-[#14141F]';
    };
@endphp

<div class="flex min-h-screen w-full">
    {{-- Backdrop mobile sidebar --}}
    <div id="admin-sidebar-backdrop"
         class="fixed inset-0 z-30 hidden bg-[#14141F]/40 backdrop-blur-[2px] md:hidden"
         aria-hidden="true"></div>

    {{-- Left Sidebar (Gaya Medesk Dashboard — Watermelon UI) --}}
    <aside id="admin-sidebar"
           class="fixed inset-y-0 left-0 z-40 flex w-68 -translate-x-full flex-col border-r border-[#E6E1EC] bg-white transition-transform duration-200 md:sticky md:top-0 md:h-screen md:translate-x-0 shrink-0">
        {{-- Sidebar Header --}}
        <div class="flex items-center justify-between gap-3 px-5 py-5">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 min-w-0">
                <span class="flex size-8 shrink-0 items-center justify-center rounded-lg bg-gradient-to-r from-[#BF8AFC] to-[#FA7F94] font-heading text-sm font-extrabold text-white">
                    P
                </span>
                <span class="font-heading text-lg font-bold tracking-tight text-[#14141F] truncate">
                    PMB Admin
                </span>
            </a>

            {{-- Close button (mobile) --}}
            <button id="admin-sidebar-close" type="button" aria-label="Tutup menu"
                    class="inline-flex size-8 items-center justify-center rounded-lg text-[#4E566E] hover:bg-[#F8F6F9] hover:text-[#14141F] md:hidden">
                <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Sidebar Navigation (Scrollable, grouped ala Medesk) --}}
        <nav aria-label="Navigasi Admin" class="flex-1 overflow-y-auto px-4 pb-4 space-y-5">
            {{-- Group: Utama --}}
            <div class="space-y-1">
                <div class="px-3 py-1 text-[13px] font-medium text-[#4E566E]/80">Utama</div>

                <a href="{{ route('admin.dashboard') }}" class="{{ $navLinkClass('admin.dashboard') }}"
                   @if(request()->routeIs('admin.dashboard')) aria-current="page" @endif>
                    <svg class="size-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                    </svg>
                    <span class="truncate">Dashboard</span>
                </a>

                <a href="{{ route('admin.pendaftaran.index') }}" class="{{ $navLinkClass('admin.pendaftaran.*') }}"
                   @if(request()->routeIs('admin.pendaftaran.*')) aria-current="page" @endif>
                    <svg class="size-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                    </svg>
                    <span class="truncate">Daftar Pendaftar</span>
                </a>
            </div>

            {{-- Group: Master Data (Collapsible ala Medesk) --}}
            <details open class="group/section space-y-1">
                <summary class="flex cursor-pointer list-none items-center justify-between px-3 py-1 text-[13px] font-medium text-[#4E566E]/80 hover:text-[#14141F] [&::-webkit-details-marker]:hidden">
                    <span>Master Data</span>
                    <svg class="size-4 transition-transform duration-200 group-open/section:rotate-90" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                    </svg>
                </summary>

                <div class="space-y-0.5 pt-1">
                    <a href="{{ route('admin.periode.index') }}" class="{{ $navLinkClass('admin.periode.*') }}">
                        <svg class="size-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                        </svg>
                        <span class="truncate">Periode Pendaftaran</span>
                    </a>

                    <a href="{{ route('admin.fakultas.index') }}" class="{{ $navLinkClass('admin.fakultas.*') }}">
                        <svg class="size-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0 0 12 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75Z" />
                        </svg>
                        <span class="truncate">Fakultas</span>
                    </a>

                    <a href="{{ route('admin.program-studi.index') }}" class="{{ $navLinkClass('admin.program-studi.*') }}">
                        <svg class="size-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342" />
                        </svg>
                        <span class="truncate">Program Studi</span>
                    </a>

                    <a href="{{ route('admin.jalur-masuk.index') }}" class="{{ $navLinkClass('admin.jalur-masuk.*') }}">
                        <svg class="size-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
                        </svg>
                        <span class="truncate">Jalur Masuk</span>
                    </a>

                    <a href="{{ route('admin.golongan-ukt.index') }}" class="{{ $navLinkClass('admin.golongan-ukt.*') }}">
                        <svg class="size-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12" />
                        </svg>
                        <span class="truncate">Golongan UKT</span>
                    </a>

                    <a href="{{ route('admin.dokumen.index') }}" class="{{ $navLinkClass('admin.dokumen.*') }}">
                        <svg class="size-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                        </svg>
                        <span class="truncate">Dokumen Persyaratan</span>
                    </a>
                </div>
            </details>

            {{-- Group: Biaya & Tarif --}}
            <details open class="group/section space-y-1">
                <summary class="flex cursor-pointer list-none items-center justify-between px-3 py-1 text-[13px] font-medium text-[#4E566E]/80 hover:text-[#14141F] [&::-webkit-details-marker]:hidden">
                    <span>Biaya &amp; Tarif</span>
                    <svg class="size-4 transition-transform duration-200 group-open/section:rotate-90" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                    </svg>
                </summary>

                <div class="space-y-0.5 pt-1">
                    <a href="{{ route('admin.tarif-ukt.index') }}" class="{{ $navLinkClass('admin.tarif-ukt.*') }}">
                        <svg class="size-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
                        </svg>
                        <span class="truncate">Tarif UKT</span>
                    </a>

                    <a href="{{ route('admin.biaya-pendaftaran.index') }}" class="{{ $navLinkClass('admin.biaya-pendaftaran.*') }}">
                        <svg class="size-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 14.25l6-6m4.5-3.493V21.75l-3.75-1.5-3.75 1.5-3.75-1.5-3.75 1.5V4.757c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0 1 11.186 0c1.1.128 1.907 1.077 1.907 2.185Z" />
                        </svg>
                        <span class="truncate">Biaya Pendaftaran</span>
                    </a>
                </div>
            </details>

            {{-- Group: Konten --}}
            <details open class="group/section space-y-1">
                <summary class="flex cursor-pointer list-none items-center justify-between px-3 py-1 text-[13px] font-medium text-[#4E566E]/80 hover:text-[#14141F] [&::-webkit-details-marker]:hidden">
                    <span>Konten</span>
                    <svg class="size-4 transition-transform duration-200 group-open/section:rotate-90" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                    </svg>
                </summary>

                <div class="space-y-0.5 pt-1">
                    <a href="{{ route('admin.berita.index') }}" class="{{ $navLinkClass('admin.berita.*') }}">
                        <svg class="size-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 0 1-2.25 2.25M16.5 7.5V18a2.25 2.25 0 0 0 2.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 0 0 2.25 2.25h13.5M6 7.5h3v3H6v-3Z" />
                        </svg>
                        <span class="truncate">Berita</span>
                    </a>

                    <a href="{{ route('admin.faq.index') }}" class="{{ $navLinkClass('admin.faq.*') }}">
                        <svg class="size-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z" />
                        </svg>
                        <span class="truncate">Pertanyaan Umum</span>
                    </a>

                    <a href="{{ route('admin.pengaturan.index') }}" class="{{ $navLinkClass('admin.pengaturan.*') }}">
                        <svg class="size-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                        <span class="truncate">Pengaturan Situs</span>
                    </a>
                </div>
            </details>

            {{-- Group: Pengumuman & Pembayaran --}}
            <details open class="group/section space-y-1">
                <summary class="flex cursor-pointer list-none items-center justify-between px-3 py-1 text-[13px] font-medium text-[#4E566E]/80 hover:text-[#14141F] [&::-webkit-details-marker]:hidden">
                    <span>Pengumuman &amp; Bayar</span>
                    <svg class="size-4 transition-transform duration-200 group-open/section:rotate-90" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                    </svg>
                </summary>

                <div class="space-y-0.5 pt-1">
                    <a href="{{ route('admin.pengumuman.index') }}" class="{{ $navLinkClass('admin.pengumuman.*') }}">
                        <svg class="size-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 1 1 0-9h.75c.704 0 1.402-.03 2.09-.09m0 9.18c.253.962.584 1.892.985 2.783.247.55.06 1.21-.463 1.511l-.657.38c-.551.318-1.26.117-1.527-.461a20.845 20.845 0 0 1-1.44-4.282m3.102.069a18.03 18.03 0 0 1-.59-4.59c0-1.586.205-3.124.59-4.59m0 9.18a23.848 23.848 0 0 1 8.835 2.535M10.34 6.66a23.847 23.847 0 0 0 8.835-2.535m0 0A23.74 23.74 0 0 0 18.795 3m.38 1.125a23.91 23.91 0 0 1 1.014 5.395m-1.014 8.855c-.118.38-.245.754-.38 1.125m.38-1.125a23.91 23.91 0 0 0 1.014-5.395m0-3.46c.495.413.811 1.035.811 1.73 0 .695-.316 1.317-.811 1.73m0-3.46a24.347 24.347 0 0 1 0 3.46" />
                        </svg>
                        <span class="truncate">Pengumuman Kelulusan</span>
                    </a>

                    <a href="{{ route('admin.pembayaran.verifikasi.index') }}" class="{{ $navLinkClass('admin.pembayaran.*') }}">
                        <svg class="size-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" />
                        </svg>
                        <span class="truncate">Verifikasi Pembayaran</span>
                    </a>
                </div>
            </details>
        </nav>

        {{-- Sidebar Footer: Account (gaya Medesk) --}}
        <div class="border-t border-[#EFEBF4] px-4 py-3 space-y-2">
            <div class="px-2 text-[12px] font-medium text-[#4E566E]/80">Akun</div>
            <div class="flex items-center gap-2.5 rounded-xl px-2 py-1.5">
                <span class="flex size-9 shrink-0 items-center justify-center rounded-full border border-[#E6E1EC] bg-[#F5EFFB] font-heading text-xs font-bold text-[#7E22CE]" aria-hidden="true">
                    {{ $adminInitials }}
                </span>
                <div class="min-w-0 flex-1">
                    <div class="truncate text-sm font-semibold text-[#14141F]">{{ $adminName }}</div>
                    @if($adminEmail)
                        <div class="truncate text-xs text-[#4E566E]">{{ $adminEmail }}</div>
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
                <button id="admin-sidebar-open" type="button" aria-label="Buka menu navigasi"
                        class="inline-flex size-10 items-center justify-center rounded-xl border border-[#E6E1EC] text-[#14141F] hover:bg-[#F8F6F9]">
                    <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
                <span class="font-heading text-base font-bold text-[#14141F]">@yield('title', 'Admin')</span>
            </div>
            <span class="text-xs font-semibold text-[#4E566E]">{{ $adminName }}</span>
        </header>

        <main class="flex-1 px-4 py-6 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mx-auto mb-5 max-w-6xl rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mx-auto mb-5 max-w-6xl rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-800">
                    {{ session('error') }}
                </div>
            @endif
            @if(session('info'))
                <div class="mx-auto mb-5 max-w-6xl rounded-xl border border-[#E6E1EC] bg-white px-4 py-3 text-sm font-medium text-[#7E22CE]">
                    {{ session('info') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

<script>
    (function () {
        const sidebar  = document.getElementById('admin-sidebar');
        const backdrop = document.getElementById('admin-sidebar-backdrop');
        const openBtn  = document.getElementById('admin-sidebar-open');
        const closeBtn = document.getElementById('admin-sidebar-close');

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
