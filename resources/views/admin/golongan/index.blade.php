@extends('layouts.admin')
@section('title', 'Golongan UKT')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Golongan UKT</h1>
    <a href="{{ route('admin.golongan-ukt.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-blue-700">+ Tambah Golongan</a>
</div>

<div class="bg-white shadow rounded-xl overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600">
            <tr>
                <th class="text-left px-4 py-3">Kode</th>
                <th class="text-left px-4 py-3">Nama</th>
                <th class="text-left px-4 py-3">Deskripsi</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody>
            @forelse($golongan as $g)
                <tr class="border-t">
                    <td class="px-4 py-3 font-mono font-bold">{{ $g->kode }}</td>
                    <td class="px-4 py-3 font-medium">{{ $g->nama }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $g->deskripsi ?? '-' }}</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <a href="{{ route('admin.golongan-ukt.edit', $g) }}" class="text-blue-600 hover:underline text-xs">Edit</a>

                            <form method="POST" action="{{ route('admin.golongan-ukt.destroy', $g) }}"
                                onsubmit="return confirm('Hapus golongan UKT ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline text-xs">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center py-8 text-gray-500">Belum ada golongan UKT.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $golongan->links() }}</div>
@endsection
