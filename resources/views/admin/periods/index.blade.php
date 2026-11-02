@extends('layouts.app')
@section('title', 'Periode Penerimaan')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Periode Penerimaan</h1>
    <a href="{{ route('admin.periode.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-blue-700">+ Tambah Periode</a>
</div>

<div class="bg-white shadow rounded-xl overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600">
            <tr>
                <th class="text-left px-4 py-3">Nama</th>
                <th class="text-left px-4 py-3">Tahun Akademik</th>
                <th class="text-left px-4 py-3">Mulai</th>
                <th class="text-left px-4 py-3">Selesai</th>
                <th class="text-left px-4 py-3">Biaya</th>
                <th class="text-left px-4 py-3">Status</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody>
            @forelse($periods as $period)
                <tr class="border-t">
                    <td class="px-4 py-3 font-medium">{{ $period->name }}</td>
                    <td class="px-4 py-3">{{ $period->academic_year }}</td>
                    <td class="px-4 py-3">{{ $period->starts_at->format('d/m/Y') }}</td>
                    <td class="px-4 py-3">{{ $period->ends_at->format('d/m/Y') }}</td>
                    <td class="px-4 py-3">{{ $period->registration_fee > 0 ? 'Rp '.number_format($period->registration_fee,0,',','.') : 'Gratis' }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-0.5 rounded text-xs font-medium {{ $period->status === 'open' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                            {{ $period->status === 'open' ? 'Buka' : 'Tutup' }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <a href="{{ route('admin.periode.edit', $period) }}" class="text-blue-600 hover:underline text-xs">Edit</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center py-8 text-gray-500">Belum ada periode.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $periods->links() }}</div>
@endsection