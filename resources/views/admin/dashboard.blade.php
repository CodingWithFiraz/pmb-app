@extends('layouts.app')
@section('title', 'Admin Dashboard')

@section('content')
{{-- Dashboard Heading --}}
<h1 class="text-2xl sm:text-3xl font-extrabold text-black mb-6 tracking-tight">Dashboard Admin PMB</h1>

{{-- Periode Aktif Banner with Soft Scaled Gradient & Grid --}}
@if($activePeriod)
    <div class="relative overflow-hidden admin-banner-gradient rounded-2xl p-6 sm:p-7 mb-8 text-white shadow-xs">
        {{-- Subtle Grid Pattern Proportionate to Banner Size --}}
        <div class="absolute inset-0 banner-grid-pattern-compact pointer-events-none"></div>

        <div class="relative z-10 flex flex-wrap items-baseline justify-between gap-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-white/85 block mb-1 drop-shadow-xs">Periode Aktif</span>
                <h2 class="text-xl sm:text-2xl font-extrabold text-white tracking-tight drop-shadow-xs">{{ $activePeriod->name }}</h2>
            </div>
            <div class="text-sm font-medium text-white/90 flex items-center gap-2 drop-shadow-xs">
                <span>{{ $activePeriod->starts_at->format('d M Y') }} — {{ $activePeriod->ends_at->format('d M Y') }}</span>
                <span class="text-white/70">•</span>
                <span>{{ $activePeriod->registration_fee > 0 ? 'Biaya: Rp ' . number_format($activePeriod->registration_fee, 0, ',', '.') : 'Gratis' }}</span>
            </div>
        </div>
    </div>
@else
    <div class="relative overflow-hidden admin-banner-gradient rounded-2xl p-6 sm:p-7 mb-8 text-white shadow-xs">
        <div class="absolute inset-0 banner-grid-pattern-compact pointer-events-none"></div>
        <div class="relative z-10">
            <span class="text-xs font-bold uppercase tracking-wider text-white/85 block mb-1 drop-shadow-xs">Periode Aktif</span>
            <h2 class="text-lg font-bold text-white drop-shadow-xs">Tidak ada periode pendaftaran aktif saat ini.</h2>
        </div>
    </div>
@endif

{{-- Status Stats Container (White Big Card Matching Reference Image) --}}
<div class="bg-white rounded-2xl p-6 sm:p-8 border border-[#E5EAFC] shadow-xs mb-8">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-5">
        {{-- Total --}}
        <div class="rounded-xl p-5 bg-[#EEF2FF]">
            <div class="text-sm font-bold text-gray-800">Total</div>
            <div class="text-3xl sm:text-4xl font-extrabold text-[#4F46E5] mt-1.5 font-numeric">{{ $stats['total'] }}</div>
        </div>

        {{-- Menunggu --}}
        <div class="rounded-xl p-5 bg-[#EFF6FF]">
            <div class="text-sm font-bold text-gray-800">Menunggu</div>
            <div class="text-3xl sm:text-4xl font-extrabold text-[#2563EB] mt-1.5 font-numeric">{{ $stats['submitted'] }}</div>
        </div>

        {{-- Diperiksa --}}
        <div class="rounded-xl p-5 bg-[#FEFCE8]">
            <div class="text-sm font-bold text-gray-800">Diperiksa</div>
            <div class="text-3xl sm:text-4xl font-extrabold text-[#D97706] mt-1.5 font-numeric">{{ $stats['under_review'] }}</div>
        </div>

        {{-- Perlu direvisi --}}
        <div class="rounded-xl p-5 bg-[#FFF7ED]">
            <div class="text-sm font-bold text-gray-800">Perlu direvisi</div>
            <div class="text-3xl sm:text-4xl font-extrabold text-[#EA580C] mt-1.5 font-numeric">{{ $stats['revision_required'] }}</div>
        </div>

        {{-- Lulus --}}
        <div class="rounded-xl p-5 bg-[#F0FDF4]">
            <div class="text-sm font-bold text-gray-800">Lulus</div>
            <div class="text-3xl sm:text-4xl font-extrabold text-[#16A34A] mt-1.5 font-numeric">{{ $stats['passed'] }}</div>
        </div>

        {{-- Tidak lulus --}}
        <div class="rounded-xl p-5 bg-[#FEF2F2]">
            <div class="text-sm font-bold text-gray-800">Tidak lulus</div>
            <div class="text-3xl sm:text-4xl font-extrabold text-[#DC2626] mt-1.5 font-numeric">{{ $stats['not_passed'] }}</div>
        </div>

        {{-- Draft --}}
        <div class="rounded-xl p-5 bg-[#F1F5F9]">
            <div class="text-sm font-bold text-gray-800">Draft</div>
            <div class="text-3xl sm:text-4xl font-extrabold text-[#0D9488] mt-1.5 font-numeric">{{ $stats['draft'] }}</div>
        </div>
    </div>
</div>

