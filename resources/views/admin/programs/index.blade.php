@extends('layouts.app')
@section('title', 'Program Studi')

@section('content')
<div class="flex flex-wrap justify-between items-center gap-4 mb-6">
    <div>
        <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-[#818CA2] mb-1">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-black transition">Dashboard</a>
            <span>/</span>
            <span class="text-[#5F4AF7]">Program Studi</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-black tracking-tight">Program Studi</h1>
    </div>
    <a href="{{ route('admin.program-studi.create') }}" 
       class="bg-[#5F4AF7] text-white px-5 py-2.5 rounded-xl font-semibold text-sm hover:bg-[#4E39E5] transition inline-flex items-center gap-2 shadow-xs">
        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
        </svg>
        <span>Tambah Program Studi</span>
    </a>
</div>

<div class="bg-white rounded-2xl border border-[#E5EAFC] shadow-xs overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-[#818CA2] text-xs uppercase tracking-wider border-b border-[#E5EAFC] bg-[#F2F5FF]/40">
                    <th class="px-6 py-3.5 font-semibold">Kode</th>
                    <th class="px-6 py-3.5 font-semibold">Nama Program Studi</th>
                    <th class="px-6 py-3.5 font-semibold">Fakultas</th>
                    <th class="px-6 py-3.5 font-semibold">Kuota</th>
                    <th class="px-6 py-3.5 font-semibold">Status</th>
                    <th class="px-6 py-3.5 text-right font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#E5EAFC]">
                @forelse($programs as $p)
                    <tr class="hover:bg-[#F2F5FF]/50 transition">
                        <td class="px-6 py-4">
                            <span class="font-mono text-xs font-bold text-[#5F4AF7] bg-[#F2F5FF] border border-[#5F4AF7]/20 px-2.5 py-1 rounded-lg">
                                {{ $p->code }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-semibold text-black">
                            {{ $p->name }}
                        </td>
                        <td class="px-6 py-4 text-gray-700">
                            {{ $p->faculty }}
                        </td>
                        <td class="px-6 py-4 font-medium text-black">
                            {{ $p->quota ? $p->quota . ' Mahasiswa' : 'Tidak Terbatas' }}
                        </td>
                        <td class="px-6 py-4">
                            @if($p->is_active)
                                <span class="px-3 py-1 rounded-lg text-xs font-semibold bg-[#F0FDF4] text-[#16A34A] border border-[#16A34A]/20">
                                    ✓ Aktif
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-lg text-xs font-semibold bg-gray-100 text-[#818CA2] border border-[#E5EAFC]">
                                    Nonaktif
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.program-studi.edit', $p) }}" 
                               class="inline-flex items-center text-xs font-semibold text-[#5F4AF7] hover:text-[#4E39E5] hover:underline">
                                Edit →
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-12 text-[#818CA2]">
                            <div class="w-12 h-12 rounded-xl bg-[#F2F5FF] text-[#5F4AF7] flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
                                </svg>
                            </div>
                            <p class="font-medium text-sm">Belum ada program studi yang ditambahkan.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6">{{ $programs->links() }}</div>
@endsection
