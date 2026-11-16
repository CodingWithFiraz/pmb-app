<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Models\AdmissionPeriod;
use App\Models\PeriodePmb;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        /** @var User $user */
        $user = Auth::user();

        $activePeriod = AdmissionPeriod::getActive()
            ?? (class_exists(PeriodePmb::class) ? PeriodePmb::getActive() : null);

        $applications = $user->applications()->with(['admissionPeriod', 'studyProgram'])->latest()->get();

        return view('applicant.dashboard', compact('applications', 'activePeriod'));
    }
}
