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
    // public function boot(): void
    // {
    //     //
    // }

    // public function boot()
    // {
    //     // Gate untuk permission "read" (r)
    //     Gate::define('r', function ($user) {
    //         $segment = Request::segment(1); // ambil segment pertama dari URL

    //         return Akses::where('id_role', $user->id_role)
    //             ->whereHas('menu', function ($query) use ($segment) {
    //                 $query->where('segment', $segment); // pastikan tabel menu punya kolom 'segment'
    //             })
    //             ->where('read', 1)
    //             ->exists();
    //     });
    // }

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
