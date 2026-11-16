@extends('layouts.admin')
@section('title', 'Pengumuman Kelulusan')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Pengumuman Kelulusan</h1>
    <a href="{{ route('admin.pendaftaran.index') }}" class="text-blue-600 hover:underline text-sm">← Kembali ke Daftar Pendaftar</a>
</div>

<form method="GET" class="bg-white shadow rounded-xl p-4 mb-6 flex flex-wrap gap-3 items-end">
    <input type="text" name="search" value="{{ request('search') }}"
        placeholder="Cari nama / nomor / email..."
        class="border rounded-lg px-3 py-2 text-sm flex-1 min-w-48 focus:ring-2 focus:ring-blue-500">

    <select name="periode_id" class="border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
        <option value="">Semua Periode</option>
        @foreach($periodeList as $p)
            <option value="{{ $p->id }}" {{ (int) request('periode_id') === $p->id ? 'selected' : '' }}>
                {{ $p->nama }}
            </option>
        @endforeach
    </select>

    <select name="jalur_id" class="border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
        <option value="">Semua Jalur</option>
        @foreach($jalurList as $j)
            <option value="{{ $j->id }}" {{ (int) request('jalur_id') === $j->id ? 'selected' : '' }}>
                {{ $j->kode }} - {{ $j->nama }}
            </option>
        @endforeach
    </select>

    <select name="status_bayar" class="border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
        <option value="">Semua Status Bayar</option>
        <option value="paid" {{ request('status_bayar') === 'paid' ? 'selected' : '' }}>Lunas</option>
        <option value="pending" {{ request('status_bayar') === 'pending' ? 'selected' : '' }}>Belum Lunas</option>
    </select>

    <select name="hasil_kelulusan" class="border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
        <option value="">Semua Hasil</option>
        <option value="belum" {{ request('hasil_kelulusan') === 'belum' ? 'selected' : '' }}>Belum Dinyatakan</option>
        <option value="lulus" {{ request('hasil_kelulusan') === 'lulus' ? 'selected' : '' }}>Lulus</option>
        <option value="tidak_lulus" {{ request('hasil_kelulusan') === 'tidak_lulus' ? 'selected' : '' }}>Tidak Lulus</option>
    </select>

    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">Filter</button>
    <a href="{{ route('admin.pengumuman.index') }}" class="border border-gray-300 text-gray-600 px-4 py-2 rounded-lg text-sm hover:bg-gray-50">Reset</a>
</div>

<div class="bg-white shadow rounded-xl overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600">
            <tr>
                <th class="text-left px-4 py-3">No. Daftar</th>
                <th class="text-left px-4 py-3">Nama</th>
                <th class="text-left px-4 py-3">Jalur</th>
                <th class="text-left px-4 py-3">Prodi</th>
                <th class="text-left px-4 py-3">Status Bayar</th>
                <th class="text-left px-4 py-3">Hasil Kelulusan</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody>
            @forelse($pendaftaran as $p)
                <tr class="border-t">
                    <td class="px-4 py-3 font-mono text-xs">{{ $p->nomor_pendaftaran ?? '-' }}</td>
                    <td class="px-4 py-3 font-medium">{{ $p->user->name }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $p->jalur?->kode ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $p->prodi?->nama ?? '-' }}</td>
                    <td class="px-4 py-3">
                        @php
                            $lunas = $p->pembayaran->every(fn ($t) => $t->status === 'paid') && $p->pembayaran->isNotEmpty();
                        @endphp
                        <span class="px-2 py-0.5 rounded text-xs font-medium {{ $lunas ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                            {{ $lunas ? 'Lunas' : 'Belum Lunas' }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-0.5 rounded text-xs font-medium
                            @if($p->hasil_kelulusan === 'lulus')
                                bg-green-100 text-green-700
                            @elseif($p->hasil_kelulusan === 'tidak_lulus')
                                bg-red-100 text-red-700
                            @else
                                bg-gray-100 text-gray-600
                            @endif">
                            @if($p->hasil_kelulusan === 'lulus')
                                Lulus
                            @elseif($p->hasil_kelulusan === 'tidak_lulus')
                                Tidak Lulus
                            @else
                                Belum
                            @endif
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <form method="POST" action="{{ route('admin.pengumuman.set-hasil', $p) }}"
                            class="inline flex flex-col sm:flex-row gap-2"
                            onsubmit="return confirm('Set hasil kelulusan untuk {{ $p->user->name }}?')">
                            @csrf
                            <select name="hasil_kelulusan" class="text-xs border rounded px-2 py-1 focus:ring-blue-500">
                                <option value="lulus" {{ old('hasil_kelulusan', $p->hasil_kelulusan) === 'lulus' ? 'selected' : '' }}>Lulus</option>
                                <option value="tidak_lulus" {{ old('hasil_kelulusan', $p->hasil_kelulusan) === 'tidak_lulus' ? 'selected' : '' }}>Tidak Lulus</option>
                                <option value="belum" {{ old('hasil_kelulusan', $p->hasil_kelulusan) === 'belum' ? 'selected' : '' }}>Belum</option>
                            </select>
                            <input type="text" name="catatan_admin" value="{{ old('catatan', $p->catatan_admin) }}"
                                placeholder="Catatan (opsional)"
                                class="text-xs border rounded px-2 py-1 focus:ring-blue-500 w-full sm:w-auto">
                            <button type="submit"
                                class="text-xs bg-blue-600 text-white px-2 py-1 rounded hover:bg-blue-700">
                                Set
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center py-8 text-gray-500">Belum ada data pendaftar.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $pendaftaran->links() }}</div>

 {{-- Bulk set hasil --}}
<div class="mt-8 bg-white shadow rounded-xl p-6">
    <h2 class="font-bold text-lg mb-4">Set Hasil Secara Bulk</h2>
    <p class="text-sm text-gray-600 mb-4">Pilih satu atau lebih pendaftar dari tabel diatur untuk ditetapkan hasil kelulusan secara bersamaan.</p>

    <form method="POST" action="{{ route('admin.pengumuman.bulk') }}" class="space-y-4">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Hasil Kelulusan <span class="text-red-500">*</span></label>
                <select name="hasil_kelulusan" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500" required>
                    <option value="lulus">Lulus</option>
                    <option value="tidak_lulus">Tidak Lulus</option>
                </select>
            </div>

            <div class="sm:w-1/3">
                <label class="block text-sm font-medium mb-1">Catatan Admin</label>
                <input type="text" name="catatan_admin"
                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Pilih Pendaftar <span class="text-red-500">*</span></label>
            <div class="max-h-60 overflow-y-auto border rounded-lg p-3 space-y-2 bg-gray-50">
                @forelse($pendaftaran as $p)
                    <label class="flex items-center gap-3 cursor-pointer hover:bg-white rounded p-2 border border-transparent hover:border-blue-300">
                        <input type="checkbox" name="pilihan[]" value="{{ $p->id }}"
                            class="mt-0.5 text-blue-600 focus:ring-blue-500">
                        <div>
                            <div class="text-sm font-medium text-gray-800">{{ $p->user->name }}</div>
                            <div class="text-xs text-gray-500">{{ $p->nomor_pendaftaran ?? '-' }} &middot; {{ $p->jalur?->kode ?? '-' }}</div>
                        </div>
                    </label>
                @empty
                    <p class="text-gray-500 text-sm">Tidak ada pendaftar yang cocok dengan filter saat ini.</p>
                @endforelse
            </div>
        </div>

        <button type="submit"
            class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700">
            Set Hasil untuk yang Dipilih
        </button>
    </form>
</div>
@endsection
