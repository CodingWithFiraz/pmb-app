<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Models\GolonganUkt;
use App\Models\PendaftarProfile;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfilController extends Controller
{
    /**
     * Definisi langkah pengisian profil beserta field yang disimpan.
     *
     * @var array<int, array{judul: string, fields: array<int, string>}>
     */
    public const STEPS = [
        1 => [
            'judul'  => 'Data Diri',
            'fields' => ['nik', 'nisn', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin'],
        ],
        2 => [
            'judul'  => 'Kontak & Alamat',
            'fields' => ['alamat', 'provinsi', 'kota'],
        ],
        3 => [
            'judul'  => 'Asal Sekolah',
            'fields' => ['asal_sekolah', 'tahun_lulus'],
        ],
        4 => [
            'judul'  => 'Orang Tua & Golongan UKT',
            'fields' => ['nama_ortu', 'pekerjaan_ortu', 'penghasilan_ortu', 'golongan_id'],
        ],
    ];

    /**
     * Arahkan ke langkah pertama yang belum lengkap.
     */
    public function index(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();
        $profile = $user->profile()->first();

        foreach (self::STEPS as $step => $info) {
            if (! $this->stepFilled($profile, $info['fields'])) {
                return redirect()->route('pendaftar.profil.step', $step);
            }
        }

        return redirect()->route('pendaftar.profil.step', 1);
    }

    public function edit(Request $request, int $step): View
    {
        abort_unless(isset(self::STEPS[$step]), 404);

        /** @var User $user */
        $user = $request->user();
        $profile = $user->profile()->first();

        return view('pendaftar.profil.step', [
            'step'      => $step,
            'profile'   => $profile,
            'steps'     => self::STEPS,
            'completed' => $this->completedSteps($profile),
            'golongan'  => GolonganUkt::orderBy('kode')->get(),
        ]);
    }

    public function update(Request $request, int $step): RedirectResponse
    {
        abort_unless(isset(self::STEPS[$step]), 404);

        /** @var User $user */
        $user = $request->user();
        $profile = $user->profile()->first();

        $validated = $request->validate(
            $this->rules($step, $profile),
            $this->messages(),
            $this->attributes()
        );

        PendaftarProfile::updateOrCreate(
            ['user_id' => $user->id],
            $validated
        );

        if ($step < count(self::STEPS)) {
            return redirect()
                ->route('pendaftar.profil.step', $step + 1)
                ->with('success', 'Langkah '.$step.' tersimpan. Lanjutkan ke langkah berikutnya.');
        }

        return redirect()
            ->route('pendaftar.pilihan')
            ->with('success', 'Profil tersimpan. Sekarang pilih jalur dan program studi.');
    }

    /**
     * @param  array<int, string>  $fields
     */
    private function stepFilled(?PendaftarProfile $profile, array $fields): bool
    {
        if (! $profile) {
            return false;
        }

        foreach ($fields as $field) {
            if (! filled($profile->{$field})) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return array<int, int>
     */
    private function completedSteps(?PendaftarProfile $profile): array
    {
        $completed = [];

        foreach (self::STEPS as $step => $info) {
            if ($this->stepFilled($profile, $info['fields'])) {
                $completed[] = $step;
            }
        }

        return $completed;
    }

    /**
     * @return array<string, mixed>
     */
    private function rules(int $step, ?PendaftarProfile $profile): array
    {
        return match ($step) {
            1 => [
                'nik'           => [
                    'required', 'digits:16',
                    Rule::unique('pendaftar_profiles', 'nik')->ignore($profile?->id),
                ],
                'nisn'          => [
                    'required', 'digits:10',
                    Rule::unique('pendaftar_profiles', 'nisn')->ignore($profile?->id),
                ],
                'tempat_lahir'  => ['required', 'string', 'max:255'],
                'tanggal_lahir' => ['required', 'date', 'before:today'],
                'jenis_kelamin' => ['required', 'in:L,P'],
            ],
            2 => [
                'alamat'   => ['required', 'string', 'max:1000'],
                'provinsi' => ['required', 'string', 'max:255'],
                'kota'     => ['required', 'string', 'max:255'],
            ],
            3 => [
                'asal_sekolah' => ['required', 'string', 'max:255'],
                'tahun_lulus'  => ['required', 'digits:4', 'integer', 'between:1950,'.now()->year],
            ],
            4 => [
                'nama_ortu'        => ['required', 'string', 'max:255'],
                'pekerjaan_ortu'   => ['required', 'string', 'max:255'],
                'penghasilan_ortu' => ['required', 'integer', 'min:0'],
                'golongan_id'      => ['required', 'exists:golongan_ukt,id'],
            ],
        };
    }

    /**
     * @return array<string, string>
     */
    private function messages(): array
    {
        return [
            'required' => 'Kolom :attribute wajib diisi.',
            'digits'   => 'Kolom :attribute harus :digits digit.',
            'unique'   => ':attribute sudah terdaftar.',
            'date'     => 'Kolom :attribute harus berupa tanggal yang valid.',
            'before'   => 'Kolom :attribute harus sebelum hari ini.',
            'in'       => 'Pilihan :attribute tidak valid.',
            'exists'   => 'Pilihan :attribute tidak valid.',
            'integer'  => 'Kolom :attribute harus berupa angka.',
            'between'  => 'Kolom :attribute tidak valid.',
            'max'      => 'Kolom :attribute terlalu panjang.',
            'min'      => 'Kolom :attribute tidak valid.',
            'string'   => 'Kolom :attribute harus berupa teks.',
        ];
    }

    /**
     * @return array<string, string>
     */
    private function attributes(): array
    {
        return [
            'nik'              => 'NIK',
            'nisn'             => 'NISN',
            'tempat_lahir'     => 'tempat lahir',
            'tanggal_lahir'    => 'tanggal lahir',
            'jenis_kelamin'    => 'jenis kelamin',
            'alamat'           => 'alamat',
            'provinsi'         => 'provinsi',
            'kota'             => 'kota',
            'asal_sekolah'     => 'asal sekolah',
            'tahun_lulus'      => 'tahun lulus',
            'nama_ortu'        => 'nama orang tua',
            'pekerjaan_ortu'   => 'pekerjaan orang tua',
            'penghasilan_ortu' => 'penghasilan orang tua',
            'golongan_id'      => 'golongan UKT',
        ];
    }
}
