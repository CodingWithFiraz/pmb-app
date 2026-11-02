<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdmissionPeriod;
use Illuminate\Http\Request;

class AdmissionPeriodController extends Controller
{
    public function index()
    {
        $periods = AdmissionPeriod::latest()->paginate(10);
        return view('admin.periods.index', compact('periods'));
    }

    public function create()
    {
        return view('admin.periods.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'academic_year'    => 'required|string|max:20',
            'starts_at'        => 'required|date',
            'ends_at'          => 'required|date|after:starts_at',
            'registration_fee' => 'required|integer|min:0',
            'status'           => 'required|in:open,closed',
        ]);

        AdmissionPeriod::create($validated);

        return redirect()->route('admin.periode.index')->with('success', 'Periode berhasil ditambahkan.');
    }

    public function edit(AdmissionPeriod $periode)
    {
        return view('admin.periods.edit', compact('periode'));
    }

    public function update(Request $request, AdmissionPeriod $periode)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'academic_year'    => 'required|string|max:20',
            'starts_at'        => 'required|date',
            'ends_at'          => 'required|date|after:starts_at',
            'registration_fee' => 'required|integer|min:0',
            'status'           => 'required|in:open,closed',
        ]);

        $periode->update($validated);

        return redirect()->route('admin.periode.index')->with('success', 'Periode berhasil diperbarui.');
    }

    public function destroy(AdmissionPeriod $periode)
    {
        $periode->delete();
        return redirect()->route('admin.periode.index')->with('success', 'Periode dihapus.');
    }
}