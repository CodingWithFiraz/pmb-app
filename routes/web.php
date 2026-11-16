<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\Applicant\DashboardController as ApplicantDashboard;
use App\Http\Controllers\Applicant\ApplicationController;
use App\Http\Controllers\Applicant\DocumentController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\ApplicationController as AdminApplicationController;
use App\Http\Controllers\Admin\AdmissionPeriodController;
use App\Http\Controllers\Admin\StudyProgramController;

// Public
Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/informasi', [PublicController::class, 'info'])->name('public.info');
Route::get('/program-studi', [PublicController::class, 'programs'])->name('public.programs');

// Auth
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout')->middleware('auth');

// Applicant
Route::middleware(['auth'])->prefix('pendaftaran')->name('applicant.')->group(function () {
    Route::get('/dashboard', [ApplicantDashboard::class, 'index'])->name('dashboard');
    Route::get('/buat', [ApplicationController::class, 'create'])->name('application.create');
    Route::post('/buat', [ApplicationController::class, 'store'])->name('application.store');
    Route::get('/{application}/edit', [ApplicationController::class, 'edit'])->name('application.edit');
    Route::put('/{application}', [ApplicationController::class, 'update'])->name('application.update');
    Route::get('/{application}/dokumen', [DocumentController::class, 'index'])->name('document.index');
    Route::post('/{application}/dokumen', [DocumentController::class, 'store'])->name('document.store');
    Route::delete('/{application}/dokumen/{document}', [DocumentController::class, 'destroy'])->name('document.destroy');
    Route::get('/{application}/ringkasan', [ApplicationController::class, 'summary'])->name('application.summary');
    Route::post('/{application}/submit', [ApplicationController::class, 'submit'])->name('application.submit');
    Route::get('/{application}/status', [ApplicationController::class, 'status'])->name('application.status');
    Route::get('/dokumen/{document}/download', [DocumentController::class, 'download'])->name('document.download');
});

// Admin
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

    Route::get('/pendaftar', [AdminApplicationController::class, 'index'])->name('applications.index');
    Route::get('/pendaftar/{application}', [AdminApplicationController::class, 'show'])->name('applications.show');
    Route::post('/pendaftar/{application}/review', [AdminApplicationController::class, 'review'])->name('applications.review');
    Route::post('/pendaftar/{application}/keputusan', [AdminApplicationController::class, 'decide'])->name('applications.decide');
    Route::get('/pendaftar/{application}/dokumen/{document}/download', [AdminApplicationController::class, 'downloadDocument'])->name('applications.document.download');
    Route::get('/export', [AdminApplicationController::class, 'export'])->name('applications.export');

    Route::resource('/periode', AdmissionPeriodController::class);
    Route::resource('/program-studi', StudyProgramController::class);
});