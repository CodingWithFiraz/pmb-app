@extends('layouts.admin')
@section('title', 'Jalur Masuk')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Jalur Masuk</h1>
    <a href="{{ route('admin.jalur-masuk.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-blue-700">+ Tambah Jalur</a>
</div>

<div class="bg-white shadow rounded-xl overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600">
            <tr>
                <th class="text-left px-4 py-3">Urutan</th>
                <th class="text-left px-4 py-3">Kode</th>
                <th class="text-left px-4 py-3">Nama</th>
                <th class="text-left px-4 py-3">Biaya Pendaftaran</th>
                <th class="text-left px-4 py-3">Dokumen</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody>
            @forelse($jalur as $j)
                <tr class="border-t">
                    <td class="px-4 py-3 text-gray-600">{{ $j->urutan }}</td>
                    <td class="px-4 py-3 font-mono font-bold">{{ $j->kode }}</td>
                    <td class="px-4 py-3 font-medium">{{ $j->nama }}</td>
                    <td class="px-4 py-3">
                        @if($j->punya_biaya_pendaftaran)
                            <span class="px-2 py-0.5 rounded text-xs font-medium bg-amber-100 text-amber-700">Berbayar</span>
                        @else
                            <span class="px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-700">Gratis</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-gray-600">{{ $j->dokumen_count }}</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <a href="{{ route('admin.jalur-masuk.edit', $j) }}" class="text-blue-600 hover:underline text-xs">Edit</a>

                            <form method="POST" action="{{ route('admin.jalur-masuk.destroy', $j) }}"
                                onsubmit="return confirm('Hapus jalur masuk ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline text-xs">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center py-8 text-gray-500">Belum ada jalur masuk.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $jalur->links() }}</div>
@endsection
