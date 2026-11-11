<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class PembayaranVerifikasiController extends Controller
{
    public function index(Request $request): View
    {
        $query = Pembayaran::with(['pendaftaran.user', 'pendaftaran.jalur', 'pendaftaran.prodi'])
            ->where('status', 'pending')
            ->latest();

        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('pendaftaran.user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })->orWhereHas('pendaftaran', function ($q) use ($search) {
                $q->where('nomor_pendaftaran', 'like', "%{$search}%");
            });
        }

        $pembayaran = $query->paginate(20)->withQueryString();

        return view('admin.pembayaran.verifikasi', compact('pembayaran'));
    }

    /**
     * Verifikasi pembayaran manual: setel status paid/failed + catatan.
     */
    public function verifikasi(Request $request, Pembayaran $pembayaran): RedirectResponse
    {
        Gate::authorize('update', $pembayaran);

        $validated = $request->validate([
            'status'      => ['required', 'in:paid,failed'],
            'catatan_admin' => ['nullable', 'string', 'max:1000'],
        ], [
            'status.required' => 'Status pembayaran wajib dipilih.',
            'status.in'       => 'Status pembayaran tidak valid.',
        ]);

        $pembayaran->update([
            'status'        => $validated['status'],
            'catatan_admin' => $validated['catatan_admin'] ?? $pembayaran->catatan_admin,
            'paid_at'       => $validated['status'] === 'paid' ? now() : null,
            'metode'        => 'transfer manual',
        ]);

        return back()->with('success', 'Pembayaran '.$pembayaran->pendaftaran->user->name.' berhasil ditandai sebagai '.$pembayaran->statusLabel().'.');
    }
}
