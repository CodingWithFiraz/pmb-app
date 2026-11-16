@extends('layouts.app')
@section('title', 'Periode Penerimaan')

@section('content')
<div class="flex flex-wrap justify-between items-center gap-4 mb-6">
    <div>
        <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-[#818CA2] mb-1">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-black transition">Dashboard</a>
            <span>/</span>
            <span class="text-[#606ADF]">Periode</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-black tracking-tight">Periode Penerimaan</h1>
    </div>
    <a href="{{ route('admin.periode.create') }}" 
       class="bg-[#606ADF] text-white px-5 py-2.5 rounded-xl font-semibold text-sm hover:bg-[#4F58CC] transition inline-flex items-center gap-2 shadow-xs">
        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
        </svg>
        <span>Tambah Periode</span>
    </a>
</div>

<div class="bg-white rounded-2xl border border-[#E5EAFC] shadow-xs overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-[#818CA2] text-xs uppercase tracking-wider border-b border-[#E5EAFC] bg-[#F2F5FF]/40">
                    <th class="px-6 py-3.5 font-semibold">Nama Periode</th>
                    <th class="px-6 py-3.5 font-semibold">Tahun Akademik</th>
                    <th class="px-6 py-3.5 font-semibold">Tanggal Buka</th>
                    <th class="px-6 py-3.5 font-semibold">Tanggal Tutup</th>
                    <th class="px-6 py-3.5 font-semibold">Biaya</th>
                    <th class="px-6 py-3.5 font-semibold">Status</th>
                    <th class="px-6 py-3.5 text-right font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#E5EAFC]">
                @forelse($periods as $period)
                    <tr class="hover:bg-[#F2F5FF]/50 transition">
                        <td class="px-6 py-4 font-semibold text-black">
                            {{ $period->name }}
                        </td>
                        <td class="px-6 py-4 text-gray-700">
                            {{ $period->academic_year }}
                        </td>
                        <td class="px-6 py-4 text-[#818CA2] text-xs">
                            {{ $period->starts_at->format('d M Y, H:i') }}
                        </td>
                        <td class="px-6 py-4 text-[#818CA2] text-xs">
                            {{ $period->ends_at->format('d M Y, H:i') }}
                        </td>
                        <td class="px-6 py-4 font-medium text-black">
                            {{ $period->registration_fee > 0 ? 'Rp ' . number_format($period->registration_fee, 0, ',', '.') : 'Gratis' }}
                        </td>
                        <td class="px-6 py-4">
                            @if($period->status === 'open')
                                <span class="px-3 py-1 rounded-lg text-xs font-semibold bg-[#F0FDF4] text-[#16A34A] border border-[#16A34A]/20">
                                    ✓ Dibuka
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-lg text-xs font-semibold bg-[#FEF2F2] text-[#DC2626] border border-[#DC2626]/20">
                                    Ditutup
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.periode.edit', $period) }}" 
                               class="inline-flex items-center text-xs font-semibold text-[#606ADF] hover:text-[#4F58CC] hover:underline">
                                Edit →
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-12 text-[#818CA2]">
                            <div class="w-12 h-12 rounded-xl bg-[#F2F5FF] text-[#606ADF] flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                                </svg>
                            </div>
                            <p class="font-medium text-sm">Belum ada data periode penerimaan.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6">{{ $periods->links() }}</div>
@endsection
