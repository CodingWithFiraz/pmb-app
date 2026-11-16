@extends('layouts.admin')
@section('title', 'Fakultas')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Fakultas</h1>
    <a href="{{ route('admin.fakultas.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-blue-700">+ Tambah Fakultas</a>
</div>

<div class="bg-white shadow rounded-xl overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600">
            <tr>
                <th class="text-left px-4 py-3">Kode</th>
                <th class="text-left px-4 py-3">Nama</th>
                <th class="text-left px-4 py-3">Program Studi</th>
                <th class="text-left px-4 py-3">Slug</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody>
            @forelse($fakultas as $f)
                <tr class="border-t">
                    <td class="px-4 py-3 font-mono font-bold">{{ $f->kode }}</td>
                    <td class="px-4 py-3 font-medium">{{ $f->nama }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $f->prodi_count }}</td>
                    <td class="px-4 py-3 text-gray-500 text-xs">{{ $f->slug }}</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <a href="{{ route('admin.fakultas.edit', $f) }}" class="text-blue-600 hover:underline text-xs">Edit</a>

                            <form method="POST" action="{{ route('admin.fakultas.destroy', $f) }}"
                                onsubmit="return confirm('Hapus fakultas ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline text-xs">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center py-8 text-gray-500">Belum ada fakultas.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $fakultas->links() }}</div>
@endsection
