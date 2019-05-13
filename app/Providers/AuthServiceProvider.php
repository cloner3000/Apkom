<?php

namespace Apkom\Providers;

use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'Apkom\Model' => 'Apkom\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('isWarek', function($user){
            return $user->role === 'Wakil Rektor';
        });

        Gate::define('isKaprodi', function($user){
            return $user->role === 'Kaprodi';
        });

        Gate::define('isAkademik', function($user){
            return $user->role === 'Bagian Akademik';
        });

        Gate::define('isAdmin', function($user){
            return $user->role === 'Bagian Akademik' || $user->role === 'Kaprodi' || $user->role === 'Wakil Rektor';
        });

        Gate::define('isMahasiswa', function($user){
            return $user->role === 'Mahasiswa';
        });

        Passport::routes();
        
    }
}
