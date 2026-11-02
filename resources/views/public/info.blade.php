@extends('layouts.app')
@section('title', 'Informasi PMB')

@section('content')
<h1 class="text-2xl font-bold mb-6">Informasi PMB</h1>

@forelse($periods as $period)
    <div class="bg-white shadow rounded-xl p-6 mb-4">
        <div class="flex justify-between items-start">
            <div>
                <h2 class="font-bold text-lg">{{ $period->name }}</h2>
                <div class="text-gray-600 text-sm mt-1">Tahun Akademik {{ $period->academic_year }}</div>
            </div>
            <span class="px-3 py-1 rounded-full text-sm font-medium {{ $period->status === 'open' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                {{ $period->status === 'open' ? '✓ Dibuka' : 'Ditutup' }}
            </span>
        </div>
        <div class="mt-3 grid grid-cols-2 md:grid-cols-3 gap-3 text-sm">
            <div><span class="text-gray-500">Buka:</span> {{ $period->starts_at->format('d M Y') }}</div>
            <div><span class="text-gray-500">Tutup:</span> {{ $period->ends_at->format('d M Y') }}</div>
            <div><span class="text-gray-500">Biaya:</span>
                {{ $period->registration_fee > 0 ? 'Rp '.number_format($period->registration_fee,0,',','.') : 'Gratis' }}
            </div>
        </div>
    </div>
@empty
    <p class="text-gray-500">Belum ada informasi periode tersedia.</p>
@endforelse
@endsection