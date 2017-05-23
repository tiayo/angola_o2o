<?php

namespace App\Providers;

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
        $this->app->bind('App\Repositories\UserRepositoryInterface','App\Repositories\UserRepository');
        $this->app->bind('App\Repositories\AddressRepositoryInterface','App\Repositories\AddressRepository');
    }
}
