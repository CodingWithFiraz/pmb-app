<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\InformasiPmbController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\BantuanController;

// Applicant Controllers
use App\Http\Controllers\Applicant\DashboardController as ApplicantDashboard;
use App\Http\Controllers\Applicant\ApplicationController;
use App\Http\Controllers\Applicant\DocumentController;
use App\Http\Controllers\Applicant\PaymentController;

// Pendaftar Module (origin/main)
use App\Http\Controllers\Applicant\DokumenController;
use App\Http\Controllers\Applicant\PembayaranController;
use App\Http\Controllers\Applicant\PengumumanController;
use App\Http\Controllers\Applicant\ProfilController;
use App\Http\Controllers\Applicant\PilihanController;
use App\Http\Controllers\Applicant\KartuController;
use App\Http\Controllers\MidtransCallbackController;

// Admin Controllers
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\ApplicationController as AdminApplicationController;
use App\Http\Controllers\Admin\AdmissionPeriodController;
use App\Http\Controllers\Admin\StudyProgramController;
use App\Http\Controllers\Admin\PendaftaranController as AdminPendaftaranController;
use App\Http\Controllers\Admin\PeriodePmbController;
use App\Http\Controllers\Admin\ProdiController;
use App\Http\Controllers\Admin\FakultasController;
use App\Http\Controllers\Admin\JalurMasukController;
use App\Http\Controllers\Admin\GolonganUktController;
use App\Http\Controllers\Admin\TarifUktController;
use App\Http\Controllers\Admin\BiayaPendaftaranController;
use App\Http\Controllers\Admin\DokumenController as AdminDokumenController;
use App\Http\Controllers\Admin\BeritaController as AdminBeritaController;
use App\Http\Controllers\Admin\FaqController as AdminFaqController;
use App\Http\Controllers\Admin\PengaturanController as AdminPengaturanController;
use App\Http\Controllers\Admin\PengumumanController as AdminPengumumanController;
use App\Http\Controllers\Admin\PembayaranVerifikasiController;

// ─── Public Routes ────────────────────────────────────────────────────────────
Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/informasi', [PublicController::class, 'info'])->name('public.info');
Route::get('/program-studi', [PublicController::class, 'programs'])->name('public.programs');

Route::name('public.')->group(function () {
    Route::get('/tentang-kampus', [PublicController::class, 'tentangKampus'])->name('tentang-kampus');
    Route::get('/fakultas', [PublicController::class, 'fakultas'])->name('fakultas');
    Route::get('/fakultas/{fakultas:slug}', [PublicController::class, 'fakultasDetail'])->name('fakultas.detail');
    Route::get('/prodi/{prodi:slug}', [PublicController::class, 'prodiDetail'])->name('prodi');
    Route::get('/informasi-pmb', [InformasiPmbController::class, 'index'])->name('informasi-pmb');
    Route::get('/berita', [BeritaController::class, 'index'])->name('berita');
    Route::get('/berita/{berita:slug}', [BeritaController::class, 'show'])->name('berita.detail');
    Route::get('/bantuan/panduan', [BantuanController::class, 'panduan'])->name('bantuan.panduan');
    Route::get('/bantuan/faq', [BantuanController::class, 'faq'])->name('bantuan.faq');
    Route::get('/bantuan/kontak', [BantuanController::class, 'kontak'])->name('bantuan.kontak');
});

// ─── Auth Routes ──────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout')->middleware('auth');

// ─── Applicant Routes (PMB Form & Midtrans UKT Payment) ───────────────────────
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

    // Midtrans UKT Payment for Applicant
    Route::get('/{application}/pembayaran', [PaymentController::class, 'show'])->name('payment.show');
    Route::post('/{application}/pembayaran/snap', [PaymentController::class, 'snapToken'])->name('payment.snap');
    Route::get('/{application}/pembayaran/sukses', [PaymentController::class, 'success'])->name('payment.success');
});

