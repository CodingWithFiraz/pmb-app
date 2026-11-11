<div class="space-y-5">
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
        <div>
            <label for="nama_ortu" class="{{ $labelClass }}">Nama Orang Tua / Wali <span class="text-red-500">*</span></label>
            <input type="text" name="nama_ortu" id="nama_ortu"
                value="{{ old('nama_ortu', $profile?->nama_ortu) }}" class="{{ $inputClass }}">
            @error('nama_ortu')
                <p class="{{ $errorClass }}">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="pekerjaan_ortu" class="{{ $labelClass }}">Pekerjaan Orang Tua / Wali <span class="text-red-500">*</span></label>
            <input type="text" name="pekerjaan_ortu" id="pekerjaan_ortu"
                value="{{ old('pekerjaan_ortu', $profile?->pekerjaan_ortu) }}" class="{{ $inputClass }}">
            @error('pekerjaan_ortu')
                <p class="{{ $errorClass }}">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="sm:w-1/2">
        <label for="penghasilan_ortu" class="{{ $labelClass }}">Penghasilan Orang Tua / Bulan (Rp) <span class="text-red-500">*</span></label>
        <input type="number" name="penghasilan_ortu" id="penghasilan_ortu" min="0" step="100000"
            value="{{ old('penghasilan_ortu', $profile?->penghasilan_ortu) }}" class="{{ $inputClass }}"
            placeholder="Contoh: 4500000">
        <p class="mt-1 text-xs text-gray-500">Masukkan angka tanpa titik atau koma.</p>
        @error('penghasilan_ortu')
            <p class="{{ $errorClass }}">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <span class="{{ $labelClass }}">Golongan UKT <span class="text-red-500">*</span></span>
        <p class="mt-1 text-xs text-gray-500">
            Pilih golongan yang paling sesuai. Besaran UKT juga bergantung pada jalur masuk dan program studi.
        </p>

        <div class="mt-3 space-y-2">
            @foreach($golongan as $g)
                <label class="flex cursor-pointer items-start gap-3 rounded-lg border border-gray-200 px-4 py-3 hover:border-blue-400 hover:bg-blue-50/40">
                    <input type="radio" name="golongan_id" value="{{ $g->id }}" class="mt-1"
                        @checked((int) old('golongan_id', $profile?->golongan_id) === $g->id)>
                    <span>
                        <span class="block text-sm font-medium text-gray-800">{{ $g->nama }}</span>
                        @if($g->deskripsi)
                            <span class="block text-xs text-gray-500">{{ $g->deskripsi }}</span>
                        @endif
                    </span>
                </label>
            @endforeach
        </div>

        @error('golongan_id')
            <p class="{{ $errorClass }}">{{ $message }}</p>
        @enderror
    </div>
</div>
