<?php

namespace App\Providers;

use App\Contracts\MidtransGateway;
use App\Models\Application;
use App\Models\Pembayaran;
use App\Models\Pendaftaran;
use App\Policies\ApplicationPolicy;
use App\Policies\PembayaranPolicy;
use App\Policies\PendaftaranPolicy;
use App\Services\MidtransService;
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
        Carbon::setLocale('id');

        Gate::policy(Application::class, ApplicationPolicy::class);
        Gate::policy(Pendaftaran::class, PendaftaranPolicy::class);
        Gate::policy(Pembayaran::class, PembayaranPolicy::class);
    }
}
