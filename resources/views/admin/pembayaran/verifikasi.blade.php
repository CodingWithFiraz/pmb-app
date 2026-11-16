@extends('layouts.admin')
@section('title', 'Verifikasi Pembayaran')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Verifikasi Pembayaran</h1>
    <p class="text-sm text-gray-600">Bukti transfer yang menunggu peninjauan panitia.</p>
</div>

<form method="GET" class="bg-white shadow rounded-xl p-4 mb-6 flex flex-wrap gap-3 items-end">
    <input type="text" name="search" value="{{ request('search') }}"
        placeholder="Cari nama / nomor / email..."
        class="border rounded-lg px-3 py-2 text-sm flex-1 min-w-48 focus:ring-2 focus:ring-blue-500">

    <select name="jenis" class="border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
        <option value="">Semua Jenis</option>
        <option value="pendaftaran" {{ request('jenis') === 'pendaftaran' ? 'selected' : '' }}>Biaya Pendaftaran</option>
        <option value="ukt" {{ request('jenis') === 'ukt' ? 'selected' : '' }}>UKT</option>
    </select>

    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">Filter</button>
    <a href="{{ route('admin.pembayaran.verifikasi.index') }}" class="border border-gray-300 text-gray-600 px-4 py-2 rounded-lg text-sm hover:bg-gray-50">Reset</a>
</div>

<div class="bg-white shadow rounded-xl overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600">
            <tr>
                <th class="text-left px-4 py-3">No. Daftar</th>
                <th class="text-left px-4 py-3">Nama</th>
                <th class="text-left px-4 py-3">Jenis</th>
                <th class="text-left px-4 py-3">Nominal</th>
                <th class="text-left px-4 py-3">Bukti</th>
                <th class="text-center px-4 py-3">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pembayaran as $p)
                <tr class="border-t">
                    <td class="px-4 py-3 font-mono text-xs">{{ $p->pendaftaran->nomor_pendaftaran ?? '-' }}</td>
                    <td class="px-4 py-3 font-medium">{{ $p->pendaftaran->user->name }}</td>
                    <td class="px-4 py-3 text-gray-600">
                        <span class="px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-700">
                            {{ $p->jenisLabel() }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-gray-600">
                        Rp {{ number_format($p->nominal, 0, ',', '.') }}
                    </td>
                    <td class="px-4 py-3">
                        @if($p->bukti_path && \Illuminate\Support\Facades\Storage::disk('local')->exists($p->bukti_path))
                            <a href="{{ \Illuminate\Support\Facades\Storage::disk('local')->url($p->bukti_path) }}"
                                class="text-blue-600 hover:underline text-xs">
                                Lihat Bukti
                            </a>
                        @else
                            <span class="text-xs text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-center">
                        <form method="POST" action="{{ route('admin.pembayaran.verifikasi', $p) }}"
                            class="flex flex-col sm:flex-row gap-2 justify-center"
                            onsubmit="return confirm('Tandai pembayaran ini sebagai status terpilih?')">
                            @csrf
                            <select name="status" class="text-xs border rounded px-2 py-1 focus:ring-blue-500 w-full sm:w-auto">
                                <option value="paid" {{ old('status', 'paid') === 'paid' ? 'selected' : '' }}>Lunas (Paid)</option>
                                <option value="failed" {{ old('status', 'failed') === 'failed' ? 'selected' : '' }}>Gagal (Failed)</option>
                            </select>
                            <input type="text" name="catatan_admin" value="{{ old('catatan') }}"
                                placeholder="Catatan (opsional)"
                                class="text-xs border rounded px-2 py-1 focus:ring-blue-500 w-full sm:w-auto">
                            <button type="submit"
                                class="text-xs bg-green-600 text-white px-2 py-1 rounded hover:bg-green-700">
                                Simpan
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center py-8 text-gray-500">Tidak ada pembayaran yang menunggu verifikasi.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $pembayaran->links() }}</div>
@endsection
