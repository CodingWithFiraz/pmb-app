@extends('layouts.pendaftar')
@section('title', 'Pilihan Jalur & Program Studi')

@section('content')
<div class="max-w-3xl mx-auto">

    @if($terkunci)
        <div class="mb-5 rounded-lg border border-amber-300 bg-amber-50 px-4 py-3 text-sm text-amber-800">
            Pilihan jalur dan program studi <strong>dikunci</strong> karena status pendaftaran sudah
            <strong>{{ $pendaftaran->statusLabel() }}</strong>. Hubungi panitia PMB jika perlu perubahan.
        </div>
    @endif

    <div class="bg-white shadow rounded-xl p-6">
        <div class="flex flex-wrap items-start justify-between gap-3">
            <div>
                <h2 class="text-lg font-bold text-gray-800">Pilihan Pendaftaran</h2>
                <p class="mt-1 text-sm text-gray-600">
                    Periode {{ $periode->nama }} (TA {{ $periode->tahun_akademik }}).
                </p>
            </div>

            @if($pendaftaran)
                <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $pendaftaran->isDraft() ? 'bg-blue-100 text-blue-700' : 'bg-amber-100 text-amber-700' }}">
                    {{ $pendaftaran->statusLabel() }}
                </span>
            @else
                <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">
                    Belum disimpan
                </span>
            @endif
        </div>

        <form id="form-pilihan" method="POST" action="{{ route('pendaftar.pilihan.update') }}" class="mt-6">
            @csrf

            <fieldset @disabled($terkunci) class="space-y-5">
                {{-- Jalur masuk --}}
                <div>
                    <label for="jalur_id" class="block text-sm font-medium text-gray-700">
                        Jalur Masuk <span class="text-red-500">*</span>
                    </label>
                    <select name="jalur_id" id="jalur_id"
                        class="mt-1 block w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="">-- Pilih jalur masuk --</option>
                        @foreach($jalur as $j)
                            <option value="{{ $j->id }}" @selected($selected['jalur_id'] === $j->id)>
                                {{ $j->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('jalur_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Prodi 1 --}}
                <div>
                    <label for="prodi_1_id" class="block text-sm font-medium text-gray-700">
                        Program Studi Pilihan 1 <span class="text-red-500">*</span>
                    </label>
                    <select name="prodi_1_id" id="prodi_1_id"
                        class="mt-1 block w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="">-- Pilih program studi --</option>
                        @foreach($prodi->groupBy(fn ($p) => $p->fakultas?->nama ?? 'Lainnya') as $namaFakultas => $daftar)
                            <optgroup label="{{ $namaFakultas }}">
                                @foreach($daftar as $p)
                                    <option value="{{ $p->id }}" @selected($selected['prodi_1_id'] === $p->id)>
                                        {{ $p->nama }} ({{ $p->jenjang }})
                                    </option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                    @error('prodi_1_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Prodi 2 --}}
                <div>
                    <label for="prodi_2_id" class="block text-sm font-medium text-gray-700">
                        Program Studi Pilihan 2 <span class="text-gray-400">(opsional)</span>
                    </label>
                    <select name="prodi_2_id" id="prodi_2_id"
                        class="mt-1 block w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="">-- Tidak memilih pilihan kedua --</option>
                        @foreach($prodi->groupBy(fn ($p) => $p->fakultas?->nama ?? 'Lainnya') as $namaFakultas => $daftar)
                            <optgroup label="{{ $namaFakultas }}">
                                @foreach($daftar as $p)
                                    <option value="{{ $p->id }}" @selected($selected['prodi_2_id'] === $p->id)>
                                        {{ $p->nama }} ({{ $p->jenjang }})
                                    </option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-gray-500">Boleh sama dengan pilihan pertama.</p>
                    @error('prodi_2_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Golongan UKT (otomatis dari penghasilan, tidak bisa diubah di sini) --}}
                <div>
                    <span class="block text-sm font-medium text-gray-700">Golongan UKT</span>
                    <input type="hidden" id="golongan_id" value="{{ $selected['golongan_id'] }}">
                    <div class="mt-1 rounded-lg border border-gray-200 bg-gray-50 px-4 py-3">
                        <p class="text-sm font-semibold text-gray-800">{{ $profile->golongan?->nama ?? '-' }}</p>
                        @if($profile->golongan?->deskripsi)
                            <p class="mt-0.5 text-xs text-gray-500">{{ $profile->golongan->deskripsi }}</p>
                        @endif
                    </div>
                    <p class="mt-1 text-xs text-gray-500">
                        Ditetapkan otomatis dari penghasilan orang tua. Ubah penghasilan di
                        <a href="{{ route('pendaftar.profil.step', 4) }}" class="text-blue-600 hover:underline">data orang tua</a>
                        jika perlu.
                    </p>
                </div>
            </fieldset>

            {{-- Preview UKT --}}
            <div class="mt-6 rounded-xl border border-blue-200 bg-blue-50 p-5">
                <div class="flex flex-wrap items-end justify-between gap-3">
                    <div>
                        <div class="text-sm text-blue-700">Perkiraan UKT per tahun</div>
                        <div id="ukt-preview" class="mt-1 text-2xl font-bold text-blue-900">
                            {{ $ukt !== null ? 'Rp '.number_format($ukt, 0, ',', '.') : 'Belum tersedia' }}
                        </div>
                    </div>
                    <div class="text-right text-sm text-blue-700">
                        Biaya pendaftaran:
                        <span id="biaya-preview" class="font-semibold text-blue-900">-</span>
                    </div>
                </div>
                <p class="mt-2 text-xs text-blue-700">
                    Dihitung dari jalur masuk, program studi pilihan pertama, dan golongan UKT.
                </p>
            </div>

            @if(! $terkunci)
                <div class="mt-6 flex flex-wrap items-center gap-3 border-t border-gray-100 pt-5">
                    <button type="submit"
                        class="rounded-lg bg-blue-600 px-5 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                        Simpan Pilihan
                    </button>
                    <a href="{{ route('pendaftar.dashboard') }}" class="text-sm text-gray-500 hover:underline">
                        Kembali ke dashboard
                    </a>
                </div>
            @endif
        </form>
    </div>
</div>

@if(! $terkunci)
    <script>
        (function () {
            const uktTarget = document.getElementById('ukt-preview');
            const biayaTarget = document.getElementById('biaya-preview');
            const endpoint = @json(route('pendaftar.pilihan.preview'));
            const fields = ['jalur_id', 'prodi_1_id', 'golongan_id'];

            if (! uktTarget || ! endpoint) {
                return;
            }

            async function refresh() {
                const params = new URLSearchParams();

                fields.forEach(function (id) {
                    const el = document.getElementById(id);
                    if (el && el.value) {
                        params.set(id, el.value);
                    }
                });

                uktTarget.textContent = 'Memuat...';

                try {
                    const response = await fetch(endpoint + '?' + params.toString(), {
                        headers: { 'Accept': 'application/json' },
                    });

                    if (! response.ok) {
                        throw new Error('Gagal memuat preview');
                    }

                    const data = await response.json();
                    uktTarget.textContent = data.formatted || 'Belum tersedia';

                    if (biayaTarget) {
                        biayaTarget.textContent = data.biaya_label || '-';
                    }
                } catch (error) {
                    uktTarget.textContent = 'Belum tersedia';
                }
            }

            fields.forEach(function (id) {
                const el = document.getElementById(id);
                if (el) {
                    el.addEventListener('change', refresh);
                }
            });

            refresh();
        })();
    </script>
@endif
@endsection
