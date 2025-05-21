<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Pemesanan;
use App\Models\Driver;
use App\Models\Kendaraan;
use App\Models\Persetujuan;
use App\Models\User;
use App\Policies\PemesananPolicy;
use App\Policies\DriverPolicy;
use App\Policies\KendaraanPolicy;
use App\Policies\PersetujuanPolicy;
use App\Policies\UserPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Pemesanan::class => PemesananPolicy::class,
    ];


    public function boot()
    {
        // Pemesanan Gates
        Gate::define('create-pemesanan', function ($user) {
            // Allow all authenticated users to create pemesanan
            return true;
        });

        Gate::define('viewAny-pemesanan', function ($user) {
            return $user->hasRole('admin');
        });

        Gate::define('view-pemesanan', function ($user, Pemesanan $pemesanan) {
            return $user->id === $pemesanan->user_id || $user->hasRole('admin');
        });

        Gate::define('update-pemesanan', function ($user, Pemesanan $pemesanan) {
            return $user->hasRole('admin') || $user->id === $pemesanan->user_id;
        });

        Gate::define('delete-pemesanan', function ($user, Pemesanan $pemesanan) {
            return $user->hasRole('admin');
        });
        
        // Driver Gates
        Gate::define('manage-drivers', function ($user) {
            return $user->role === 'admin';
        });
        
        // Kendaraan Gates
        Gate::define('manage-kendaraan', function ($user) {
            return $user->role === 'admin';
        });
        
        // Persetujuan Gates
        Gate::define('approve-pemesanan', function ($user) {
            return $user->role === 'approver';
        });
    }
}
