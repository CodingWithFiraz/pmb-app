@extends('layouts.admin')
@section('title', 'Biaya Pendaftaran')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Biaya Pendaftaran</h1>
    <a href="{{ route('admin.biaya-pendaftaran.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-blue-700">+ Tambah Biaya</a>
</div>

<form method="GET" class="bg-white shadow rounded-xl p-4 mb-6 flex flex-wrap gap-3 items-end">
    <div>
        <label class="block text-sm font-medium mb-1">Periode</label>
        <select name="periode_id" class="border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
            <option value="">Semua Periode</option>
            @foreach($periodeList as $p)
                <option value="{{ $p->id }}" {{ (int) request('periode_id') === $p->id ? 'selected' : '' }}>
                    {{ $p->nama }}
                </option>
            @endforeach
        </select>
    </div>

    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">Filter</button>
</form>

<div class="bg-white shadow rounded-xl overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600">
            <tr>
                <th class="text-left px-4 py-3">Periode</th>
                <th class="text-left px-4 py-3">Jalur Masuk</th>
                <th class="text-left px-4 py-3">Nominal</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody>
            @forelse($biaya as $b)
                <tr class="border-t">
                    <td class="px-4 py-3">{{ $b->periode->nama }}</td>
                    <td class="px-4 py-3 font-medium">{{ $b->jalur->nama }}</td>
                    <td class="px-4 py-3">
                        @if($b->nominal === null)
                            <span class="text-green-600 font-semibold">Gratis</span>
                        @else
                            <span class="font-semibold">Rp {{ number_format($b->nominal, 0, ',', '.') }}</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <a href="{{ route('admin.biaya-pendaftaran.edit', $b) }}" class="text-blue-600 hover:underline text-xs">Edit</a>

                            <form method="POST" action="{{ route('admin.biaya-pendaftaran.destroy', $b) }}"
                                onsubmit="return confirm('Hapus biaya pendaftaran ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline text-xs">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center py-8 text-gray-500">Belum ada data biaya pendaftaran.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $biaya->links() }}</div>
@endsection
