@extends('layouts.app')
@section('title', 'Formulir Pendaftaran Mahasiswa Baru')
@section('body-class', 'bg-soft-gradient')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    {{-- Header --}}
    <div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-black tracking-tight">Formulir Pendaftaran Mahasiswa Baru</h1>
        <p class="text-xs sm:text-sm text-gray-700 mt-1">Periode: <strong class="text-black">{{ $application->admissionPeriod->name }}</strong></p>
    </div>

        {{-- Revision Notice if status is revision_required --}}
        @if($application->status === 'revision_required' && $application->admin_note)
            <div class="bg-orange-50 border border-orange-200 rounded-2xl p-5 text-sm text-orange-900 shadow-xs flex items-start gap-3">
                <svg class="w-5 h-5 text-orange-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.526ZM12 18.75h.007v.008H12v-.008Z" />
                </svg>
                <div>
                    <span class="font-bold">Perlu Perbaikan Data dari Admin:</span>
                    <p class="mt-0.5">{{ $application->admin_note }}</p>
                </div>
            </div>
        @endif

        {{-- Error Alert --}}
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-2xl text-sm shadow-xs">
                <div class="font-bold mb-1">Gagal menyimpan perubahan! Silakan periksa isian berikut:</div>
                <ul class="list-disc list-inside space-y-0.5 text-xs">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('applicant.application.update', $application) }}" class="space-y-6">
            @csrf
            @method('PUT')
            <input type="hidden" name="admission_period_id" value="{{ $application->admission_period_id }}">

            {{-- Program Studi --}}
            <div class="bg-[#F3F3FF] rounded-2xl p-6 sm:p-8 border border-[#E5EAFC] shadow-xs">
                <h2 class="font-bold text-lg text-black mb-4 pb-2 border-b border-[#E5EAFC]">
                    Pilihan Program Studi
                </h2>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                        Program Studi <span class="text-red-500">*</span>
                    </label>
                    <select name="study_program_id" 
                        class="w-full bg-[#F3F3FF] border border-[#DBDCFA] rounded-xl px-4 py-2.5 text-sm text-black focus:outline-none focus:ring-2 focus:ring-[#606ADF] focus:border-[#606ADF] transition" required>
                        @foreach($studyPrograms as $prodi)
                            <option value="{{ $prodi->id }}" {{ $application->study_program_id == $prodi->id ? 'selected' : '' }}>
                                {{ $prodi->name }} ({{ $prodi->faculty }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Data Pribadi --}}
            <div class="bg-[#F3F3FF] rounded-2xl p-6 sm:p-8 border border-[#E5EAFC] shadow-xs space-y-4">
                <h2 class="font-bold text-lg text-black mb-4 pb-2 border-b border-[#E5EAFC]">
                    Data Pribadi Calon Mahasiswa
                </h2>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="full_name" value="{{ old('full_name', $application->full_name) }}"
                        class="w-full bg-[#F3F3FF] border border-[#DBDCFA] rounded-xl px-4 py-2.5 text-sm text-black focus:outline-none focus:ring-2 focus:ring-[#606ADF] focus:border-[#606ADF] transition" required>
                    @error('full_name')<p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">NIK</label>
                        <input type="text" name="nik" value="{{ old('nik', $application->nik) }}" maxlength="16"
                            class="w-full bg-[#F3F3FF] border border-[#DBDCFA] rounded-xl px-4 py-2.5 text-sm text-black focus:outline-none focus:ring-2 focus:ring-[#606ADF] focus:border-[#606ADF] transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                            Jenis Kelamin <span class="text-red-500">*</span>
                        </label>
                        <select name="gender" class="w-full bg-[#F3F3FF] border border-[#DBDCFA] rounded-xl px-4 py-2.5 text-sm text-black focus:outline-none focus:ring-2 focus:ring-[#606ADF] focus:border-[#606ADF] transition" required>
                            <option value="male" {{ old('gender', $application->gender) == 'male' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="female" {{ old('gender', $application->gender) == 'female' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                            Tempat Lahir <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="birth_place" value="{{ old('birth_place', $application->birth_place) }}"
                            class="w-full bg-[#F3F3FF] border border-[#DBDCFA] rounded-xl px-4 py-2.5 text-sm text-black focus:outline-none focus:ring-2 focus:ring-[#606ADF] focus:border-[#606ADF] transition" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                            Tanggal Lahir <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="birth_date" value="{{ old('birth_date', $application->birth_date?->format('Y-m-d')) }}"
                            class="w-full bg-[#F3F3FF] border border-[#DBDCFA] rounded-xl px-4 py-2.5 text-sm text-black focus:outline-none focus:ring-2 focus:ring-[#606ADF] focus:border-[#606ADF] transition" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Agama</label>
                        <select name="religion" class="w-full bg-[#F3F3FF] border border-[#DBDCFA] rounded-xl px-4 py-2.5 text-sm text-black focus:outline-none focus:ring-2 focus:ring-[#606ADF] focus:border-[#606ADF] transition">
                            @foreach(['Islam','Kristen','Katolik','Hindu','Buddha','Konghucu','Lainnya'] as $r)
                                <option value="{{ $r }}" {{ old('religion', $application->religion) == $r ? 'selected' : '' }}>{{ $r }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                            Nomor HP <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="phone" value="{{ old('phone', $application->phone) }}"
                            class="w-full bg-[#F3F3FF] border border-[#DBDCFA] rounded-xl px-4 py-2.5 text-sm text-black focus:outline-none focus:ring-2 focus:ring-[#606ADF] focus:border-[#606ADF] transition" required>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                        Alamat Lengkap <span class="text-red-500">*</span>
                    </label>
                    <textarea name="address" rows="3"
                        class="w-full bg-[#F3F3FF] border border-[#DBDCFA] rounded-xl px-4 py-2.5 text-sm text-black focus:outline-none focus:ring-2 focus:ring-[#606ADF] focus:border-[#606ADF] transition" required>{{ old('address', $application->address) }}</textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                            Kota <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="city" value="{{ old('city', $application->city) }}"
                            class="w-full bg-[#F3F3FF] border border-[#DBDCFA] rounded-xl px-4 py-2.5 text-sm text-black focus:outline-none focus:ring-2 focus:ring-[#606ADF] focus:border-[#606ADF] transition" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                            Provinsi <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="province" value="{{ old('province', $application->province) }}"
                            class="w-full bg-[#F3F3FF] border border-[#DBDCFA] rounded-xl px-4 py-2.5 text-sm text-black focus:outline-none focus:ring-2 focus:ring-[#606ADF] focus:border-[#606ADF] transition" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Kode Pos</label>
                        <input type="text" name="postal_code" value="{{ old('postal_code', $application->postal_code) }}"
                            class="w-full bg-[#F3F3FF] border border-[#DBDCFA] rounded-xl px-4 py-2.5 text-sm text-black focus:outline-none focus:ring-2 focus:ring-[#606ADF] focus:border-[#606ADF] transition">
                    </div>
                </div>
            </div>

            {{-- Data Pendidikan --}}
            <div class="bg-[#F3F3FF] rounded-2xl p-6 sm:p-8 border border-[#E5EAFC] shadow-xs space-y-4">
                <h2 class="font-bold text-lg text-black mb-4 pb-2 border-b border-[#E5EAFC]">
                    Data Pendidikan Asal
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                            Nama Sekolah <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="school_name" value="{{ old('school_name', $application->school_name) }}"
                            class="w-full bg-[#F3F3FF] border border-[#DBDCFA] rounded-xl px-4 py-2.5 text-sm text-black focus:outline-none focus:ring-2 focus:ring-[#606ADF] focus:border-[#606ADF] transition" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                            Kota Sekolah <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="school_city" value="{{ old('school_city', $application->school_city) }}"
                            class="w-full bg-[#F3F3FF] border border-[#DBDCFA] rounded-xl px-4 py-2.5 text-sm text-black focus:outline-none focus:ring-2 focus:ring-[#606ADF] focus:border-[#606ADF] transition" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Jurusan</label>
                        <input type="text" name="major" value="{{ old('major', $application->major) }}"
                            class="w-full bg-[#F3F3FF] border border-[#DBDCFA] rounded-xl px-4 py-2.5 text-sm text-black focus:outline-none focus:ring-2 focus:ring-[#606ADF] focus:border-[#606ADF] transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                            Tahun Lulus <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="graduation_year" value="{{ old('graduation_year', $application->graduation_year) }}"
                            min="2000" max="{{ date('Y') + 1 }}"
                            class="w-full bg-[#F3F3FF] border border-[#DBDCFA] rounded-xl px-4 py-2.5 text-sm text-black focus:outline-none focus:ring-2 focus:ring-[#606ADF] focus:border-[#606ADF] transition" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Nilai Rata-rata</label>
                        <input type="number" name="gpa" value="{{ old('gpa', $application->gpa) }}" step="0.01" min="0" max="100"
                            class="w-full bg-[#F3F3FF] border border-[#DBDCFA] rounded-xl px-4 py-2.5 text-sm text-black focus:outline-none focus:ring-2 focus:ring-[#606ADF] focus:border-[#606ADF] transition">
                    </div>
                </div>
            </div>

            {{-- Submit Buttons --}}
            <div class="flex items-center gap-3 pt-2">
                <button type="submit" 
                    class="bg-[#606ADF] text-white px-8 py-3.5 rounded-xl font-semibold text-sm hover:bg-[#4F58CC] transition shadow-xs">
                    Simpan Perubahan Formulir
                </button>
                <a href="{{ route('applicant.dashboard') }}" 
                    class="bg-[#F3F3FF] border border-[#DBDCFA] text-[#818CA2] hover:text-black hover:bg-white px-6 py-3.5 rounded-xl font-semibold text-sm transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection
