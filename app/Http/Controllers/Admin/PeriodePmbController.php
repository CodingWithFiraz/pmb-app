<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PeriodePmb;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PeriodePmbController extends Controller
{
    public function index(): View
    {
        $periods = PeriodePmb::withCount('pendaftaran')->latest()->paginate(15);

        return view('admin.periods.index', compact('periods'));
    }

    public function create(): View
    {
        return view('admin.periods.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama'            => 'required|string|max:255',
            'tahun_akademik' => 'required|string|max:20',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'is_active'       => 'boolean',
        ]);

        $aktif = $request->boolean('is_active');

        DB::transaction(function () use ($validated, $aktif) {
            $periode = PeriodePmb::create(array_merge($validated, ['is_active' => false]));

            // Hanya satu periode yang boleh aktif.
            if ($aktif) {
                PeriodePmb::where('is_active', true)->update(['is_active' => false]);
                $periode->update(['is_active' => true]);
            }
        });

        return redirect()->route('admin.periode.index')->with('success', 'Periode berhasil ditambahkan.');
    }

    public function edit(PeriodePmb $periode): View
    {
        return view('admin.periods.edit', compact('periode'));
    }

    public function update(Request $request, PeriodePmb $periode): RedirectResponse
    {
        $validated = $request->validate([
            'nama'            => 'required|string|max:255',
            'tahun_akademik' => 'required|string|max:20',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'is_active'       => 'boolean',
        ]);

        $aktif = $request->boolean('is_active');

        DB::transaction(function () use ($periode, $validated, $aktif) {
            $periode->update(array_merge($validated, ['is_active' => $aktif]));

            // Hanya satu periode yang boleh aktif.
            if ($aktif) {
                PeriodePmb::where('is_active', true)->where('id', '!=', $periode->id)
                    ->update(['is_active' => false]);
            }
        });

        return redirect()->route('admin.periode.index')->with('success', 'Periode berhasil diperbarui.');
    }

    /**
     * Aktifkan / nonaktifkan periode. Hanya satu periode aktif pada satu waktu.
     */
    public function toggle(PeriodePmb $periode): RedirectResponse
    {
        $akanDiaktifkan = ! $periode->is_active;

        DB::transaction(function () use ($periode, $akanDiaktifkan) {
            if ($akanDiaktifkan) {
                PeriodePmb::where('is_active', true)->update(['is_active' => false]);
            }

            $periode->update(['is_active' => $akanDiaktifkan]);
        });

        return redirect()
            ->route('admin.periode.index')
            ->with('success', $akanDiaktifkan ? 'Periode berhasil diaktifkan.' : 'Periode dinonaktifkan.');
    }

    public function destroy(PeriodePmb $periode): RedirectResponse
    {
        if ($periode->pendaftaran()->exists()) {
            return back()->with('error', 'Periode sudah dipakai pendaftar dan tidak dapat dihapus.');
        }

        $periode->delete();

        return redirect()->route('admin.periode.index')->with('success', 'Periode berhasil dihapus.');
    }
}
