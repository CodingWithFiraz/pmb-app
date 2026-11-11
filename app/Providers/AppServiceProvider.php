<?php

namespace App\Providers;

use App\Models\Pembayaran;
use App\Models\Pendaftaran;
use App\Policies\PembayaranPolicy;
use App\Policies\PendaftaranPolicy;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(MidtransGateway::class, MidtransService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Tampilan tanggal berbahasa Indonesia (mis. "01 Januari 2026").
        Carbon::setLocale('id');

        // Kebijakan akses: pendaftar hanya boleh melihat data milik sendiri.
        Gate::policy(Pendaftaran::class, PendaftaranPolicy::class);
        Gate::policy(Pembayaran::class, PembayaranPolicy::class);
    }
}
