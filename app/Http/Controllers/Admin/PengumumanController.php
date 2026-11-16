<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JalurMasuk;
use App\Models\Pendaftaran;
use App\Models\PeriodePmb;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class PengumumanController extends Controller
{
    public function index(Request $request): View
    {
        $query = Pendaftaran::with(['user.profile', 'jalur', 'prodi', 'periode', 'pembayaran'])
            ->latest('nomor_pendaftaran');

        if ($request->filled('periode_id')) {
            $query->where('periode_id', $request->periode_id);
        }

        if ($request->filled('jalur_id')) {
            $query->where('jalur_id', $request->jalur_id);
        }

        if ($request->filled('status_bayar')) {
            $query->whereHas('pembayaran', function ($q) use ($request) {
                $q->where('status', $request->status_bayar);
            });
        }

        if ($request->filled('hasil_kelulusan')) {
            $query->where('hasil_kelulusan', $request->hasil_kelulusan);
        }

        $pendaftaran = $query->paginate(20)->withQueryString();

        $periodeList = PeriodePmb::orderByDesc('tahun_akademik')->get();
        $jalurList = JalurMasuk::orderBy('urutan')->get();

        return view('admin.pengumuman.index', compact(
            'pendaftaran', 'periodeList', 'jalurList'
        ));
    }

    /**
     * Set hasil kelulusan untuk satu pendaftar.
     */
    public function setHasil(Request $request, Pendaftaran $pendaftaran)
    {
        Gate::authorize('update', $pendaftaran);

        $validated = $request->validate([
            'hasil_kelulusan' => ['required', 'in:belum,lulus,tidak_lulus'],
            'catatan_admin'   => ['nullable', 'string', 'max:1000'],
        ], [
            'hasil_kelulusan.required' => 'Hasil kelulusan wajib dipilih.',
            'hasil_kelulusan.in'       => 'Hasil kelulusan tidak valid.',
        ]);

        $pendaftaran->update([
            'hasil_kelulusan' => $validated['hasil_kelulusan'],
            'catatan_admin'   => $validated['catatan_admin'] ?? $pendaftaran->catatan_admin,
        ]);

        // Kirim notifikasi ke pendaftar jika hasil bukan belum.
        if ($validated['hasil_kelulusan'] !== 'belum') {
            $pendaftaran->user->notify(new \App\Notifications\PengumumanDikeluarkan(
                $pendaftaran->fresh(),
                $validated['hasil_kelulusan']
            ));
        }

        return back()->with('success', 'Hasil kelulusan '.$pendaftaran->user->name.' berhasil diperbarui.');
    }

    /**
     * Set hasil kelulusan secara bulk via checkbox.
     */
    public function bulk(Request $request)
    {
        $validated = $request->validate([
            'pilihan'         => ['required', 'array', 'min:1'],
            'pilihan.*'       => ['exists:pendaftaran,id'],
            'hasil_kelulusan' => ['required', 'in:lulus,tidak_lulus'],
            'catatan_admin'   => ['nullable', 'string', 'max:1000'],
        ], [
            'pilihan.required'    => 'Pilih setidaknya satu pendaftar.',
            'pilihan.min'         => 'Pilih setidaknya satu pendaftar.',
            'hasil_kelulusan.required' => 'Hasil kelulusan wajib dipilih.',
            'hasil_kelulusan.in'  => 'Hasil kelulusan tidak valid.',
        ]);

        $ids = $validated['pilihan'];
        $hasil = $validated['hasil_kelulusan'];
        $catatan = $validated['catatan_admin'] ?? null;

        $pendaftaran = Pendaftaran::whereIn('id', $ids)->get();

        foreach ($pendaftaran as $p) {
            Gate::authorize('update', $p);

            $p->update([
                'hasil_kelulusan' => $hasil,
                'catatan_admin'   => $catatan,
            ]);

            if ($hasil !== 'belum') {
                $p->user->notify(new \App\Notifications\PengumumanDikeluarkan(
                    $p->fresh(),
                    $hasil
                ));
            }
        }

        return back()->with('success', 'Hasil kelulusan sebanyak '.count($ids).' pendaftar berhasil diperbarui.');
    }
}
