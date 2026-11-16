@extends('layouts.pendaftar')
@section('title', 'Profil Pendaftar')

@section('content')
<div class="max-w-3xl mx-auto">

    {{-- Indikator langkah --}}
    <div class="bg-white shadow rounded-xl p-6 mb-6">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-lg font-bold text-gray-800">Lengkapi Profil Pendaftar</h2>
                <p class="text-sm text-gray-600">Data disimpan sebagai draft dan dapat dilengkapi bertahap.</p>
            </div>
            <span class="rounded-full bg-blue-50 px-3 py-1 text-sm font-semibold text-blue-700">
                {{ $profile?->completionPercentage() ?? 0 }}% lengkap
            </span>
        </div>

        <ol class="mt-5 grid grid-cols-2 gap-2 md:grid-cols-4">
            @foreach($steps as $nomor => $info)
                @php
                    $aktif = $step === $nomor;
                    $selesai = in_array($nomor, $completed, true);
                @endphp
                <li>
                    <a href="{{ route('pendaftar.profil.step', $nomor) }}"
                        class="block rounded-lg border px-3 py-2 text-sm transition
                            {{ $aktif
                                ? 'border-blue-600 bg-blue-50 font-semibold text-blue-700'
                                : ($selesai
                                    ? 'border-green-300 bg-green-50 text-green-700'
                                    : 'border-gray-200 text-gray-600 hover:border-blue-300') }}">
                        <span class="block text-xs opacity-75">Langkah {{ $nomor }}</span>
                        {{ $info['judul'] }}
                    </a>
                </li>
            @endforeach
        </ol>
    </div>

    {{-- Form langkah aktif --}}
    <div class="bg-white shadow rounded-xl p-6">
        <h3 class="text-lg font-bold text-gray-800">Langkah {{ $step }}: {{ $steps[$step]['judul'] }}</h3>
        <p class="mt-1 text-sm text-gray-500 mb-5">
            @switch($step)
                @case(1)
                    Isi data identitas sesuai dokumen resmi (KTP/Kartu Keluarga dan ijazah).
                    @break
                @case(2)
                    Isi alamat tempat tinggal yang dapat dihubungi saat proses verifikasi.
                    @break
                @case(3)
                    Isi data sekolah asal dan tahun kelulusan.
                    @break
                @case(4)
                    Isi data orang tua/wali. Golongan UKT ditentukan otomatis dari penghasilan.
                    @break
            @endswitch
        </p>

        <form method="POST" action="{{ route('pendaftar.profil.step.update', $step) }}">
            @csrf

            @php
                $inputClass = 'mt-1 block w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500';
                $labelClass = 'block text-sm font-medium text-gray-700';
                $errorClass = 'mt-1 text-sm text-red-600';
            @endphp

            @include('pendaftar.profil.steps.step-'.$step)

            <div class="mt-6 flex flex-wrap items-center gap-3 border-t border-gray-100 pt-5">
                @if($step > 1)
                    <a href="{{ route('pendaftar.profil.step', $step - 1) }}"
                        class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Kembali
                    </a>
                @endif

                <button type="submit"
                    class="rounded-lg bg-blue-600 px-5 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                    {{ $step < 4 ? 'Simpan & Lanjut' : 'Simpan & Pilih Jalur' }}
                </button>

                <a href="{{ route('pendaftar.dashboard') }}" class="text-sm text-gray-500 hover:underline">
                    Kembali ke dashboard
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
