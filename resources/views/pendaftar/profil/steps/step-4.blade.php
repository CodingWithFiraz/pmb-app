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
        <p class="mt-1 text-xs text-gray-500">Masukkan angka tanpa titik atau koma. Golongan UKT terisi otomatis.</p>
        @error('penghasilan_ortu')
            <p class="{{ $errorClass }}">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <span class="{{ $labelClass }}">Golongan UKT</span>
        <p class="mt-1 text-xs text-gray-500">
            Ditentukan otomatis dari penghasilan orang tua. Besaran UKT juga bergantung pada jalur masuk dan program studi.
        </p>

        @php
            $penghasilanAwal = (int) old('penghasilan_ortu', $profile?->penghasilan_ortu);
            $kodeAktif = filled(old('penghasilan_ortu', $profile?->penghasilan_ortu))
                ? \App\Models\GolonganUkt::kodeDariPenghasilan($penghasilanAwal)
                : null;
            $golonganAktif = $kodeAktif ? $golongan->firstWhere('kode', $kodeAktif) : null;
        @endphp

        {{-- Kartu Hasil Penetapan Golongan UKT Otomatis --}}
        <div id="ukt-hasil" class="mt-3 rounded-lg border border-blue-200 bg-blue-50/80 p-4 transition-all duration-200 {{ $kodeAktif ? '' : 'hidden' }}">
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0 text-blue-600 mt-0.5">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-blue-700">Golongan UKT yang Ditetapkan</p>
                    <p id="ukt-hasil-nama" class="mt-0.5 text-lg font-bold text-blue-950">
                        {{ $golonganAktif?->nama ?? ($kodeAktif ? 'Golongan '.$kodeAktif : '') }}
                    </p>
                    <p id="ukt-hasil-deskripsi" class="mt-1 text-xs text-blue-800">
                        {{ $golonganAktif?->deskripsi ?? '' }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Pesan jika belum mengisi nominal penghasilan --}}
        <div id="ukt-placeholder" class="mt-3 rounded-lg border border-dashed border-gray-300 bg-gray-50 p-4 text-sm text-gray-500 {{ $kodeAktif ? 'hidden' : '' }}">
            <div class="flex items-center gap-2.5">
                <svg class="h-5 w-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Golongan UKT akan muncul otomatis di sini setelah nominal penghasilan orang tua diisi.</span>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        const input = document.getElementById('penghasilan_ortu');
        const hasil = document.getElementById('ukt-hasil');
        const placeholder = document.getElementById('ukt-placeholder');
        const hasilNama = document.getElementById('ukt-hasil-nama');
        const hasilDeskripsi = document.getElementById('ukt-hasil-deskripsi');
        const rentang = @json($rentangUkt ?? []);
        const golongan = @json($golonganJs ?? []);

        function kodeDariPenghasilan(nominal) {
            for (const item of rentang) {
                if (nominal < item.min) {
                    continue;
                }
                if (item.max !== null && nominal > item.max) {
                    continue;
                }
                return item.kode;
            }
            return 'V';
        }

        function formatRupiah(angka) {
            return 'Rp' + new Intl.NumberFormat('id-ID').format(angka);
        }

        function perbarui() {
            if (! input) {
                return;
            }

            const raw = input.value.trim();
            if (raw === '' || Number.isNaN(Number(raw)) || Number(raw) < 0) {
                hasil?.classList.add('hidden');
                placeholder?.classList.remove('hidden');
                return;
            }

            const nominal = Number(raw);
            const kode = kodeDariPenghasilan(nominal);

            // Cari data golongan yang cocok dari database
            const data = golongan.find(function (g) {
                return String(g.kode).toUpperCase() === String(kode).toUpperCase();
            }) || golongan.find(function (g) {
                return g.nama && g.nama.toUpperCase().endsWith(' ' + String(kode).toUpperCase());
            });

            if (hasilNama) {
                hasilNama.textContent = data ? data.nama : ('Golongan ' + kode);
            }

            if (hasilDeskripsi) {
                if (data && data.deskripsi) {
                    hasilDeskripsi.textContent = data.deskripsi;
                } else {
                    const r = rentang.find(function (item) { return item.kode === kode; });
                    if (r) {
                        if (r.max !== null) {
                            hasilDeskripsi.textContent = 'Penghasilan orang tua ' + formatRupiah(r.min) + ' – ' + formatRupiah(r.max) + ' per bulan.';
                        } else {
                            hasilDeskripsi.textContent = 'Penghasilan orang tua ≥ ' + formatRupiah(r.min) + ' per bulan.';
                        }
                    } else {
                        hasilDeskripsi.textContent = '';
                    }
                }
            }

            placeholder?.classList.add('hidden');
            hasil?.classList.remove('hidden');
        }

        input?.addEventListener('input', perbarui);
        input?.addEventListener('change', perbarui);
        input?.addEventListener('keyup', perbarui);

        // Inisialisasi saat pertama kali dibuka jika nilai sudah ada
        if (input && input.value !== '') {
            perbarui();
        }
    })();
</script>
