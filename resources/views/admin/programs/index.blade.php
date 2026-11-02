@extends('layouts.app')
@section('title', 'Program Studi')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Program Studi</h1>
    <a href="{{ route('admin.program-studi.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-blue-700">+ Tambah</a>
</div>

<div class="bg-white shadow rounded-xl overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600">
            <tr>
                <th class="text-left px-4 py-3">Kode</th>
                <th class="text-left px-4 py-3">Nama</th>
                <th class="text-left px-4 py-3">Fakultas</th>
                <th class="text-left px-4 py-3">Kuota</th>
                <th class="text-left px-4 py-3">Status</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody>
            @forelse($programs as $p)
                <tr class="border-t">
                    <td class="px-4 py-3 font-mono font-bold">{{ $p->code }}</td>
                    <td class="px-4 py-3 font-medium">{{ $p->name }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $p->faculty }}</td>
                    <td class="px-4 py-3">{{ $p->quota ?? '∞' }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-0.5 rounded text-xs {{ $p->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ $p->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <a href="{{ route('admin.program-studi.edit', $p) }}" class="text-blue-600 hover:underline text-xs">Edit</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center py-8 text-gray-500">Belum ada program studi.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $programs->links() }}</div>
@endsection