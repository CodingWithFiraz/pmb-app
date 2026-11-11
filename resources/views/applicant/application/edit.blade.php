@extends('layouts.app')
@section('title', 'Edit Pendaftaran')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold mb-2">Edit Pendaftaran</h1>
    <p class="text-gray-600 mb-6">Periode: <strong>{{ $application->admissionPeriod->name }}</strong></p>

    @if($application->status === 'revision_required' && $application->admin_note)
        <div class="bg-orange-50 border border-orange-300 rounded-xl p-4 mb-6">
            <div class="font-semibold text-orange-800">âš ï¸ Revisi Diminta</div>
            <p class="text-orange-700 mt-1">{{ $application->admin_note }}</p>
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-50 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-6">
            <p class="font-bold">Gagal menyimpan perubahan! Mohon periksa kembali isian formulir:</p>
            <ul class="list-disc list-inside text-sm mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('applicant.application.update', $application) }}">
        @csrf
        @method('PUT')
        <input type="hidden" name="admission_period_id" value="{{ $application->admission_period_id }}">

        {{-- Program Studi --}}
        <div class="bg-white shadow rounded-xl p-6 mb-6">
            <h2 class="font-bold text-lg mb-4 text-blue-700">Pilihan Program Studi</h2>
            <div>
                <label class="block text-sm font-medium mb-1">Program Studi <span class="text-red-500">*</span></label>
                <select name="study_program_id" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500" required>
                    @foreach($studyPrograms as $prodi)
                        <option value="{{ $prodi->id }}" {{ $application->study_program_id == $prodi->id ? 'selected' : '' }}>
                            {{ $prodi->name }} ({{ $prodi->faculty }})
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Data Pribadi --}}
        <div class="bg-white shadow rounded-xl p-6 mb-6">
            <h2 class="font-bold text-lg mb-4 text-blue-700">Data Pribadi</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="full_name" value="{{ old('full_name', $application->full_name) }}"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500" required>
                    @error('full_name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">NIK</label>
                    <input type="text" name="nik" value="{{ old('nik', $application->nik) }}"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Jenis Kelamin <span class="text-red-500">*</span></label>
                    <select name="gender" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500" required>
                        <option value="male" {{ $application->gender == 'male' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="female" {{ $application->gender == 'female' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Tanggal Lahir <span class="text-red-500">*</span></label>
                    <input type="date" name="birth_date" value="{{ old('birth_date', $application->birth_date->format('Y-m-d')) }}"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Tempat Lahir <span class="text-red-500">*</span></label>
                    <input type="text" name="birth_place" value="{{ old('birth_place', $application->birth_place) }}"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Agama</label>
                    <select name="religion" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                        <option value="">â€” Pilih â€”</option>
                        @foreach(['Islam','Kristen','Katolik','Hindu','Buddha','Konghucu','Lainnya'] as $r)
                            <option value="{{ $r }}" {{ $application->religion == $r ? 'selected' : '' }}>{{ $r }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Nomor HP <span class="text-red-500">*</span></label>
                    <input type="text" name="phone" value="{{ old('phone', $application->phone) }}"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500" required>
                </div>
            </div>
            <div class="mt-4">
                <label class="block text-sm font-medium mb-1">Alamat <span class="text-red-500">*</span></label>
                <textarea name="address" rows="3"
                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500" required>{{ old('address', $application->address) }}</textarea>
            </div>
            <div class="grid grid-cols-3 gap-4 mt-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Kota <span class="text-red-500">*</span></label>
                    <input type="text" name="city" value="{{ old('city', $application->city) }}"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Provinsi <span class="text-red-500">*</span></label>
                    <input type="text" name="province" value="{{ old('province', $application->province) }}"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Kode Pos</label>
                    <input type="text" name="postal_code" value="{{ old('postal_code', $application->postal_code) }}"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
        </div>

        {{-- Data Pendidikan --}}
        <div class="bg-white shadow rounded-xl p-6 mb-6">
            <h2 class="font-bold text-lg mb-4 text-blue-700">Data Pendidikan</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-1">Nama Sekolah <span class="text-red-500">*</span></label>
                    <input type="text" name="school_name" value="{{ old('school_name', $application->school_name) }}"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Kota Sekolah <span class="text-red-500">*</span></label>
                    <input type="text" name="school_city" value="{{ old('school_city', $application->school_city) }}"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Jurusan</label>
                    <input type="text" name="major" value="{{ old('major', $application->major) }}"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Tahun Lulus <span class="text-red-500">*</span></label>
                    <input type="number" name="graduation_year" value="{{ old('graduation_year', $application->graduation_year) }}"
                        min="2000" max="{{ date('Y') + 1 }}"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Nilai Rata-rata</label>
                    <input type="number" name="gpa" value="{{ old('gpa', $application->gpa) }}"
                        step="0.01" min="0" max="100"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700">
                Simpan Perubahan
            </button>
            <a href="{{ route('applicant.dashboard') }}" class="border border-gray-300 text-gray-600 px-6 py-2 rounded-lg hover:bg-gray-50">Kembali</a>
        </div>
    </form>
</div>
@endsection