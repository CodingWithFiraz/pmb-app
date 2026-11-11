@extends('layouts.app')
@section('title', 'Daftar Pendaftar')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Daftar Pendaftar</h1>
    <a href="{{ route('admin.applications.export', request()->query()) }}"
        class="bg-green-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-green-700 text-sm">
        Export CSV
    </a>
</div>

{{-- Filter --}}
<form method="GET" class="bg-white shadow rounded-xl p-4 mb-6 flex gap-3 flex-wrap">
    <input type="text" name="search" value="{{ request('search') }}"
        placeholder="Cari nama / nomor / email..."
        class="border rounded-lg px-3 py-2 text-sm flex-1 min-w-48 focus:ring-2 focus:ring-blue-500">
    <select name="status" class="border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
        <option value="">Semua Status</option>
        @foreach(['draft','submitted','under_review','revision_required','passed','not_passed'] as $s)
            <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ $s }}</option>
        @endforeach
    </select>
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">Filter</button>
    <a href="{{ route('admin.applications.index') }}" class="border border-gray-300 text-gray-600 px-4 py-2 rounded-lg text-sm hover:bg-gray-50">Reset</a>
</form>

<div class="bg-white shadow rounded-xl overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600">
            <tr>
                <th class="text-left px-4 py-3">No. Daftar</th>
                <th class="text-left px-4 py-3">Nama</th>
                <th class="text-left px-4 py-3">Program Studi</th>
                <th class="text-left px-4 py-3">Status</th>
                <th class="text-left px-4 py-3">Tgl Submit</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody>
            @forelse($applications as $app)
                <tr class="border-t hover:bg-gray-50">
                    <td class="px-4 py-3 font-mono text-xs">{{ $app->registration_number ?? '-' }}</td>
                    <td class="px-4 py-3 font-medium">{{ $app->full_name }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $app->studyProgram->name }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-0.5 rounded text-xs font-medium bg-gray-100">{{ $app->status_label }}</span>
                    </td>
                    <td class="px-4 py-3 text-gray-500 text-xs">{{ $app->submitted_at?->format('d/m/Y') ?? '-' }}</td>
                    <td class="px-4 py-3">
                        <a href="{{ route('admin.applications.show', $app) }}" class="text-blue-600 hover:underline">Detail</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center py-8 text-gray-500">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $applications->links() }}</div>
@endsection