<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Akses;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Request;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */

    public function boot()
    {
        Gate::define('r', function ($user, $segment) {
            return Akses::where('id_role', $user->id_role)
                ->whereHas('menu', function ($q) use ($segment) {
                    $q->where('segment', $segment);
                })
                ->where('read', 1)
                ->exists();
        });
    }
}
