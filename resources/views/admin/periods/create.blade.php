@extends('layouts.app')
@section('title', 'Tambah Periode')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    {{-- Header --}}
    <div class="flex flex-wrap justify-between items-center gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-[#818CA2] mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-black transition">Dashboard</a>
                <span>/</span>
                <a href="{{ route('admin.periode.index') }}" class="hover:text-black transition">Periode</a>
                <span>/</span>
                <span class="text-[#5F4AF7]">Tambah</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-black tracking-tight">Tambah Periode Penerimaan</h1>
        </div>
        <a href="{{ route('admin.periode.index') }}" 
           class="bg-white border border-[#E5EAFC] text-gray-700 hover:text-black hover:bg-[#F2F5FF] px-4 py-2 rounded-xl text-sm font-semibold transition inline-flex items-center gap-2">
            <svg class="w-4 h-4 text-[#818CA2]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
            <span>Kembali</span>
        </a>
    </div>

    {{-- Form Card --}}
    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-[#E5EAFC] shadow-xs">
        <form method="POST" action="{{ route('admin.periode.store') }}" class="space-y-5">
            @csrf
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                    Nama Periode <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name') }}"
                    class="w-full bg-[#F2F5FF]/50 border border-[#E5EAFC] rounded-xl px-4 py-2.5 text-sm text-black focus:outline-none focus:ring-2 focus:ring-[#5F4AF7] focus:border-[#5F4AF7] transition @error('name') border-red-500 @enderror"
                    placeholder="Contoh: PMB Reguler Gelombang 1 2026/2027" required>
                @error('name')<p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                    Tahun Akademik <span class="text-red-500">*</span>
                </label>
                <input type="text" name="academic_year" value="{{ old('academic_year') }}"
                    class="w-full bg-[#F2F5FF]/50 border border-[#E5EAFC] rounded-xl px-4 py-2.5 text-sm text-black focus:outline-none focus:ring-2 focus:ring-[#5F4AF7] focus:border-[#5F4AF7] transition"
                    placeholder="Contoh: 2026/2027" required>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                        Tanggal & Waktu Buka <span class="text-red-500">*</span>
                    </label>
                    <input type="datetime-local" name="starts_at" value="{{ old('starts_at') }}"
                        class="w-full bg-[#F2F5FF]/50 border border-[#E5EAFC] rounded-xl px-4 py-2.5 text-sm text-black focus:outline-none focus:ring-2 focus:ring-[#5F4AF7] focus:border-[#5F4AF7] transition" required>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                        Tanggal & Waktu Tutup <span class="text-red-500">*</span>
                    </label>
                    <input type="datetime-local" name="ends_at" value="{{ old('ends_at') }}"
                        class="w-full bg-[#F2F5FF]/50 border border-[#E5EAFC] rounded-xl px-4 py-2.5 text-sm text-black focus:outline-none focus:ring-2 focus:ring-[#5F4AF7] focus:border-[#5F4AF7] transition" required>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                    Biaya Pendaftaran (Rp)
                </label>
                <input type="number" name="registration_fee" value="{{ old('registration_fee', 0) }}" min="0"
                    class="w-full bg-[#F2F5FF]/50 border border-[#E5EAFC] rounded-xl px-4 py-2.5 text-sm text-black focus:outline-none focus:ring-2 focus:ring-[#5F4AF7] focus:border-[#5F4AF7] transition">
                <p class="text-xs text-[#818CA2] mt-1">Isi 0 jika pendaftaran gratis tanpa biaya.</p>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                    Status Penerimaan
                </label>
                <select name="status" class="w-full bg-[#F2F5FF]/50 border border-[#E5EAFC] rounded-xl px-4 py-2.5 text-sm text-black focus:outline-none focus:ring-2 focus:ring-[#5F4AF7] focus:border-[#5F4AF7] transition">
                    <option value="closed">Ditutup</option>
                    <option value="open">Dibuka</option>
                </select>
            </div>

            <div class="pt-4 border-t border-[#E5EAFC] flex items-center gap-3">
                <button type="submit" 
                    class="bg-[#5F4AF7] text-white px-7 py-3 rounded-xl font-semibold text-sm hover:bg-[#4E39E5] transition shadow-xs">
                    Simpan Periode
                </button>
                <a href="{{ route('admin.periode.index') }}" 
                    class="bg-white border border-[#E5EAFC] text-[#818CA2] hover:text-black hover:bg-[#F2F5FF] px-6 py-3 rounded-xl font-semibold text-sm transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
