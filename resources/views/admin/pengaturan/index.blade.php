@extends('layouts.admin')
@section('title', 'Pengaturan Situs')

@section('content')
<div class="max-w-3xl mx-auto">
    <h1 class="font-heading text-2xl font-bold tracking-tight text-[#14141F] mb-2">Pengaturan Situs</h1>
    <p class="text-sm text-[#4E566E] mb-6">
        Nilai pengaturan ini dipakai pada halaman publik (alamat, kontak, peta, visi, misi, dan sambutan).
        Mengosongkan nilai berarti pengaturan tersebut tidak dipakai.
    </p>

    <div class="rounded-2xl border border-[#E6E1EC] bg-white p-6">
        <form method="POST" action="{{ route('admin.pengaturan.update') }}">
            @csrf

            <div class="space-y-5">
                @forelse($pengaturan as $p)
                    <div>
                        <label for="setting-{{ $p->key }}" class="block text-sm font-medium mb-1">
                            <span class="font-mono text-xs text-[#4E566E]">{{ $p->key }}</span>
                        </label>
                        <textarea name="settings[{{ $p->key }}]" id="setting-{{ $p->key }}" rows="3"
                            class="w-full border border-[#E6E1EC] rounded-lg bg-white text-[#14141F] px-3 py-2 text-sm focus:ring-2 focus:ring-[#BF8AFC]/40">{{ old('settings.'.$p->key, $p->value) }}</textarea>
                    </div>
                @empty
                    <p class="text-[#4E566E] text-sm">Belum ada pengaturan tersimpan.</p>
                @endforelse
            </div>

            <div class="mt-8 border-t border-[#EFEBF4] pt-5">
                <h2 class="font-semibold text-[#14141F] mb-3">Tambah Pengaturan Baru</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="key_baru" class="block text-sm font-medium mb-1">Key</label>
                        <input type="text" name="key_baru" id="key_baru" value="{{ old('key_baru') }}"
                            class="w-full border border-[#E6E1EC] rounded-lg bg-white text-[#14141F] px-3 py-2 text-sm focus:ring-2 focus:ring-[#BF8AFC]/40 @error('key_baru') border-red-500 @enderror"
                            placeholder="contoh: nama_kampus">
                        @error('key_baru')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="value_baru" class="block text-sm font-medium mb-1">Nilai</label>
                        <input type="text" name="value_baru" id="value_baru" value="{{ old('value_baru') }}"
                            class="w-full border border-[#E6E1EC] rounded-lg bg-white text-[#14141F] px-3 py-2 text-sm focus:ring-2 focus:ring-[#BF8AFC]/40">
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <button type="submit" class="bg-black text-white px-6 py-2 rounded-lg font-semibold hover:bg-[#14141F]/85">
                    Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
