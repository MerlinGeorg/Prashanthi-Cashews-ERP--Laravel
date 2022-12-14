<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $menudata['office'] = json_decode(file_get_contents(base_path('resources/data/menu-data/office-menu.json')));
        $menudata['factory'] = json_decode(file_get_contents(base_path('resources/data/menu-data/factory-menu.json')));
        $menudata['stockyard'] = json_decode(file_get_contents(base_path('resources/data/menu-data/stockyard-menu.json')));
        $menudata['package'] = json_decode(file_get_contents(base_path('resources/data/menu-data/package-menu.json')));
        
        \View::share('menuData',$menudata);
    }
}