// ─── Pendaftar Module (origin/main) ──────────────────────────────────────────
Route::middleware(['auth'])->prefix('pendaftar')->name('pendaftar.')->group(function () {
    Route::get('/dashboard', [ApplicantDashboard::class, 'index'])->name('dashboard');

    Route::get('/profil', [ProfilController::class, 'index'])->name('profil');
    Route::get('/profil/{step}', [ProfilController::class, 'edit'])->whereNumber('step')->name('profil.step');
    Route::post('/profil/{step}', [ProfilController::class, 'update'])->whereNumber('step')->name('profil.step.update');

    Route::get('/pilihan/preview', [PilihanController::class, 'preview'])->name('pilihan.preview');
    Route::get('/pilihan', [PilihanController::class, 'edit'])->name('pilihan');
    Route::post('/pilihan', [PilihanController::class, 'update'])->name('pilihan.update');

    Route::get('/dokumen', [DokumenController::class, 'index'])->name('dokumen');
    Route::post('/dokumen/verifikasi', [DokumenController::class, 'verifikasi'])->name('dokumen.verifikasi');
    Route::post('/dokumen/{dokumen}/upload', [DokumenController::class, 'upload'])->name('dokumen.upload');
    Route::get('/dokumen/{dokumen}/download', [DokumenController::class, 'download'])->name('dokumen.download');

    Route::get('/pembayaran/status', [PembayaranController::class, 'status'])->name('pembayaran.status');
    Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('pembayaran');
    Route::post('/pembayaran/{pembayaran}/snap', [PembayaranController::class, 'snap'])->name('pembayaran.snap');
    Route::post('/pembayaran/{pembayaran}/bukti', [PembayaranController::class, 'bukti'])->name('pembayaran.bukti');

    Route::get('/pengumuman', [PengumumanController::class, 'index'])->name('pengumuman');
    Route::get('/kartu', [KartuController::class, 'index'])->name('kartu');
});

// ─── Midtrans Webhook Callback ────────────────────────────────────────────────
Route::post('/midtrans/callback', MidtransCallbackController::class)->name('midtrans.callback');

// ─── Admin Routes ─────────────────────────────────────────────────────────────
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    })->name('home');
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

    Route::get('/pendaftar', [AdminApplicationController::class, 'index'])->name('applications.index');
    Route::get('/pendaftar/{application}', [AdminApplicationController::class, 'show'])->name('applications.show');
    Route::post('/pendaftar/{application}/review', [AdminApplicationController::class, 'review'])->name('applications.review');
    Route::post('/pendaftar/{application}/keputusan', [AdminApplicationController::class, 'decide'])->name('applications.decide');
    Route::post('/pendaftar/{application}/ukt', [AdminApplicationController::class, 'updateUkt'])->name('applications.ukt');
    Route::get('/pendaftar/{application}/dokumen/{document}/download', [AdminApplicationController::class, 'downloadDocument'])->name('applications.document.download');
    Route::get('/export', [AdminApplicationController::class, 'export'])->name('applications.export');

    Route::resource('/periode', AdmissionPeriodController::class);
    Route::resource('/program-studi', StudyProgramController::class);

    // Master Data Modules from origin/main
    Route::resource('/fakultas', FakultasController::class)->parameters(['fakultas' => 'fakultas'])->except(['show']);
    Route::resource('/prodi', ProdiController::class)->parameters(['prodi' => 'prodi'])->except(['show']);
    Route::resource('/jalur-masuk', JalurMasukController::class)->parameters(['jalur-masuk' => 'jalur'])->except(['show']);
    Route::resource('/golongan-ukt', GolonganUktController::class)->parameters(['golongan-ukt' => 'golongan'])->except(['show']);
    Route::get('/tarif-ukt', [TarifUktController::class, 'index'])->name('tarif-ukt.index');
    Route::post('/tarif-ukt', [TarifUktController::class, 'update'])->name('tarif-ukt.update');
    Route::resource('/biaya-pendaftaran', BiayaPendaftaranController::class)->parameters(['biaya-pendaftaran' => 'biaya'])->except(['show']);
    Route::resource('/dokumen-master', AdminDokumenController::class)->parameters(['dokumen-master' => 'dokumen'])->except(['show']);
    Route::resource('/berita', AdminBeritaController::class)->parameters(['berita' => 'berita'])->except(['show']);
    Route::resource('/faq', AdminFaqController::class)->parameters(['faq' => 'faq'])->except(['show']);
    Route::get('/pengaturan', [AdminPengaturanController::class, 'index'])->name('pengaturan.index');
    Route::post('/pengaturan', [AdminPengaturanController::class, 'update'])->name('pengaturan.update');
    Route::get('/pengumuman', [AdminPengumumanController::class, 'index'])->name('pengumuman.index');
    Route::post('/pengumuman/{pendaftaran}/hasil', [AdminPengumumanController::class, 'setHasil'])->name('pengumuman.set-hasil');
    Route::post('/pengumuman/bulk', [AdminPengumumanController::class, 'bulk'])->name('pengumuman.bulk');
    Route::get('/pembayaran/verifikasi', [PembayaranVerifikasiController::class, 'index'])->name('pembayaran.verifikasi.index');
    Route::post('/pembayaran/{pembayaran}/verifikasi', [PembayaranVerifikasiController::class, 'verifikasi'])->name('pembayaran.verifikasi');
});
