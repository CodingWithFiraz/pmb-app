@extends('layouts.admin')
@section('title', 'Tarif UKT')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Matrix Tarif UKT</h1>
    <p class="text-sm text-gray-600">Nominal UKT per jalur, program studi, dan golongan.</p>
</div>

<form method="GET" class="bg-white shadow rounded-xl p-4 mb-6 flex flex-wrap gap-3 items-end">
    <div>
        <label class="block text-sm font-medium mb-1">Periode</label>
        <select name="periode_id" class="border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
            @foreach($periodeList as $p)
                <option value="{{ $p->id }}" {{ $periode && $periode->id === $p->id ? 'selected' : '' }}>
                    {{ $p->nama }}
                </option>
            @endforeach
        </select>
    </div>

    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">Tampilkan</button>
</form>

@if($periode)
    <form method="POST" action="{{ route('admin.tarif-ukt.update') }}">
        @csrf
        <input type="hidden" name="periode_id" value="{{ $periode->id }}">

        <div class="space-y-6">
            @forelse($jalur as $j)
                <div class="bg-white shadow rounded-xl p-5">
                    <h2 class="font-bold mb-3">
                        {{ $j->nama }} <span class="text-xs font-mono text-gray-500">{{ $j->kode }}</span>
                    </h2>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 text-gray-600">
                                <tr>
                                    <th class="text-left px-3 py-2">Program Studi</th>
                                    @foreach($golongan as $g)
                                        <th class="px-3 py-2">{{ $g->kode }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($prodi as $p)
                                    <tr class="border-t">
                                        <td class="px-3 py-2">
                                            {{ $p->nama }}
                                            <span class="text-xs text-gray-400">{{ $p->kode }}</span>
                                        </td>

                                        @foreach($golongan as $g)
                                            @php
                                                $key = $j->id.'-'.$p->id.'-'.$g->id;
                                                $nominal = $tarif[$key]->nominal ?? null;
                                            @endphp
                                            <td class="px-3 py-1">
                                                <input type="number" name="tarif[{{ $key }}]" min="0" step="1000"
                                                    value="{{ old('tarif.'.$key, $nominal) }}"
                                                    class="w-full border rounded px-2 py-1 text-right text-sm focus:ring-2 focus:ring-blue-500">
                                            </td>
                                        @endforeach
                                    </tr>
                                @empty
                                    <tr><td colspan="10" class="px-3 py-4 text-gray-500">Belum ada program studi.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @empty
                <p class="text-gray-500">Belum ada jalur masuk.</p>
            @endforelse
        </div>

        <div class="mt-6 flex justify-end">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700">
                Simpan Tarif UKT
            </button>
        </div>
    </form>
@else
    <p class="text-gray-500">Belum ada periode pendaftaran.</p>
@endif
@endsection
