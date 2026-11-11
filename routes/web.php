<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\InformasiPmbController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\BantuanController;
use App\Http\Controllers\Applicant\DashboardController as ApplicantDashboard;
use App\Http\Controllers\Applicant\DokumenController;
use App\Http\Controllers\Applicant\PembayaranController;
use App\Http\Controllers\Applicant\PengumumanController;
use App\Http\Controllers\Applicant\ProfilController;
use App\Http\Controllers\Applicant\PilihanController;
use App\Http\Controllers\Applicant\KartuController;
use App\Http\Controllers\MidtransCallbackController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
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

// Public
Route::get('/', [PublicController::class, 'index'])->name('home');

Route::name('public.')->group(function () {
    Route::get('/tentang-kampus', [PublicController::class, 'tentangKampus'])->name('tentang-kampus');

    Route::get('/fakultas', [PublicController::class, 'fakultas'])->name('fakultas');
    Route::get('/fakultas/{fakultas:slug}', [PublicController::class, 'fakultasDetail'])->name('fakultas.detail');

    Route::get('/program-studi', [PublicController::class, 'prodiIndex'])->name('programs');
    Route::get('/prodi/{prodi:slug}', [PublicController::class, 'prodiDetail'])->name('prodi');

    Route::get('/informasi-pmb', [InformasiPmbController::class, 'index'])->name('informasi-pmb');

    Route::get('/berita', [BeritaController::class, 'index'])->name('berita');
    Route::get('/berita/{berita:slug}', [BeritaController::class, 'show'])->name('berita.detail');

    Route::get('/bantuan/panduan', [BantuanController::class, 'panduan'])->name('bantuan.panduan');
    Route::get('/bantuan/faq', [BantuanController::class, 'faq'])->name('bantuan.faq');
    Route::get('/bantuan/kontak', [BantuanController::class, 'kontak'])->name('bantuan.kontak');
});

// Auth
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout')->middleware('auth');

// Pendaftar
Route::middleware(['auth', 'pendaftar'])->prefix('pendaftar')->name('pendaftar.')->group(function () {
    Route::get('/dashboard', [ApplicantDashboard::class, 'index'])->name('dashboard');

    Route::get('/profil', [ProfilController::class, 'index'])->name('profil');
    Route::get('/profil/{step}', [ProfilController::class, 'edit'])
        ->whereNumber('step')->name('profil.step');
    Route::post('/profil/{step}', [ProfilController::class, 'update'])
        ->whereNumber('step')->name('profil.step.update');

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

// Callback Midtrans (dipanggil oleh server Midtrans, tanpa login).
Route::post('/midtrans/callback', MidtransCallbackController::class)->name('midtrans.callback');

// Admin
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    })->name('home');
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

    Route::get('/pendaftar', [AdminPendaftaranController::class, 'index'])->name('pendaftaran.index');
    Route::get('/pendaftar/{pendaftaran}', [AdminPendaftaranController::class, 'show'])->name('pendaftaran.show');
    Route::post('/pendaftar/{pendaftaran}/verifikasi', [AdminPendaftaranController::class, 'verifikasi'])->name('pendaftaran.verifikasi');
    Route::post('/pendaftar/{pendaftaran}/tolak', [AdminPendaftaranController::class, 'tolak'])->name('pendaftaran.tolak');
    Route::post('/dokumen/{dokumen}/status', [AdminPendaftaranController::class, 'statusDokumen'])->name('dokumen.status');
    Route::get('/dokumen/{dokumen}/unduh', [AdminPendaftaranController::class, 'unduhDokumen'])->name('dokumen.unduh');
    Route::get('/export', [AdminPendaftaranController::class, 'export'])->name('pendaftaran.export');

    Route::resource('/periode', PeriodePmbController::class);
    Route::post('/periode/{periode}/toggle', [PeriodePmbController::class, 'toggle'])->name('periode.toggle');

    Route::resource('/fakultas', FakultasController::class)->parameters(['fakultas' => 'fakultas'])->except(['show']);
    Route::resource('/program-studi', ProdiController::class)->parameters(['program-studi' => 'prodi'])->except(['show']);
    Route::resource('/jalur-masuk', JalurMasukController::class)->parameters(['jalur-masuk' => 'jalur'])->except(['show']);
    Route::resource('/golongan-ukt', GolonganUktController::class)->parameters(['golongan-ukt' => 'golongan'])->except(['show']);

    Route::get('/tarif-ukt', [TarifUktController::class, 'index'])->name('tarif-ukt.index');
    Route::post('/tarif-ukt', [TarifUktController::class, 'update'])->name('tarif-ukt.update');

    Route::resource('/biaya-pendaftaran', BiayaPendaftaranController::class)->parameters(['biaya-pendaftaran' => 'biaya'])->except(['show']);

    Route::resource('/dokumen', AdminDokumenController::class)->parameters(['dokumen' => 'dokumen'])->except(['show']);
    Route::get('/dokumen/{dokumen}/mapping', [AdminDokumenController::class, 'mapping'])->name('dokumen.mapping');
    Route::post('/dokumen/{dokumen}/mapping', [AdminDokumenController::class, 'simpanMapping'])->name('dokumen.mapping.simpan');

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
