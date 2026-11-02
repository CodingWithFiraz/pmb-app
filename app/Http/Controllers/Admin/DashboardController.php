<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\AdmissionPeriod;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total'             => Application::count(),
            'draft'             => Application::where('status', 'draft')->count(),
            'submitted'         => Application::where('status', 'submitted')->count(),
            'under_review'      => Application::where('status', 'under_review')->count(),
            'revision_required' => Application::where('status', 'revision_required')->count(),
            'passed'            => Application::where('status', 'passed')->count(),
            'not_passed'        => Application::where('status', 'not_passed')->count(),
        ];

        $activePeriod    = AdmissionPeriod::where('status', 'open')->first();
        $recentApplicants = Application::with(['user', 'studyProgram'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'activePeriod', 'recentApplicants'));
    }
}