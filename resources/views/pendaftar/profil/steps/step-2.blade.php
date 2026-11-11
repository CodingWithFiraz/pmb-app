<div class="space-y-5">
    <div>
        <label for="alamat" class="{{ $labelClass }}">Alamat Lengkap <span class="text-red-500">*</span></label>
        <textarea name="alamat" id="alamat" rows="3" class="{{ $inputClass }}"
            placeholder="Jalan, nomor rumah, RT/RW, kelurahan, kecamatan">{{ old('alamat', $profile?->alamat) }}</textarea>
        @error('alamat')
            <p class="{{ $errorClass }}">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
        <div>
            <label for="provinsi" class="{{ $labelClass }}">Provinsi <span class="text-red-500">*</span></label>
            <input type="text" name="provinsi" id="provinsi"
                value="{{ old('provinsi', $profile?->provinsi) }}" class="{{ $inputClass }}">
            @error('provinsi')
                <p class="{{ $errorClass }}">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="kota" class="{{ $labelClass }}">Kota / Kabupaten <span class="text-red-500">*</span></label>
            <input type="text" name="kota" id="kota"
                value="{{ old('kota', $profile?->kota) }}" class="{{ $inputClass }}">
            @error('kota')
                <p class="{{ $errorClass }}">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>
