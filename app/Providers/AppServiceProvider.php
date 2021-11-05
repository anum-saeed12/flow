<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Admin directive
        Blade::if('admin', function () {
            return auth()->user() && auth()->user()->user_role == 'admin';
        });
        Blade::if('manager', function () {
            return auth()->user() && auth()->user()->user_role == 'manager';
        });
        Blade::if('sale', function () {
            return auth()->user() && auth()->user()->user_role == 'sale';
        });
        Blade::if('team', function () {
            return auth()->user() && auth()->user()->user_role == 'team';
        });
    }
}