{{-- Lower Section: Navigation Menu on the Left, Pendaftar Terbaru on the Right --}}
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
    {{-- Left Column: Navigation Card + Export CSV Button --}}
    <div class="lg:col-span-4 space-y-3">
        <div class="bg-white rounded-2xl p-2.5 border border-[#E5EAFC] shadow-xs">
            <nav class="space-y-1">
                {{-- Daftar Pendaftar (Users Icon) --}}
                <a href="{{ route('admin.applications.index') }}" 
                   class="flex items-center gap-3.5 px-4 py-3 rounded-xl text-gray-800 hover:text-black hover:bg-[#F2F5FF] font-semibold text-sm transition group">
                    <div class="w-8 h-8 rounded-lg bg-[#F2F5FF] group-hover:bg-white flex items-center justify-center shrink-0 transition">
                        <svg class="w-4.5 h-4.5 text-[#606ADF]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                        </svg>
                    </div>
                    <span>Daftar pendaftar</span>
                </a>

                {{-- Periode (Calendar Icon) --}}
                <a href="{{ route('admin.periode.index') }}" 
                   class="flex items-center gap-3.5 px-4 py-3 rounded-xl text-gray-800 hover:text-black hover:bg-[#F2F5FF] font-semibold text-sm transition group">
                    <div class="w-8 h-8 rounded-lg bg-[#F2F5FF] group-hover:bg-white flex items-center justify-center shrink-0 transition">
                        <svg class="w-4.5 h-4.5 text-[#606ADF]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                        </svg>
                    </div>
                    <span>Periode</span>
                </a>

                {{-- Program Studi (Graduation Cap Icon) --}}
                <a href="{{ route('admin.program-studi.index') }}" 
                   class="flex items-center gap-3.5 px-4 py-3 rounded-xl text-gray-800 hover:text-black hover:bg-[#F2F5FF] font-semibold text-sm transition group">
                    <div class="w-8 h-8 rounded-lg bg-[#F2F5FF] group-hover:bg-white flex items-center justify-center shrink-0 transition">
                        <svg class="w-4.5 h-4.5 text-[#606ADF]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
                        </svg>
                    </div>
                    <span>Program studi</span>
                </a>
            </nav>
        </div>

        {{-- Export CSV (Black Button Matching Image) --}}
        <a href="{{ route('admin.applications.export') }}" 
           class="bg-black text-white w-full py-3.5 px-5 rounded-2xl font-semibold text-sm hover:bg-gray-800 transition flex items-center justify-center gap-2.5 shadow-xs">
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
            </svg>
            <span>Export CSV</span>
        </a>
    </div>

    {{-- Right Column: Pendaftar Terbaru Beside the Menu --}}
    <div class="lg:col-span-8">
        <div class="bg-white rounded-2xl p-6 sm:p-7 border border-[#E5EAFC] shadow-xs">
            <div class="flex items-center justify-between mb-5 pb-3 border-b border-[#E5EAFC]">
                <div>
                    <h2 class="font-bold text-lg text-black">Pendaftar Terbaru</h2>
                    <p class="text-xs text-[#818CA2] mt-0.5">Daftar pendaftaran terbaru yang masuk ke sistem</p>
                </div>
                <a href="{{ route('admin.applications.index') }}" 
                   class="text-xs font-semibold text-[#606ADF] hover:text-[#4F58CC] hover:underline flex items-center gap-1">
                    Lihat Semua
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                    </svg>
                </a>
            </div>

            @if($recentApplicants->isEmpty())
                <div class="text-center py-10 text-[#818CA2]">
                    <div class="w-12 h-12 rounded-xl bg-[#F2F5FF] text-[#606ADF] flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                        </svg>
                    </div>
                    <p class="font-medium text-sm">Belum ada pendaftar terbaru.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-[#818CA2] text-xs uppercase tracking-wider border-b border-[#E5EAFC]">
                                <th class="pb-3 font-semibold">Nama</th>
                                <th class="pb-3 font-semibold">Prodi</th>
                                <th class="pb-3 font-semibold">Status</th>
                                <th class="pb-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#E5EAFC]">
                            @foreach($recentApplicants as $app)
                                <tr class="hover:bg-[#F2F5FF]/50 transition">
                                    <td class="py-3.5 pr-3 font-semibold text-black">
                                        <div class="text-sm">{{ $app->full_name }}</div>
                                        @if($app->registration_number)
                                            <div class="text-xs font-mono text-[#818CA2] font-normal mt-0.5">{{ $app->registration_number }}</div>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-3 text-gray-700 text-xs sm:text-sm">
                                        {{ $app->studyProgram->name }}
                                    </td>
                                    <td class="py-3.5 px-3">
                                        @php
                                            $statusStyles = [
                                                'draft' => 'bg-gray-100 text-gray-600',
                                                'submitted' => 'bg-blue-50 text-blue-700 border border-blue-200',
                                                'under_review' => 'bg-amber-50 text-amber-700 border border-amber-200',
                                                'revision_required' => 'bg-orange-50 text-orange-700 border border-orange-200',
                                                'passed' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
                                                'not_passed' => 'bg-red-50 text-red-700 border border-red-200',
                                            ];
                                            $badgeStyle = $statusStyles[$app->status] ?? 'bg-gray-100 text-gray-700';
                                        @endphp
                                        <span class="px-2.5 py-1 rounded-lg text-xs font-semibold inline-block {{ $badgeStyle }}">
                                            {{ $app->status_label }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 pl-3 text-right">
                                        <a href="{{ route('admin.applications.show', $app) }}" 
                                           class="inline-flex items-center text-xs font-semibold text-[#606ADF] hover:text-[#4F58CC] hover:underline">
                                            Detail →
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
