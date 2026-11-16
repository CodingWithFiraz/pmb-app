@extends('layouts.public')
@section('title', $prodi->nama)

@section('content')
<nav class="text-sm text-gray-500 mb-4">
    <a href="{{ route('home') }}" class="hover:text-blue-600">Beranda</a>
    <span class="mx-1">/</span>
    <a href="{{ route('public.fakultas') }}" class="hover:text-blue-600">Fakultas</a>
    <span class="mx-1">/</span>
    <a href="{{ route('public.fakultas.detail', $prodi->fakultas) }}" class="hover:text-blue-600">{{ $prodi->fakultas->nama }}</a>
    <span class="mx-1">/</span>
    <span class="text-gray-700">{{ $prodi->nama }}</span>
</nav>

<div class="bg-white shadow rounded-xl p-6 mb-6">
    <span class="text-xs font-mono font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded">{{ $prodi->kode }}</span>
    <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mt-2">{{ $prodi->nama }}</h1>
    <div class="text-gray-600 mt-1">{{ $prodi->jenjang }} · {{ $prodi->fakultas->nama }}</div>

    @if($prodi->deskripsi)
        <p class="text-gray-600 mt-4 max-w-3xl leading-relaxed">{{ $prodi->deskripsi }}</p>
    @endif

    <div class="mt-4 flex flex-wrap gap-4 text-sm">
        @if($prodi->kuota)
            <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded">Kuota: <strong>{{ $prodi->kuota }}</strong> mahasiswa</span>
        @endif
        <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded">Jenjang: <strong>{{ $prodi->jenjang }}</strong></span>
    </div>
</div>

{{-- Tarif UKT --}}
@if($tarif->isNotEmpty())
    <div class="bg-white shadow rounded-xl p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-800 mb-1">Biaya UKT</h2>
        <p class="text-sm text-gray-500 mb-4">
            {{ $periode->nama }} — besaran UKT untuk {{ $prodi->nama }} pada setiap jalur masuk dan golongan UKT.
        </p>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="text-left px-4 py-3">Jalur</th>
                        @foreach ($golongan as $g)
                            <th class="px-4 py-3">{{ $g->kode }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($tarif as $namaJalur => $rows)
                        <tr class="border-t">
                            <td class="px-4 py-3 font-medium">{{ $namaJalur }}</td>
                            @foreach ($golongan as $g)
                                @php $row = $rows->firstWhere('golongan.kode', $g->kode); @endphp
                                <td class="px-4 py-3">
                                    {{ $row ? 'Rp '.number_format($row->nominal, 0, ',', '.') : '-' }}
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <p class="text-xs text-gray-500 mt-3">Nominal dalam Rupiah (Rp) per tahun akademik.</p>
    </div>
@endif

{{-- Prodi lain di fakultas yang sama --}}
@if($prodiLain->isNotEmpty())
    <div class="bg-white shadow rounded-xl p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Program Studi Lain di {{ $prodi->fakultas->nama }}</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($prodiLain as $p)
                <a href="{{ route('public.prodi', $p) }}" class="block border border-gray-200 rounded-lg p-4 hover:border-blue-400 hover:bg-blue-50/40">
                    <div class="font-semibold text-gray-800">{{ $p->nama }}</div>
                    <div class="text-sm text-gray-500">{{ $p->jenjang }} · {{ $p->kode }}</div>
                </a>
            @endforeach
        </div>
    </div>
@endif
@endsection
