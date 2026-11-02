@extends('layouts.app')
@section('title', 'Admin Dashboard')

@section('content')
<h1 class="text-2xl font-bold mb-6">Dashboard Admin PMB</h1>

@if($activePeriod)
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6 text-blue-800">
        📢 Periode Aktif: <strong>{{ $activePeriod->name }}</strong>
        ({{ $activePeriod->starts_at->format('d M Y') }} — {{ $activePeriod->ends_at->format('d M Y') }})
    </div>
@endif

{{-- Stats Cards --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    @foreach([
        ['label' => 'Total', 'value' => $stats['total'], 'color' => 'bg-gray-100 text-gray-700'],
        ['label' => 'Menunggu', 'value' => $stats['submitted'], 'color' => 'bg-blue-100 text-blue-700'],
        ['label' => 'Diperiksa', 'value' => $stats['under_review'], 'color' => 'bg-yellow-100 text-yellow-700'],
        ['label' => 'Perlu Revisi', 'value' => $stats['revision_required'], 'color' => 'bg-orange-100 text-orange-700'],
        ['label' => 'Lulus', 'value' => $stats['passed'], 'color' => 'bg-green-100 text-green-700'],
        ['label' => 'Tidak Lulus', 'value' => $stats['not_passed'], 'color' => 'bg-red-100 text-red-700'],
        ['label' => 'Draft', 'value' => $stats['draft'], 'color' => 'bg-gray-100 text-gray-500'],
    ] as $stat)
        <div class="bg-white shadow rounded-xl p-4 text-center">
            <div class="text-2xl font-bold {{ $stat['color'] }} rounded-lg py-1">{{ $stat['value'] }}</div>
            <div class="text-sm text-gray-600 mt-1">{{ $stat['label'] }}</div>
        </div>
    @endforeach
</div>

{{-- Quick Links --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-8">
    <a href="{{ route('admin.applications.index') }}" class="bg-blue-600 text-white text-center py-3 rounded-xl font-medium hover:bg-blue-700">Daftar Pendaftar</a>
    <a href="{{ route('admin.periode.index') }}" class="bg-gray-700 text-white text-center py-3 rounded-xl font-medium hover:bg-gray-800">Periode</a>
    <a href="{{ route('admin.program-studi.index') }}" class="bg-gray-700 text-white text-center py-3 rounded-xl font-medium hover:bg-gray-800">Program Studi</a>
    <a href="{{ route('admin.applications.export') }}" class="bg-green-600 text-white text-center py-3 rounded-xl font-medium hover:bg-green-700">Export CSV</a>
</div>

{{-- Recent --}}
<div class="bg-white shadow rounded-xl p-6">
    <h2 class="font-bold text-lg mb-4">Pendaftar Terbaru</h2>
    @if($recentApplicants->isEmpty())
        <p class="text-gray-500">Belum ada pendaftar.</p>
    @else
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-gray-500 border-b">
                    <th class="pb-2">Nama</th>
                    <th class="pb-2">Prodi</th>
                    <th class="pb-2">Status</th>
                    <th class="pb-2"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentApplicants as $app)
                    <tr class="border-b last:border-0">
                        <td class="py-2">{{ $app->full_name }}</td>
                        <td class="py-2 text-gray-600">{{ $app->studyProgram->name }}</td>
                        <td class="py-2">
                            <span class="px-2 py-0.5 rounded text-xs font-medium bg-gray-100">{{ $app->status_label }}</span>
                        </td>
                        <td class="py-2">
                            <a href="{{ route('admin.applications.show', $app) }}" class="text-blue-600 hover:underline text-xs">Detail</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection 