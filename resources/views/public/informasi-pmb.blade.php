@extends('layouts.public')
@section('title', 'Informasi PMB')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Informasi PMB</h1>
    <p class="text-gray-600 mt-1">Jalur masuk, biaya pendaftaran, dan tarif Uang Kuliah Tunggal (UKT).</p>
</div>

@if(! $periode)
    <div class="bg-yellow-50 border border-yellow-300 text-yellow-800 rounded-xl p-6">
        Saat ini belum ada periode pendaftaran yang aktif. Silakan cek halaman berita untuk informasi terbaru.
    </div>
@else
    {{-- Jadwal --}}
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mb-6">
        <h2 class="text-lg font-bold text-blue-900 mb-2">Jadwal Pendaftaran</h2>
        <div class="text-blue-800">{{ $periode->nama }}</div>
        <div class="text-sm text-blue-700 mt-1">Tahun Akademik {{ $periode->tahun_akademik }}</div>
        <div class="text-sm text-blue-700 mt-1">
            {{ $periode->tanggal_mulai->translatedFormat('d F Y') }} — {{ $periode->tanggal_selesai->translatedFormat('d F Y') }}
        </div>
    </div>

    {{-- Jalur masuk & biaya pendaftaran --}}
    <div class="bg-white shadow rounded-xl p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Jalur Masuk</h2>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="text-left px-4 py-3">Kode</th>
                        <th class="text-left px-4 py-3">Jalur</th>
                        <th class="text-left px-4 py-3">Deskripsi</th>
                        <th class="px-4 py-3">Biaya Pendaftaran</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($jalur as $j)
                        @php $b = $biaya->get($j->nama); @endphp
                        <tr class="border-t align-top">
                            <td class="px-4 py-3">
                                <span class="text-xs font-mono font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded">{{ $j->kode }}</span>
                            </td>
                            <td class="px-4 py-3 font-medium">{{ $j->nama }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $j->deskripsi }}</td>
                            <td class="px-4 py-3 font-semibold">
                                @if($b && $b->nominal !== null)
                                    Rp {{ number_format($b->nominal, 0, ',', '.') }}
                                @else
                                    <span class="text-green-600">Gratis</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Tarif UKT --}}
    <div class="bg-white shadow rounded-xl p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-800 mb-1">Biaya UKT</h2>
        <p class="text-sm text-gray-500 mb-4">
            Besaran UKT per tahun akademik untuk {{ $periode->nama }}, dikelompokkan per jalur masuk, program studi, dan golongan UKT.
        </p>

        @foreach($tarif as $namaJalur => $data)
            @if($data['prodi']->isNotEmpty())
                <div class="mb-6 last:mb-0">
                    <h3 class="font-semibold text-gray-700 mb-2">{{ $namaJalur }}</h3>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 text-gray-600">
                                <tr>
                                    <th class="text-left px-4 py-2">Program Studi</th>
                                    @foreach($golongan as $g)
                                        <th class="px-4 py-2">{{ $g->kode }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data['prodi'] as $p)
                                    <tr class="border-t">
                                        <td class="px-4 py-2 font-medium">{{ $p->nama }}</td>
                                        @foreach($golongan as $g)
                                            @php $row = $data['tarif'][$p->id][$g->id] ?? null; @endphp
                                            <td class="px-4 py-2">{{ $row ? 'Rp '.number_format($row->nominal, 0, ',', '.') : '-' }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        @endforeach

        <p class="text-xs text-gray-500 mt-4">
            Seluruh nominal dalam Rupiah (Rp) per tahun akademik.
        </p>
    </div>

    {{-- Program studi per fakultas --}}
    <div class="bg-white shadow rounded-xl p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Program Studi</h2>

        @foreach($prodiPerFakultas as $namaFakultas => $list)
            <div class="mb-4 last:mb-0">
                <h3 class="font-semibold text-gray-700 mb-2">{{ $namaFakultas }}</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($list as $p)
                        <a href="{{ route('public.prodi', $p) }}"
                            class="inline-block border border-gray-200 rounded-lg px-3 py-2 text-sm hover:border-blue-400 hover:bg-blue-50/40">
                            {{ $p->nama }} <span class="text-gray-400 text-xs">{{ $p->kode }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection
