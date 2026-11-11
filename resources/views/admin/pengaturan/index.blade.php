@extends('layouts.admin')
@section('title', 'Pengaturan Situs')

@section('content')
<div class="max-w-3xl mx-auto">
    <h1 class="text-2xl font-bold mb-2">Pengaturan Situs</h1>
    <p class="text-sm text-gray-600 mb-6">
        Nilai pengaturan ini dipakai pada halaman publik (alamat, kontak, peta, visi, misi, dan sambutan).
        Mengosongkan nilai berarti pengaturan tersebut tidak dipakai.
    </p>

    <div class="bg-white shadow rounded-xl p-6">
        <form method="POST" action="{{ route('admin.pengaturan.update') }}">
            @csrf

            <div class="space-y-5">
                @forelse($pengaturan as $p)
                    <div>
                        <label for="setting-{{ $p->key }}" class="block text-sm font-medium mb-1">
                            <span class="font-mono text-xs text-gray-500">{{ $p->key }}</span>
                        </label>
                        <textarea name="settings[{{ $p->key }}]" id="setting-{{ $p->key }}" rows="3"
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">{{ old('settings.'.$p->key, $p->value) }}</textarea>
                    </div>
                @empty
                    <p class="text-gray-500 text-sm">Belum ada pengaturan tersimpan.</p>
                @endforelse
            </div>

            <div class="mt-8 border-t border-gray-100 pt-5">
                <h2 class="font-semibold text-gray-700 mb-3">Tambah Pengaturan Baru</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="key_baru" class="block text-sm font-medium mb-1">Key</label>
                        <input type="text" name="key_baru" id="key_baru" value="{{ old('key_baru') }}"
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 @error('key_baru') border-red-500 @enderror"
                            placeholder="contoh: nama_kampus">
                        @error('key_baru')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="value_baru" class="block text-sm font-medium mb-1">Nilai</label>
                        <input type="text" name="value_baru" id="value_baru" value="{{ old('value_baru') }}"
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700">
                    Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
