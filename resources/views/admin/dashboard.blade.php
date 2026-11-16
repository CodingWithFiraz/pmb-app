@extends('layouts.app')
@section('title', 'Admin Dashboard')

@section('content')
{{-- Dashboard Heading (Tanpa Tombol di Kanan Atas) --}}
<div class="mb-6">
    <h1 class="text-2xl sm:text-3xl font-extrabold text-black tracking-tight">Dashboard Admin PMB</h1>
    <p class="text-sm text-[#818CA2] mt-1">Pusat kendali seleksi penerimaan mahasiswa baru dan verifikasi berkas</p>
</div>

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

{{-- Section 1: Metrics (Kotak Putih Rounded Hanya Berisi Card Tanpa Ikon dan Tanpa Header) --}}
<div class="bg-white rounded-2xl p-6 sm:p-7 border border-[#E5EAFC] shadow-xs mb-8">
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-7 gap-3.5">
        {{-- 1. Total (Purple / Indigo) --}}
        <div class="flex flex-col justify-between h-32 rounded-xl border border-[#E0E7FF] bg-[#EEF2FF] p-4 transition-all duration-150 hover:shadow-xs hover:-translate-y-0.5">
            <div>
                <span class="text-xs sm:text-sm font-semibold tracking-tight text-[#4F46E5]">Total</span>
            </div>
            <div class="flex flex-col gap-1">
                <div class="text-3xl font-extrabold text-[#4F46E5] leading-none font-numeric tracking-tight">
                    {{ $stats['total'] }}
                </div>
                <div class="text-[11px] font-medium text-[#4F46E5]/80">
                    Semua akun pendaftar
                </div>
            </div>
        </div>

        {{-- 2. Menunggu (Blue) --}}
        <div class="flex flex-col justify-between h-32 rounded-xl border border-[#DBEAFE] bg-[#EFF6FF] p-4 transition-all duration-150 hover:shadow-xs hover:-translate-y-0.5">
            <div>
                <span class="text-xs sm:text-sm font-semibold tracking-tight text-[#2563EB]">Menunggu</span>
            </div>
            <div class="flex flex-col gap-1">
                <div class="text-3xl font-extrabold text-[#2563EB] leading-none font-numeric tracking-tight">
                    {{ $stats['submitted'] }}
                </div>
                <div class="text-[11px] font-medium text-[#2563EB]/80">
                    Menunggu verifikasi
                </div>
            </div>
        </div>

        {{-- 3. Diperiksa (Amber / Yellow) --}}
        <div class="flex flex-col justify-between h-32 rounded-xl border border-[#FEF08A] bg-[#FEFCE8] p-4 transition-all duration-150 hover:shadow-xs hover:-translate-y-0.5">
            <div>
                <span class="text-xs sm:text-sm font-semibold tracking-tight text-[#D97706]">Diperiksa</span>
            </div>
            <div class="flex flex-col gap-1">
                <div class="text-3xl font-extrabold text-[#D97706] leading-none font-numeric tracking-tight">
                    {{ $stats['under_review'] }}
                </div>
                <div class="text-[11px] font-medium text-[#D97706]/80">
                    Dalam review admin
                </div>
            </div>
        </div>

        {{-- 4. Perlu direvisi (Orange) --}}
        <div class="flex flex-col justify-between h-32 rounded-xl border border-[#FFEDD5] bg-[#FFF7ED] p-4 transition-all duration-150 hover:shadow-xs hover:-translate-y-0.5">
            <div>
                <span class="text-xs sm:text-sm font-semibold tracking-tight text-[#EA580C]">Perlu Revisi</span>
            </div>
            <div class="flex flex-col gap-1">
                <div class="text-3xl font-extrabold text-[#EA580C] leading-none font-numeric tracking-tight">
                    {{ $stats['revision_required'] }}
                </div>
                <div class="text-[11px] font-medium text-[#EA580C]/80">
                    Berkas perlu perbaikan
                </div>
            </div>
        </div>

        {{-- 5. Lulus (Emerald / Green) --}}
        <div class="flex flex-col justify-between h-32 rounded-xl border border-[#DCFCE7] bg-[#F0FDF4] p-4 transition-all duration-150 hover:shadow-xs hover:-translate-y-0.5">
            <div>
                <span class="text-xs sm:text-sm font-semibold tracking-tight text-[#16A34A]">Lulus</span>
            </div>
            <div class="flex flex-col gap-1">
                <div class="text-3xl font-extrabold text-[#16A34A] leading-none font-numeric tracking-tight">
                    {{ $stats['passed'] }}
                </div>
                <div class="text-[11px] font-medium text-[#16A34A]/80">
                    Lolos verifikasi berkas
                </div>
            </div>
        </div>

        {{-- 6. Tidak lulus (Red) --}}
        <div class="flex flex-col justify-between h-32 rounded-xl border border-[#FEE2E2] bg-[#FEF2F2] p-4 transition-all duration-150 hover:shadow-xs hover:-translate-y-0.5">
            <div>
                <span class="text-xs sm:text-sm font-semibold tracking-tight text-[#DC2626]">Tidak Lulus</span>
            </div>
            <div class="flex flex-col gap-1">
                <div class="text-3xl font-extrabold text-[#DC2626] leading-none font-numeric tracking-tight">
                    {{ $stats['not_passed'] }}
                </div>
                <div class="text-[11px] font-medium text-[#DC2626]/80">
                    Tidak memenuhi syarat
                </div>
            </div>
        </div>

        {{-- 7. Draft (Teal / Slate) --}}
        <div class="flex flex-col justify-between h-32 rounded-xl border border-[#E2E8F0] bg-[#F1F5F9] p-4 transition-all duration-150 hover:shadow-xs hover:-translate-y-0.5">
            <div>
                <span class="text-xs sm:text-sm font-semibold tracking-tight text-[#0D9488]">Draft</span>
            </div>
            <div class="flex flex-col gap-1">
                <div class="text-3xl font-extrabold text-[#0D9488] leading-none font-numeric tracking-tight">
                    {{ $stats['draft'] }}
                </div>
                <div class="text-[11px] font-medium text-[#0D9488]/80">
                    Belum diajukan pendaftar
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Section 2: Lower Section (Navigation & Tallie-Inspired Pendaftar Terbaru Table) --}}
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
    {{-- Left Column: Navigation Card + Export CSV Button --}}
    <div class="lg:col-span-4 space-y-3">
        <div class="bg-white rounded-2xl p-3 border border-[#E5EAFC] shadow-xs">
            <div class="px-3 pt-2 pb-2">
                <span class="text-xs font-bold uppercase tracking-wider text-[#818CA2]">Menu Pengelolaan</span>
            </div>
            <nav class="space-y-1">
                {{-- Daftar Pendaftar (Users Icon) --}}
                <a href="{{ route('admin.applications.index') }}" 
                   class="flex items-center gap-3.5 px-3.5 py-3 rounded-xl text-gray-800 hover:text-black hover:bg-[#F2F5FF] font-semibold text-sm transition group">
                    <div class="w-8 h-8 rounded-lg bg-[#F2F5FF] group-hover:bg-white text-[#5F4AF7] flex items-center justify-center shrink-0 border border-[#DBDCFA]/60 transition">
                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                        </svg>
                    </div>
                    <span>Daftar pendaftar</span>
                </a>

                {{-- Periode (Calendar Icon) --}}
                <a href="{{ route('admin.periode.index') }}" 
                   class="flex items-center gap-3.5 px-3.5 py-3 rounded-xl text-gray-800 hover:text-black hover:bg-[#F2F5FF] font-semibold text-sm transition group">
                    <div class="w-8 h-8 rounded-lg bg-[#F2F5FF] group-hover:bg-white text-[#5F4AF7] flex items-center justify-center shrink-0 border border-[#DBDCFA]/60 transition">
                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                        </svg>
                    </div>
                    <span>Periode pendaftaran</span>
                </a>

                {{-- Program Studi (Graduation Cap Icon) --}}
                <a href="{{ route('admin.program-studi.index') }}" 
                   class="flex items-center gap-3.5 px-3.5 py-3 rounded-xl text-gray-800 hover:text-black hover:bg-[#F2F5FF] font-semibold text-sm transition group">
                    <div class="w-8 h-8 rounded-lg bg-[#F2F5FF] group-hover:bg-white text-[#5F4AF7] flex items-center justify-center shrink-0 border border-[#DBDCFA]/60 transition">
                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
                        </svg>
                    </div>
                    <span>Program studi</span>
                </a>
            </nav>
        </div>

        {{-- Export CSV (Black Button with Tallie Precision) --}}
        <a href="{{ route('admin.applications.export') }}" 
           class="bg-black text-white w-full py-3.5 px-5 rounded-2xl font-bold text-sm hover:bg-gray-800 transition flex items-center justify-center gap-2.5 shadow-xs">
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
            </svg>
            <span>Export Data Pendaftar (CSV)</span>
        </a>
    </div>

    {{-- Right Column: Redesigned Pendaftar Terbaru Table (Watermelon Tallie Table Structure) --}}
    <div class="lg:col-span-8">
        <div class="bg-white rounded-2xl border border-[#E5EAFC] shadow-xs overflow-hidden">
            {{-- Table Section Header --}}
            <div class="p-5 sm:p-6 border-b border-[#E5EAFC] flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white">
                <div>
                    <div class="flex items-center gap-2.5">
                        <h2 class="font-extrabold text-base sm:text-lg text-black tracking-tight">Pendaftar Terbaru</h2>
                        @if($recentApplicants->isNotEmpty())
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-semibold bg-[#F2F5FF] text-[#5F4AF7] border border-[#DBDCFA]">
                                {{ $recentApplicants->count() }} Data
                            </span>
                        @endif
                    </div>
                    <p class="text-xs text-[#818CA2] mt-0.5">Daftar calon mahasiswa baru yang baru saja mengajukan berkas</p>
                </div>
                <a href="{{ route('admin.applications.index') }}" 
                   class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#5F4AF7] hover:text-[#4E39E5] transition py-1 px-2.5 rounded-lg hover:bg-[#F2F5FF]">
                    <span>Lihat Semua Pendaftar</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                    </svg>
                </a>
            </div>

            {{-- Table Body Container --}}
            @if($recentApplicants->isEmpty())
                <div class="text-center py-14 px-6 text-[#818CA2]">
                    <div class="w-12 h-12 rounded-2xl bg-[#F2F5FF] text-[#5F4AF7] flex items-center justify-center mx-auto mb-3 border border-[#DBDCFA]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                        </svg>
                    </div>
                    <p class="font-bold text-sm text-black">Belum Ada Pendaftar Terbaru</p>
                    <p class="text-xs text-[#818CA2] mt-1 max-w-sm mx-auto">Data pendaftaran yang baru masuk akan langsung ditampilkan di sini.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        {{-- Tallie-Style Table Head --}}
                        <thead class="bg-[#F8FAFC] border-b border-[#E5EAFC]">
                            <tr class="text-[#818CA2] text-[11px] uppercase tracking-wider font-semibold">
                                <th scope="col" class="py-3.5 px-5">Pendaftar</th>
                                <th scope="col" class="py-3.5 px-4">Program Studi</th>
                                <th scope="col" class="py-3.5 px-4">Status Verifikasi</th>
                                <th scope="col" class="py-3.5 px-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        {{-- Tallie-Style Table Body with Clean Dividers & Spacing --}}
                        <tbody class="divide-y divide-[#E5EAFC]">
                            @foreach($recentApplicants as $app)
                                <tr class="hover:bg-[#F8FAFC]/80 transition duration-150 group">
                                    {{-- Column 1: Applicant Info (2-Tier Hierarchy with Avatar) --}}
                                    <td class="py-4 px-5">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-xl bg-[#EEF2FF] text-[#4F46E5] font-bold text-xs flex items-center justify-center shrink-0 border border-[#E0E7FF]">
                                                {{ strtoupper(substr($app->full_name, 0, 2)) }}
                                            </div>
                                            <div class="flex flex-col min-w-0">
                                                <span class="font-bold text-sm text-black group-hover:text-[#5F4AF7] transition truncate">
                                                    {{ $app->full_name }}
                                                </span>
                                                <span class="text-xs font-mono text-[#818CA2] mt-0.5">
                                                    {{ $app->registration_number ?? 'No. Reg: -' }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Column 2: Program Studi --}}
                                    <td class="py-4 px-4 text-xs sm:text-sm">
                                        <div class="font-medium text-black">
                                            {{ $app->studyProgram->name }}
                                        </div>
                                        <div class="text-[11px] text-[#818CA2] mt-0.5">
                                            {{ $app->admissionPeriod->name ?? 'Reguler' }}
                                        </div>
                                    </td>

                                    {{-- Column 3: Status Badge (Tallie Status Chip with Semantics) --}}
                                    <td class="py-4 px-4 whitespace-nowrap">
                                        @php
                                            $statusConfig = [
                                                'draft' => [
                                                    'badge' => 'bg-[#F1F5F9] text-[#475569] border-[#E2E8F0]',
                                                    'dot' => 'bg-[#94A3B8]',
                                                ],
                                                'submitted' => [
                                                    'badge' => 'bg-[#EFF6FF] text-[#2563EB] border-[#DBEAFE]',
                                                    'dot' => 'bg-[#2563EB]',
                                                ],
                                                'under_review' => [
                                                    'badge' => 'bg-[#FEFCE8] text-[#D97706] border-[#FEF08A]',
                                                    'dot' => 'bg-[#D97706]',
                                                ],
                                                'revision_required' => [
                                                    'badge' => 'bg-[#FFF7ED] text-[#EA580C] border-[#FFEDD5]',
                                                    'dot' => 'bg-[#EA580C]',
                                                ],
                                                'passed' => [
                                                    'badge' => 'bg-[#F0FDF4] text-[#16A34A] border-[#DCFCE7]',
                                                    'dot' => 'bg-[#16A34A]',
                                                ],
                                                'not_passed' => [
                                                    'badge' => 'bg-[#FEF2F2] text-[#DC2626] border-[#FEE2E2]',
                                                    'dot' => 'bg-[#DC2626]',
                                                ],
                                            ];
                                            $cfg = $statusConfig[$app->status] ?? [
                                                'badge' => 'bg-[#F1F5F9] text-[#475569] border-[#E2E8F0]',
                                                'dot' => 'bg-[#94A3B8]',
                                            ];
                                        @endphp
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold border {{ $cfg['badge'] }}">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $cfg['dot'] }}"></span>
                                            {{ $app->status_label }}
                                        </span>
                                    </td>

                                    {{-- Column 4: Action Button (Tallie-Inspired Outline Pill Action) --}}
                                    <td class="py-4 px-5 text-right whitespace-nowrap">
                                        <a href="{{ route('admin.applications.show', $app) }}" 
                                           class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl border border-[#DBDCFA] bg-white text-xs font-semibold text-[#5F4AF7] hover:bg-[#5F4AF7] hover:text-white hover:border-[#5F4AF7] transition shadow-2xs">
                                            <span>Detail</span>
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                            </svg>
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
