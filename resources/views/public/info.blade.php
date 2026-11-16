@extends('layouts.app')
@section('title', 'Informasi PMB')

@section('content')
<h1 class="text-2xl sm:text-3xl font-bold text-black mb-6">Informasi PMB</h1>

@forelse($periods as $period)
    <div class="bg-white rounded-2xl p-6 sm:p-7 mb-5 border border-[#E5EAFC] shadow-xs hover:shadow-md transition">
        <div class="flex flex-wrap justify-between items-start gap-4">
            <div>
                <h2 class="font-bold text-lg sm:text-xl text-black">{{ $period->name }}</h2>
                <div class="text-[#818CA2] text-sm mt-1">Tahun Akademik {{ $period->academic_year }}</div>
            </div>
            <span class="px-3.5 py-1 rounded-full text-xs font-semibold {{ $period->status === 'open' ? 'bg-[#F2F5FF] text-[#606ADF] border border-[#606ADF]/30' : 'bg-gray-100 text-gray-500' }}">
                {{ $period->status === 'open' ? '✓ Sedang Dibuka' : 'Ditutup' }}
            </span>
        </div>
        <div class="mt-5 pt-5 border-t border-[#E5EAFC] grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
            <div>
                <span class="text-[#818CA2] block text-xs uppercase font-medium">Buka</span>
                <span class="font-semibold text-black mt-0.5 block">{{ $period->starts_at->format('d M Y') }}</span>
            </div>
            <div>
                <span class="text-[#818CA2] block text-xs uppercase font-medium">Tutup</span>
                <span class="font-semibold text-black mt-0.5 block">{{ $period->ends_at->format('d M Y') }}</span>
            </div>
            <div>
                <span class="text-[#818CA2] block text-xs uppercase font-medium">Biaya Pendaftaran</span>
                <span class="font-semibold text-black mt-0.5 block">
                    {{ $period->registration_fee > 0 ? 'Rp ' . number_format($period->registration_fee, 0, ',', '.') : 'Gratis' }}
                </span>
            </div>
        </div>
    </div>
@empty
    <div class="bg-white rounded-2xl p-8 border border-[#E5EAFC] text-center text-[#818CA2]">
        Belum ada informasi periode pendaftaran yang tersedia.
    </div>
@endforelse
@endsection
