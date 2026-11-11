<div class="space-y-5">
    <div>
        <label for="asal_sekolah" class="{{ $labelClass }}">Nama Sekolah Asal <span class="text-red-500">*</span></label>
        <input type="text" name="asal_sekolah" id="asal_sekolah"
            value="{{ old('asal_sekolah', $profile?->asal_sekolah) }}" class="{{ $inputClass }}"
            placeholder="Contoh: SMA Negeri 1 Jakarta">
        @error('asal_sekolah')
            <p class="{{ $errorClass }}">{{ $message }}</p>
        @enderror
    </div>

    <div class="sm:w-1/2">
        <label for="tahun_lulus" class="{{ $labelClass }}">Tahun Lulus <span class="text-red-500">*</span></label>
        <input type="number" name="tahun_lulus" id="tahun_lulus" min="1950" max="{{ now()->year }}"
            value="{{ old('tahun_lulus', $profile?->tahun_lulus) }}" class="{{ $inputClass }}"
            placeholder="{{ now()->year }}">
        @error('tahun_lulus')
            <p class="{{ $errorClass }}">{{ $message }}</p>
        @enderror
    </div>
</div>
