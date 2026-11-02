<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Models\AdmissionPeriod;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user         = Auth::user();
        $applications = $user->applications()->with(['admissionPeriod', 'studyProgram'])->latest()->get();
        $activePeriod = AdmissionPeriod::getActive();

        return view('applicant.dashboard', compact('applications', 'activePeriod'));
    }
}