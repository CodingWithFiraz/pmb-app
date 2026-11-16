<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudyProgram;
use Illuminate\Http\Request;

class StudyProgramController extends Controller
{
    public function index()
    {
        $programs = StudyProgram::latest()->paginate(10);
        return view('admin.programs.index', compact('programs'));
    }

    public function create()
    {
        return view('admin.programs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code'        => 'required|string|unique:study_programs,code',
            'name'        => 'required|string|max:255',
            'faculty'     => 'required|string|max:255',
            'description' => 'nullable|string',
            'quota'       => 'nullable|integer|min:1',
            'is_active'   => 'boolean',
        ]);

        StudyProgram::create(array_merge($validated, [
            'is_active' => $request->boolean('is_active'),
        ]));

        return redirect()->route('admin.program-studi.index')->with('success', 'Program studi ditambahkan.');
    }

    public function edit(StudyProgram $programStudi)
    {
        return view('admin.programs.edit', compact('programStudi'));
    }

    public function update(Request $request, StudyProgram $programStudi)
    {
        $validated = $request->validate([
            'code'        => 'required|string|unique:study_programs,code,' . $programStudi->id,
            'name'        => 'required|string|max:255',
            'faculty'     => 'required|string|max:255',
            'description' => 'nullable|string',
            'quota'       => 'nullable|integer|min:1',
            'is_active'   => 'boolean',
        ]);

        $programStudi->update(array_merge($validated, [
            'is_active' => $request->boolean('is_active'),
        ]));

        return redirect()->route('admin.program-studi.index')->with('success', 'Program studi diperbarui.');
    }

    public function destroy(StudyProgram $programStudi)
    {
        $programStudi->update(['is_active' => false]);
        return redirect()->route('admin.program-studi.index')->with('success', 'Program studi dinonaktifkan.');
    }
}