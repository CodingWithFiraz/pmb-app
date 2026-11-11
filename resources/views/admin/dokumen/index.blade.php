@extends('layouts.admin')
@section('title', 'Dokumen Persyaratan')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Dokumen Persyaratan</h1>
    <a href="{{ route('admin.dokumen.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-blue-700">+ Tambah Dokumen</a>
</div>

<div class="bg-white shadow rounded-xl overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600">
            <tr>
                <th class="text-left px-4 py-3">Kode</th>
                <th class="text-left px-4 py-3">Nama</th>
                <th class="text-left px-4 py-3">Tipe</th>
                <th class="text-left px-4 py-3">Max Size</th>
                <th class="text-left px-4 py-3">Wajib</th>
                <th class="text-left px-4 py-3">Jalur</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody>
            @forelse($dokumen as $d)
                <tr class="border-t">
                    <td class="px-4 py-3 font-mono font-bold">{{ $d->kode }}</td>
                    <td class="px-4 py-3 font-medium">{{ $d->nama }}</td>
                    <td class="px-4 py-3 text-gray-600 uppercase">{{ $d->tipe_file }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $d->max_size }} KB</td>
                    <td class="px-4 py-3">
                        @if($d->is_wajib)
                            <span class="px-2 py-0.5 rounded text-xs font-medium bg-red-50 text-red-700">Wajib</span>
                        @else
                            <span class="px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600">Opsional</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-gray-600">{{ $d->jalur_count }} jalur</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <a href="{{ route('admin.dokumen.mapping', $d) }}" class="text-blue-600 hover:underline text-xs">Mapping Jalur</a>
                            <a href="{{ route('admin.dokumen.edit', $d) }}" class="text-blue-600 hover:underline text-xs">Edit</a>

                            <form method="POST" action="{{ route('admin.dokumen.destroy', $d) }}"
                                onsubmit="return confirm('Hapus dokumen ini beserta mapping jalurnya?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline text-xs">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center py-8 text-gray-500">Belum ada dokumen.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $dokumen->links() }}</div>
@endsection
