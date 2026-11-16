@extends('layouts.admin')
@section('title', 'Daftar Pendaftar')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Daftar Pendaftar</h1>
    <a href="{{ route('admin.pendaftaran.export', request()->query()) }}"
        class="bg-green-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-green-700 text-sm">
        Export CSV
    </a>
</div>

{{-- Filter --}}
<form method="GET" class="bg-white shadow rounded-xl p-4 mb-6 flex gap-3 flex-wrap">
    <input type="text" name="search" value="{{ request('search') }}"
        placeholder="Cari nama / nomor / email..."
        class="border rounded-lg px-3 py-2 text-sm flex-1 min-w-48 focus:ring-2 focus:ring-blue-500">

    <select name="periode_id" class="border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
        <option value="">Semua Periode</option>
        @foreach($periode as $p)
            <option value="{{ $p->id }}" {{ (int) request('periode_id') === $p->id ? 'selected' : '' }}>
                {{ $p->nama }}
            </option>
        @endforeach
    </select>

    <select name="jalur_id" class="border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
        <option value="">Semua Jalur</option>
        @foreach($jalur as $j)
            <option value="{{ $j->id }}" {{ (int) request('jalur_id') === $j->id ? 'selected' : '' }}>
                {{ $j->kode }} - {{ $j->nama }}
            </option>
        @endforeach
    </select>

    <select name="status" class="border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
        <option value="">Semua Status</option>
        @foreach([
            'draft'               => 'Draft',
            'menunggu_verifikasi' => 'Menunggu Verifikasi',
            'terverifikasi'       => 'Terverifikasi',
            'ditolak'             => 'Ditolak',
        ] as $value => $label)
            <option value="{{ $value }}" {{ request('status') === $value ? 'selected' : '' }}>{{ $label }}</option>
        @endforeach
    </select>

    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">Filter</button>
    <a href="{{ route('admin.pendaftaran.index') }}" class="border border-gray-300 text-gray-600 px-4 py-2 rounded-lg text-sm hover:bg-gray-50">Reset</a>
</div>

<div class="bg-white shadow rounded-xl overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600">
            <tr>
                <th class="text-left px-4 py-3">No. Daftar</th>
                <th class="text-left px-4 py-3">Nama</th>
                <th class="text-left px-4 py-3">Program Studi</th>
                <th class="text-left px-4 py-3">Jalur</th>
                <th class="text-left px-4 py-3">Periode</th>
                <th class="text-left px-4 py-3">Status</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody>
            @forelse($pendaftaran as $p)
                <tr class="border-t hover:bg-gray-50">
                    <td class="px-4 py-3 font-mono text-xs">{{ $p->nomor_pendaftaran ?? '-' }}</td>
                    <td class="px-4 py-3 font-medium">{{ $p->user->name }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $p->prodi->nama }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $p->jalur->kode }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $p->periode->tahun_akademik }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-0.5 rounded text-xs font-medium
                            @if($p->status === 'terverifikasi')
                                bg-green-100 text-green-700
                            @elseif($p->status === 'ditolak')
                                bg-red-100 text-red-700
                            @elseif($p->status === 'menunggu_verifikasi')
                                bg-amber-100 text-amber-700
                            @else
                                bg-gray-100 text-gray-700
                            @endif">
                            {{ $p->statusLabel() }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <a href="{{ route('admin.pendaftaran.show', $p) }}" class="text-blue-600 hover:underline">Detail</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center py-8 text-gray-500">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $pendaftaran->links() }}</div>
@endsection
