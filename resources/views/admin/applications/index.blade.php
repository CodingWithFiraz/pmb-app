@extends('layouts.app')
@section('title', 'Daftar Pendaftar')

@section('content')
{{-- Header --}}
<div class="flex flex-wrap justify-between items-center gap-4 mb-6">
    <div>
        <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-[#818CA2] mb-1">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-black transition">Dashboard</a>
            <span>/</span>
            <span class="text-[#606ADF]">Pendaftar</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-black tracking-tight">Daftar Pendaftar</h1>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.applications.export', request()->query()) }}"
            class="bg-black text-white px-5 py-2.5 rounded-xl font-semibold text-sm hover:bg-gray-800 transition inline-flex items-center gap-2 shadow-xs">
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
            </svg>
            <span>Export CSV</span>
        </a>
    </div>
</div>

{{-- Filter Card --}}
<div class="bg-white rounded-2xl p-5 border border-[#E5EAFC] shadow-xs mb-6">
    <form method="GET" class="flex flex-col sm:flex-row gap-3">
        <div class="relative flex-1">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Cari nama, nomor pendaftaran, atau email..."
                class="w-full bg-[#F2F5FF]/60 border border-[#E5EAFC] rounded-xl pl-10 pr-4 py-2.5 text-sm text-black focus:outline-none focus:ring-2 focus:ring-[#606ADF] focus:border-[#606ADF] transition">
            <svg class="w-4 h-4 text-[#818CA2] absolute left-3.5 top-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
            </svg>
        </div>
        <select name="status" 
            class="bg-[#F2F5FF]/60 border border-[#E5EAFC] rounded-xl px-4 py-2.5 text-sm text-black focus:outline-none focus:ring-2 focus:ring-[#606ADF] focus:border-[#606ADF] transition">
            <option value="">Semua Status</option>
            @php
                $statusLabels = [
                    'draft' => 'Draft',
                    'submitted' => 'Menunggu',
                    'under_review' => 'Diperiksa',
                    'revision_required' => 'Perlu Revisi',
                    'passed' => 'Lulus',
                    'not_passed' => 'Tidak Lulus',
                ];
            @endphp
            @foreach($statusLabels as $k => $v)
                <option value="{{ $k }}" {{ request('status') == $k ? 'selected' : '' }}>{{ $v }}</option>
            @endforeach
        </select>
        <button type="submit" 
            class="bg-[#606ADF] text-white px-6 py-2.5 rounded-xl font-semibold text-sm hover:bg-[#4F58CC] transition shadow-xs">
            Filter
        </button>
        @if(request('search') || request('status'))
            <a href="{{ route('admin.applications.index') }}" 
                class="bg-white border border-[#E5EAFC] text-[#818CA2] hover:text-black hover:bg-[#F2F5FF] px-4 py-2.5 rounded-xl text-sm font-semibold transition text-center">
                Reset
            </a>
        @endif
    </form>
</div>

{{-- Table Card --}}
<div class="bg-white rounded-2xl border border-[#E5EAFC] shadow-xs overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-[#818CA2] text-xs uppercase tracking-wider border-b border-[#E5EAFC] bg-[#F2F5FF]/40">
                    <th class="px-6 py-3.5 font-semibold">No. Daftar</th>
                    <th class="px-6 py-3.5 font-semibold">Nama Lengkap</th>
                    <th class="px-6 py-3.5 font-semibold">Program Studi</th>
                    <th class="px-6 py-3.5 font-semibold">Status</th>
                    <th class="px-6 py-3.5 font-semibold">Tgl Submit</th>
                    <th class="px-6 py-3.5 text-right font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#E5EAFC]">
                @forelse($applications as $app)
                    <tr class="hover:bg-[#F2F5FF]/50 transition">
                        <td class="px-6 py-4 font-mono text-xs font-semibold text-[#606ADF]">
                            {{ $app->registration_number ?? '-' }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-semibold text-black">{{ $app->full_name }}</div>
                            <div class="text-xs text-[#818CA2] mt-0.5">{{ $app->user->email }}</div>
                        </td>
                        <td class="px-6 py-4 text-gray-700">
                            {{ $app->studyProgram->name }}
                        </td>
                        <td class="px-6 py-4">
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
                            <span class="px-3 py-1 rounded-lg text-xs font-semibold inline-block {{ $badgeStyle }}">
                                {{ $app->status_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-[#818CA2] text-xs">
                            {{ $app->submitted_at?->format('d M Y, H:i') ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.applications.show', $app) }}" 
                               class="inline-flex items-center text-xs font-semibold text-[#606ADF] hover:text-[#4F58CC] hover:underline">
                                Detail →
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-12 text-[#818CA2]">
                            <div class="w-12 h-12 rounded-xl bg-[#F2F5FF] text-[#606ADF] flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                                </svg>
                            </div>
                            <p class="font-medium text-sm">Tidak ada pendaftar yang cocok dengan pencarian.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6">{{ $applications->links() }}</div>
@endsection
