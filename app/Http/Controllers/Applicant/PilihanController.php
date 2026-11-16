<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Models\BiayaPendaftaran;
use App\Models\GolonganUkt;
use App\Models\JalurMasuk;
use App\Models\Pendaftaran;
use App\Models\PeriodePmb;
use App\Models\Prodi;
use App\Models\TarifUkt;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PilihanController extends Controller
{
    private const PESAN_PROFIL_BELUM_LENGKAP =
        'Lengkapi data orang tua dan golongan UKT terlebih dahulu sebelum memilih jalur.';

    public function edit(Request $request): View|RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $periode = PeriodePmb::getActive();

        if (! $periode) {
            return redirect()
                ->route('pendaftar.dashboard')
                ->with('error', 'Belum ada periode PMB yang aktif.');
        }

        $profile = $user->profile()->first();

        if (! $profile || ! $profile->golongan_id) {
            return redirect()
                ->route('pendaftar.profil.step', 4)
                ->with('info', self::PESAN_PROFIL_BELUM_LENGKAP);
        }

        $pendaftaran = $user->pendaftaran()
            ->where('periode_id', $periode->id)
            ->first();

        $terkunci = $pendaftaran?->pilihanTerkunci() ?? false;

        $selected = [
            'jalur_id'    => (int) old('jalur_id', $pendaftaran?->jalur_id ?? 0),
            'prodi_1_id'  => (int) old('prodi_1_id', $pendaftaran?->prodi_1_id ?? 0),
            'prodi_2_id'  => (int) old('prodi_2_id', $pendaftaran?->prodi_2_id ?? 0),
            'golongan_id' => (int) $profile->golongan_id,
        ];

        $profile->loadMissing('golongan');

        return view('pendaftar.pilihan', [
            'periode'    => $periode,
            'pendaftaran' => $pendaftaran,
            'profile'    => $profile,
            'terkunci'   => $terkunci,
            'selected'   => $selected,
            'jalur'      => JalurMasuk::orderBy('urutan')->get(),
            'prodi'      => Prodi::with('fakultas')->orderBy('nama')->get(),
            'golongan'   => GolonganUkt::orderBy('kode')->get(),
            'ukt'        => $this->hitungUkt(
                $periode,
                $selected['jalur_id'],
                $selected['prodi_1_id'],
                $selected['golongan_id']
            ),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $periode = PeriodePmb::getActive();

        if (! $periode) {
            return redirect()
                ->route('pendaftar.dashboard')
                ->with('error', 'Belum ada periode PMB yang aktif.');
        }

        $profile = $user->profile()->first();

        if (! $profile || ! $profile->golongan_id) {
            return redirect()
                ->route('pendaftar.profil.step', 4)
                ->with('info', self::PESAN_PROFIL_BELUM_LENGKAP);
        }

        $validated = $request->validate(
            [
                'jalur_id'   => ['required', 'exists:jalur_masuk,id'],
                'prodi_1_id' => ['required', 'exists:prodi,id'],
                'prodi_2_id' => ['nullable', 'exists:prodi,id'],
            ],
            [
                'required' => 'Kolom :attribute wajib diisi.',
                'exists'   => 'Pilihan :attribute tidak valid.',
            ],
            [
                'jalur_id'   => 'jalur masuk',
                'prodi_1_id' => 'program studi pilihan pertama',
                'prodi_2_id' => 'program studi pilihan kedua',
            ]
        );

        $pendaftaran = $user->pendaftaran()
            ->where('periode_id', $periode->id)
            ->first();

        if ($pendaftaran && $pendaftaran->pilihanTerkunci()) {
            return redirect()
                ->route('pendaftar.pilihan')
                ->with('error', 'Pilihan tidak dapat diubah karena status pendaftaran sudah '.$pendaftaran->statusLabel().'.');
        }

        $pendaftaran = Pendaftaran::firstOrNew([
            'user_id'    => $user->id,
            'periode_id' => $periode->id,
        ]);

        $pendaftaran->fill([
            'jalur_id'    => $validated['jalur_id'],
            'prodi_1_id'  => $validated['prodi_1_id'],
            'prodi_2_id'  => $validated['prodi_2_id'] ?? null,
            'golongan_id' => $profile->golongan_id,
        ]);

        if (! $pendaftaran->exists) {
            // Submit pertama selalu membuat pendaftaran berstatus draft.
            $pendaftaran->status = 'draft';
        }

        $pendaftaran->save();

        return redirect()
            ->route('pendaftar.pilihan')
            ->with('success', 'Pilihan jalur dan program studi berhasil disimpan sebagai draft.');
    }

    /**
     * Endpoint JSON untuk preview UKT saat pilihan berubah.
     */
    public function preview(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'jalur_id'    => ['nullable', 'exists:jalur_masuk,id'],
            'prodi_1_id'  => ['nullable', 'exists:prodi,id'],
            'golongan_id' => ['nullable', 'exists:golongan_ukt,id'],
        ]);

        $periode = PeriodePmb::getActive();

        $nominal = $this->hitungUkt(
            $periode,
            $validated['jalur_id'] ?? null,
            $validated['prodi_1_id'] ?? null,
            $validated['golongan_id'] ?? null
        );

        $jalur = isset($validated['jalur_id'])
            ? JalurMasuk::find($validated['jalur_id'])
            : null;

        $nominalBiaya = null;

        if ($periode && $jalur) {
            $nominalBiaya = BiayaPendaftaran::query()
                ->where('periode_id', $periode->id)
                ->where('jalur_id', $jalur->id)
                ->value('nominal');
        }

        $biayaLabel = '-';

        if ($jalur) {
            $biayaLabel = $jalur->punya_biaya_pendaftaran && $nominalBiaya !== null
                ? 'Rp '.number_format((int) $nominalBiaya, 0, ',', '.')
                : 'Gratis';
        }

        return response()->json([
            'nominal'         => $nominal,
            'formatted'       => $nominal === null
                ? null
                : 'Rp '.number_format($nominal, 0, ',', '.'),
            'biaya_label'     => $biayaLabel,
        ]);
    }

    private function hitungUkt(?PeriodePmb $periode, mixed $jalurId, mixed $prodiId, mixed $golonganId): ?int
    {
        if (! $periode || ! $jalurId || ! $prodiId || ! $golonganId) {
            return null;
        }

        $nominal = TarifUkt::query()
            ->where('periode_id', $periode->id)
            ->where('jalur_id', $jalurId)
            ->where('prodi_id', $prodiId)
            ->where('golongan_id', $golonganId)
            ->value('nominal');

        return $nominal === null ? null : (int) $nominal;
    }
}
