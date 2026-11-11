@extends('layouts.admin')
@section('title', 'Periode PMB')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Periode PMB</h1>
    <a href="{{ route('admin.periode.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-blue-700">+ Tambah Periode</a>
</div>

<div class="bg-white shadow rounded-xl overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600">
            <tr>
                <th class="text-left px-4 py-3">Nama</th>
                <th class="text-left px-4 py-3">Tahun Akademik</th>
                <th class="text-left px-4 py-3">Mulai</th>
                <th class="text-left px-4 py-3">Selesai</th>
                <th class="text-left px-4 py-3">Status</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody>
            @forelse($periods as $period)
                <tr class="border-t">
                    <td class="px-4 py-3 font-medium">{{ $period->nama }}</td>
                    <td class="px-4 py-3">{{ $period->tahun_akademik }}</td>
                    <td class="px-4 py-3">{{ $period->tanggal_mulai->format('d/m/Y') }}</td>
                    <td class="px-4 py-3">{{ $period->tanggal_selesai->format('d/m/Y') }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-0.5 rounded text-xs font-medium {{ $period->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                            {{ $period->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                        <div class="mt-1 text-xs text-gray-400">{{ $period->pendaftaran_count }} pendaftar</div>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <a href="{{ route('admin.periode.edit', $period) }}" class="text-blue-600 hover:underline text-xs">Edit</a>

                            <form method="POST" action="{{ route('admin.periode.toggle', $period) }}">
                                @csrf
                                <button type="submit" class="text-blue-600 hover:underline text-xs">
                                    {{ $period->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>

                            <form method="POST" action="{{ route('admin.periode.destroy', $period) }}"
                                onsubmit="return confirm('Hapus periode ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline text-xs">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center py-8 text-gray-500">Belum ada periode.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $periods->links() }}</div>
@endsection
