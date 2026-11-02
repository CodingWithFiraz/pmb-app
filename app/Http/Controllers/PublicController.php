<?php

namespace App\Http\Controllers;

use App\Models\AdmissionPeriod;
use App\Models\StudyProgram;

class PublicController extends Controller
{
    public function index()
    {
        $activePeriod = AdmissionPeriod::where('status', 'open')->first();
        return view('public.home', compact('activePeriod'));
    }

    public function info()
    {
        $periods = AdmissionPeriod::orderByDesc('created_at')->get();
        return view('public.info', compact('periods'));
    }

    public function programs()
    {
        $programs = StudyProgram::where('is_active', true)->get();
        return view('public.programs', compact('programs'));
    }
}