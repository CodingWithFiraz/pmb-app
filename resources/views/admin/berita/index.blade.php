@extends('layouts.admin')
@section('title', 'Berita')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Berita</h1>
    <a href="{{ route('admin.berita.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-blue-700">+ Tambah Berita</a>
</div>

<form method="GET" class="bg-white shadow rounded-xl p-4 mb-6 flex flex-wrap gap-3 items-end">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul berita..."
        class="border rounded-lg px-3 py-2 text-sm flex-1 min-w-48 focus:ring-2 focus:ring-blue-500">

    <select name="status" class="border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
        <option value="">Semua Status</option>
        <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
        <option value="publish" {{ request('status') === 'publish' ? 'selected' : '' }}>Publish</option>
    </select>

    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">Filter</button>
    <a href="{{ route('admin.berita.index') }}" class="border border-gray-300 text-gray-600 px-4 py-2 rounded-lg text-sm hover:bg-gray-50">Reset</a>
</form>

<div class="bg-white shadow rounded-xl overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600">
            <tr>
                <th class="text-left px-4 py-3">Judul</th>
                <th class="text-left px-4 py-3">Penulis</th>
                <th class="text-left px-4 py-3">Tanggal Publish</th>
                <th class="text-left px-4 py-3">Status</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody>
            @forelse($berita as $b)
                <tr class="border-t">
                    <td class="px-4 py-3">
                        <div class="font-medium">{{ $b->judul }}</div>
                        <div class="text-xs text-gray-500">/{{ $b->slug }}</div>
                    </td>
                    <td class="px-4 py-3 text-gray-600">{{ $b->author }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $b->published_at?->translatedFormat('d M Y H:i') ?? '-' }}</td>
                    <td class="px-4 py-3">
                        @if($b->isTayang())
                            <span class="px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-700">Tayang</span>
                        @elseif($b->status === 'publish')
                            <span class="px-2 py-0.5 rounded text-xs font-medium bg-amber-100 text-amber-700">Terjadwal</span>
                        @else
                            <span class="px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600">Draft</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <a href="{{ route('admin.berita.edit', $b) }}" class="text-blue-600 hover:underline text-xs">Edit</a>

                            <form method="POST" action="{{ route('admin.berita.destroy', $b) }}"
                                onsubmit="return confirm('Hapus berita ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline text-xs">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center py-8 text-gray-500">Belum ada berita.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $berita->links() }}</div>
@endsection
