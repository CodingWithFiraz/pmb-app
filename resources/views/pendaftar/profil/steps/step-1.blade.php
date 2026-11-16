<div class="space-y-5">
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
        <div>
            <label for="nik" class="{{ $labelClass }}">NIK <span class="text-red-500">*</span></label>
            <input type="text" name="nik" id="nik" inputmode="numeric" maxlength="16"
                value="{{ old('nik', $profile?->nik) }}" class="{{ $inputClass }}" placeholder="16 digit NIK">
            @error('nik')
                <p class="{{ $errorClass }}">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="nisn" class="{{ $labelClass }}">NISN <span class="text-red-500">*</span></label>
            <input type="text" name="nisn" id="nisn" inputmode="numeric" maxlength="10"
                value="{{ old('nisn', $profile?->nisn) }}" class="{{ $inputClass }}" placeholder="10 digit NISN">
            @error('nisn')
                <p class="{{ $errorClass }}">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
        <div>
            <label for="tempat_lahir" class="{{ $labelClass }}">Tempat Lahir <span class="text-red-500">*</span></label>
            <input type="text" name="tempat_lahir" id="tempat_lahir"
                value="{{ old('tempat_lahir', $profile?->tempat_lahir) }}" class="{{ $inputClass }}">
            @error('tempat_lahir')
                <p class="{{ $errorClass }}">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="tanggal_lahir" class="{{ $labelClass }}">Tanggal Lahir <span class="text-red-500">*</span></label>
            <input type="date" name="tanggal_lahir" id="tanggal_lahir"
                value="{{ old('tanggal_lahir', $profile?->tanggal_lahir?->format('Y-m-d')) }}" class="{{ $inputClass }}">
            @error('tanggal_lahir')
                <p class="{{ $errorClass }}">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div>
        <span class="{{ $labelClass }}">Jenis Kelamin <span class="text-red-500">*</span></span>

        <div class="mt-2 flex gap-6">
            <label class="flex items-center gap-2 text-sm text-gray-700">
                <input type="radio" name="jenis_kelamin" value="L"
                    @checked(old('jenis_kelamin', $profile?->jenis_kelamin) === 'L')>
                Laki-laki
            </label>
            <label class="flex items-center gap-2 text-sm text-gray-700">
                <input type="radio" name="jenis_kelamin" value="P"
                    @checked(old('jenis_kelamin', $profile?->jenis_kelamin) === 'P')>
                Perempuan
            </label>
        </div>

        @error('jenis_kelamin')
            <p class="{{ $errorClass }}">{{ $message }}</p>
        @enderror
    </div>
</div>
