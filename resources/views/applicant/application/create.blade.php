@extends('layouts.app')
@section('title', 'Formulir Pendaftaran Mahasiswa Baru')
@section('body-class', 'bg-soft-gradient')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    {{-- Page Heading --}}
    <div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-black tracking-tight">Formulir Pendaftaran Mahasiswa Baru</h1>
        <p class="text-xs sm:text-sm text-gray-700 mt-1">Periode: <strong class="text-black">{{ $activePeriod->name }}</strong></p>
    </div>

        {{-- Error Alert --}}
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-2xl text-sm shadow-xs">
                <div class="font-bold mb-1">Gagal menyimpan formulir! Silakan periksa kembali:</div>
                <ul class="list-disc list-inside space-y-0.5 text-xs">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('applicant.application.store') }}" class="space-y-6">
            @csrf
            <input type="hidden" name="admission_period_id" value="{{ $activePeriod->id }}">

            {{-- Section 1: Program Studi --}}
            <div class="bg-[#F3F3FF] rounded-2xl p-6 sm:p-8 border border-[#E5EAFC] shadow-xs">
                <h2 class="font-bold text-lg text-black mb-4 pb-2 border-b border-[#E5EAFC]">
                    Pilihan Program Studi
                </h2>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                        Program Studi Pilihan <span class="text-red-500">*</span>
                    </label>
                    <select name="study_program_id" 
                        class="w-full bg-[#F3F3FF] border border-[#DBDCFA] rounded-xl px-4 py-2.5 text-sm text-black focus:outline-none focus:ring-2 focus:ring-[#5F4AF7] focus:border-[#5F4AF7] transition @error('study_program_id') border-red-500 @enderror" required>
                        <option value="">— Pilih Program Studi —</option>
                        @foreach($studyPrograms as $prodi)
                            <option value="{{ $prodi->id }}" {{ old('study_program_id') == $prodi->id ? 'selected' : '' }}>
                                {{ $prodi->name }} ({{ $prodi->faculty }})
                            </option>
                        @endforeach
                    </select>
                    @error('study_program_id')<p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Section 2: Data Pribadi --}}
            <div class="bg-[#F3F3FF] rounded-2xl p-6 sm:p-8 border border-[#E5EAFC] shadow-xs space-y-4">
                <h2 class="font-bold text-lg text-black mb-4 pb-2 border-b border-[#E5EAFC]">
                    Data Pribadi Calon Mahasiswa
                </h2>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="full_name" value="{{ old('full_name', auth()->user()->name) }}"
                        class="w-full bg-[#F3F3FF] border border-[#DBDCFA] rounded-xl px-4 py-2.5 text-sm text-black focus:outline-none focus:ring-2 focus:ring-[#5F4AF7] focus:border-[#5F4AF7] transition @error('full_name') border-red-500 @enderror"
                        placeholder="Sesuai ijazah atau KTP" required>
                    @error('full_name')<p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                            NIK (16 Digit Angka)
                        </label>
                        <input type="text" name="nik" value="{{ old('nik') }}" maxlength="16"
                            class="w-full bg-[#F3F3FF] border border-[#DBDCFA] rounded-xl px-4 py-2.5 text-sm text-black focus:outline-none focus:ring-2 focus:ring-[#5F4AF7] focus:border-[#5F4AF7] transition"
                            placeholder="Contoh: 3201234567890001">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                            Jenis Kelamin <span class="text-red-500">*</span>
                        </label>
                        <select name="gender" class="w-full bg-[#F3F3FF] border border-[#DBDCFA] rounded-xl px-4 py-2.5 text-sm text-black focus:outline-none focus:ring-2 focus:ring-[#5F4AF7] focus:border-[#5F4AF7] transition" required>
                            <option value="">— Pilih —</option>
                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('gender')<p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                            Tempat Lahir <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="birth_place" value="{{ old('birth_place') }}"
                            class="w-full bg-[#F3F3FF] border border-[#DBDCFA] rounded-xl px-4 py-2.5 text-sm text-black focus:outline-none focus:ring-2 focus:ring-[#5F4AF7] focus:border-[#5F4AF7] transition"
                            placeholder="Kota tempat lahir" required>
                        @error('birth_place')<p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                            Tanggal Lahir <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="birth_date" value="{{ old('birth_date') }}"
                            class="w-full bg-[#F3F3FF] border border-[#DBDCFA] rounded-xl px-4 py-2.5 text-sm text-black focus:outline-none focus:ring-2 focus:ring-[#5F4AF7] focus:border-[#5F4AF7] transition" required>
                        @error('birth_date')<p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                            Agama
                        </label>
                        <select name="religion" class="w-full bg-[#F3F3FF] border border-[#DBDCFA] rounded-xl px-4 py-2.5 text-sm text-black focus:outline-none focus:ring-2 focus:ring-[#5F4AF7] focus:border-[#5F4AF7] transition">
                            <option value="">— Pilih Agama —</option>
                            @foreach(['Islam','Kristen','Katolik','Hindu','Buddha','Konghucu','Lainnya'] as $r)
                                <option value="{{ $r }}" {{ old('religion') == $r ? 'selected' : '' }}>{{ $r }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                            Nomor WhatsApp / HP <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="phone" value="{{ old('phone') }}"
                            class="w-full bg-[#F3F3FF] border border-[#DBDCFA] rounded-xl px-4 py-2.5 text-sm text-black focus:outline-none focus:ring-2 focus:ring-[#5F4AF7] focus:border-[#5F4AF7] transition"
                            placeholder="Contoh: 08123456789" required>
                        @error('phone')<p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                        Alamat Lengkap Rumah <span class="text-red-500">*</span>
                    </label>
                    <textarea name="address" rows="3"
                        class="w-full bg-[#F3F3FF] border border-[#DBDCFA] rounded-xl px-4 py-2.5 text-sm text-black focus:outline-none focus:ring-2 focus:ring-[#5F4AF7] focus:border-[#5F4AF7] transition"
                        placeholder="Nama jalan, nomor rumah, RT/RW, kelurahan, kecamatan" required>{{ old('address') }}</textarea>
                    @error('address')<p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                            Kota / Kabupaten <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="city" value="{{ old('city') }}"
                            class="w-full bg-[#F3F3FF] border border-[#DBDCFA] rounded-xl px-4 py-2.5 text-sm text-black focus:outline-none focus:ring-2 focus:ring-[#5F4AF7] focus:border-[#5F4AF7] transition" required>
                        @error('city')<p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                            Provinsi <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="province" value="{{ old('province') }}"
                            class="w-full bg-[#F3F3FF] border border-[#DBDCFA] rounded-xl px-4 py-2.5 text-sm text-black focus:outline-none focus:ring-2 focus:ring-[#5F4AF7] focus:border-[#5F4AF7] transition" required>
                        @error('province')<p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                            Kode Pos
                        </label>
                        <input type="text" name="postal_code" value="{{ old('postal_code') }}"
                            class="w-full bg-[#F3F3FF] border border-[#DBDCFA] rounded-xl px-4 py-2.5 text-sm text-black focus:outline-none focus:ring-2 focus:ring-[#5F4AF7] focus:border-[#5F4AF7] transition">
                    </div>
                </div>
            </div>

            {{-- Section 3: Data Pendidikan --}}
            <div class="bg-[#F3F3FF] rounded-2xl p-6 sm:p-8 border border-[#E5EAFC] shadow-xs space-y-4">
                <h2 class="font-bold text-lg text-black mb-4 pb-2 border-b border-[#E5EAFC]">
                    Data Pendidikan Asal
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                            Nama Sekolah (SMA / SMK / MA) <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="school_name" value="{{ old('school_name') }}"
                            class="w-full bg-[#F3F3FF] border border-[#DBDCFA] rounded-xl px-4 py-2.5 text-sm text-black focus:outline-none focus:ring-2 focus:ring-[#5F4AF7] focus:border-[#5F4AF7] transition"
                            placeholder="Contoh: SMAN 1 Jakarta" required>
                        @error('school_name')<p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                            Kota Sekolah <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="school_city" value="{{ old('school_city') }}"
                            class="w-full bg-[#F3F3FF] border border-[#DBDCFA] rounded-xl px-4 py-2.5 text-sm text-black focus:outline-none focus:ring-2 focus:ring-[#5F4AF7] focus:border-[#5F4AF7] transition" required>
                        @error('school_city')<p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                            Jurusan Asal Sekolah (opsional)
                        </label>
                        <input type="text" name="major" value="{{ old('major') }}"
                            class="w-full bg-[#F3F3FF] border border-[#DBDCFA] rounded-xl px-4 py-2.5 text-sm text-black focus:outline-none focus:ring-2 focus:ring-[#5F4AF7] focus:border-[#5F4AF7] transition"
                            placeholder="Contoh: IPA, IPS, RPL, TKJ">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                            Tahun Kelulusan <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="graduation_year" value="{{ old('graduation_year', date('Y')) }}"
                            min="2000" max="{{ date('Y') + 1 }}"
                            class="w-full bg-[#F3F3FF] border border-[#DBDCFA] rounded-xl px-4 py-2.5 text-sm text-black focus:outline-none focus:ring-2 focus:ring-[#5F4AF7] focus:border-[#5F4AF7] transition" required>
                        @error('graduation_year')<p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                            Nilai Rata-rata Rapor / Ijazah
                        </label>
                        <input type="number" name="gpa" value="{{ old('gpa') }}"
                            step="0.01" min="0" max="100"
                            class="w-full bg-[#F3F3FF] border border-[#DBDCFA] rounded-xl px-4 py-2.5 text-sm text-black focus:outline-none focus:ring-2 focus:ring-[#5F4AF7] focus:border-[#5F4AF7] transition"
                            placeholder="Contoh: 88.50">
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center gap-3 pt-2">
                <button type="submit" 
                    class="bg-[#5F4AF7] text-white px-8 py-3.5 rounded-xl font-semibold text-sm hover:bg-[#4E39E5] transition shadow-xs inline-flex items-center gap-2">
                    <span>Simpan & Lanjut ke Dokumen</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                    </svg>
                </button>
                <a href="{{ route('applicant.dashboard') }}" 
                    class="bg-[#F3F3FF] border border-[#DBDCFA] text-[#818CA2] hover:text-black hover:bg-white px-6 py-3.5 rounded-xl font-semibold text-sm transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection
