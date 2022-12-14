<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Repositories\UserInterface;
use App\Repositories\UserRepository;
use App\Repositories\EmployeeInterface;
use App\Repositories\EmployeeRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UserInterface::class,UserRepository::class);
        $this->app->bind(EmployeeInterface::class,EmployeeRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(125);
    }
}