<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengaturan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PengaturanController extends Controller
{
    /**
     * Pengaturan situs ditampilkan sebagai form key-value.
     */
    public function index(): View
    {
        $pengaturan = Pengaturan::orderBy('key')->get();

        return view('admin.pengaturan.index', compact('pengaturan'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'settings'   => 'nullable|array',
            'settings.*' => 'nullable|string|max:5000',
            'key_baru'   => 'nullable|string|max:100|regex:/^[a-z0-9_\-]+$/i',
            'value_baru' => 'nullable|string|max:5000',
        ], [
            'key_baru.regex' => 'Key baru hanya boleh huruf, angka, tanda hubung, dan underscore.',
            'settings.*.max' => 'Isi pengaturan terlalu panjang.',
        ]);

        foreach ($validated['settings'] ?? [] as $key => $value) {
            $value = is_string($value) ? trim($value) : '';

            if ($value === '') {
                // Nilai kosong berarti pengaturan tidak dipakai.
                Pengaturan::where('key', $key)->delete();

                continue;
            }

            Pengaturan::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        if (! empty($validated['key_baru'])) {
            $value = trim((string) ($validated['value_baru'] ?? ''));

            if ($value !== '') {
                Pengaturan::updateOrCreate(['key' => $validated['key_baru']], ['value' => $value]);
            }
        }

        return redirect()->route('admin.pengaturan.index')->with('success', 'Pengaturan berhasil disimpan.');
    }
}
