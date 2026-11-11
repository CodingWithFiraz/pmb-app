@extends('layouts.app')
@section('title', 'Buat Pendaftaran')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold mb-2">Formulir Pendaftaran</h1>
    <p class="text-gray-600 mb-6">Periode: <strong>{{ $activePeriod->name }}</strong></p>

    @if ($errors->any())
        <div class="bg-red-50 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-6">
            <p class="font-bold">Gagal menyimpan data! Mohon periksa kembali isian formulir:</p>
            <ul class="list-disc list-inside text-sm mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('applicant.application.store') }}">
        @csrf
        <input type="hidden" name="admission_period_id" value="{{ $activePeriod->id }}">

        {{-- Program Studi --}}
        <div class="bg-white shadow rounded-xl p-6 mb-6">
            <h2 class="font-bold text-lg mb-4 text-blue-700">Pilihan Program Studi</h2>
            <div>
                <label class="block text-sm font-medium mb-1">Program Studi <span class="text-red-500">*</span></label>
                <select name="study_program_id" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 @error('study_program_id') border-red-500 @enderror" required>
                    <option value="">â€” Pilih Program Studi â€”</option>
                    @foreach($studyPrograms as $prodi)
                        <option value="{{ $prodi->id }}" {{ old('study_program_id') == $prodi->id ? 'selected' : '' }}>
                            {{ $prodi->name }} ({{ $prodi->faculty }})
                        </option>
                    @endforeach
                </select>
                @error('study_program_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- Data Pribadi --}}
        <div class="bg-white shadow rounded-xl p-6 mb-6">
            <h2 class="font-bold text-lg mb-4 text-blue-700">Data Pribadi</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="full_name" value="{{ old('full_name') }}"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 @error('full_name') border-red-500 @enderror" required>
                    @error('full_name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">NIK (Nomor KTP)</label>
                    <input type="text" name="nik" value="{{ old('nik') }}" maxlength="16"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Jenis Kelamin <span class="text-red-500">*</span></label>
                    <select name="gender" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500" required>
                        <option value="">â€” Pilih â€”</option>
                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('gender')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Tanggal Lahir <span class="text-red-500">*</span></label>
                    <input type="date" name="birth_date" value="{{ old('birth_date') }}"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 @error('birth_date') border-red-500 @enderror" required>
                    @error('birth_date')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Tempat Lahir <span class="text-red-500">*</span></label>
                    <input type="text" name="birth_place" value="{{ old('birth_place') }}"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 @error('birth_place') border-red-500 @enderror" required>
                    @error('birth_place')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Agama</label>
                    <select name="religion" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                        <option value="">â€” Pilih â€”</option>
                        @foreach(['Islam','Kristen','Katolik','Hindu','Buddha','Konghucu','Lainnya'] as $r)
                            <option value="{{ $r }}" {{ old('religion') == $r ? 'selected' : '' }}>{{ $r }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Nomor HP <span class="text-red-500">*</span></label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 @error('phone') border-red-500 @enderror" required>
                    @error('phone')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-medium mb-1">Alamat Lengkap <span class="text-red-500">*</span></label>
                <textarea name="address" rows="3"
                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 @error('address') border-red-500 @enderror" required>{{ old('address') }}</textarea>
                @error('address')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Kota <span class="text-red-500">*</span></label>
                    <input type="text" name="city" value="{{ old('city') }}"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 @error('city') border-red-500 @enderror" required>
                    @error('city')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Provinsi <span class="text-red-500">*</span></label>
                    <input type="text" name="province" value="{{ old('province') }}"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 @error('province') border-red-500 @enderror" required>
                    @error('province')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Kode Pos</label>
                    <input type="text" name="postal_code" value="{{ old('postal_code') }}"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
        </div>

        {{-- Data Pendidikan --}}
        <div class="bg-white shadow rounded-xl p-6 mb-6">
            <h2 class="font-bold text-lg mb-4 text-blue-700">Data Pendidikan</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-1">Nama Sekolah (SMA/SMK/MA) <span class="text-red-500">*</span></label>
                    <input type="text" name="school_name" value="{{ old('school_name') }}"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 @error('school_name') border-red-500 @enderror" required>
                    @error('school_name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Kota Sekolah <span class="text-red-500">*</span></label>
                    <input type="text" name="school_city" value="{{ old('school_city') }}"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 @error('school_city') border-red-500 @enderror" required>
                    @error('school_city')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Jurusan (opsional)</label>
                    <input type="text" name="major" value="{{ old('major') }}"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500" placeholder="cth: IPA, IPS, TKJ">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Tahun Lulus <span class="text-red-500">*</span></label>
                    <input type="number" name="graduation_year" value="{{ old('graduation_year', date('Y')) }}"
                        min="2000" max="{{ date('Y') + 1 }}"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 @error('graduation_year') border-red-500 @enderror" required>
                    @error('graduation_year')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Nilai Rata-rata Rapor</label>
                    <input type="number" name="gpa" value="{{ old('gpa') }}"
                        step="0.01" min="0" max="100"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500" placeholder="cth: 85.50">
                </div>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700">
                Simpan & Lanjut ke Dokumen
            </button>
            <a href="{{ route('applicant.dashboard') }}" class="border border-gray-300 text-gray-600 px-6 py-2 rounded-lg hover:bg-gray-50">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection