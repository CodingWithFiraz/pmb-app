@extends('layouts.admin')
@section('title', 'FAQ')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Pertanyaan Umum</h1>
    <a href="{{ route('admin.faq.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-blue-700">+ Tambah Pertanyaan</a>
</div>

<form method="GET" class="bg-white shadow rounded-xl p-4 mb-6 flex flex-wrap gap-3 items-end">
    <select name="kategori" class="border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
        <option value="">Semua Kategori</option>
        @foreach($kategori as $k)
            <option value="{{ $k }}" {{ request('kategori') === $k ? 'selected' : '' }}>{{ $k }}</option>
        @endforeach
    </select>

    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">Filter</button>
    <a href="{{ route('admin.faq.index') }}" class="border border-gray-300 text-gray-600 px-4 py-2 rounded-lg text-sm hover:bg-gray-50">Reset</a>
</form>

<div class="bg-white shadow rounded-xl overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600">
            <tr>
                <th class="text-left px-4 py-3">Urutan</th>
                <th class="text-left px-4 py-3">Kategori</th>
                <th class="text-left px-4 py-3">Pertanyaan</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody>
            @forelse($faq as $f)
                <tr class="border-t">
                    <td class="px-4 py-3 text-gray-600">{{ $f->urutan }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-700">{{ $f->kategori }}</span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="font-medium">{{ $f->pertanyaan }}</div>
                        <div class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $f->jawaban }}</div>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <a href="{{ route('admin.faq.edit', $f) }}" class="text-blue-600 hover:underline text-xs">Edit</a>

                            <form method="POST" action="{{ route('admin.faq.destroy', $f) }}"
                                onsubmit="return confirm('Hapus pertanyaan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline text-xs">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center py-8 text-gray-500">Belum ada pertanyaan.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $faq->links() }}</div>
@endsection
