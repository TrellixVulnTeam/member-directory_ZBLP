<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

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
        Schema::defaultStringLength(191);
<<<<<<< HEAD
        // \URL::forceRootUrl(\Config::get('app.url'));
        // // And this if you wanna handle https URL scheme
        // // It's not usefull for http://www.example.com, it's just to make it more independant from the constant value
        // if (str_contains(\Config::get('app.url'), 'https://developers.thegraphe.com/member-directory')) {
        //     \URL::forceScheme('https');
        //     //use \URL:forceSchema('https') if you use laravel < 5.4
        // }
=======
        \URL::forceRootUrl(\Config::get('app.url'));    
        // And this if you wanna handle https URL scheme
        // It's not usefull for http://www.example.com, it's just to make it more independant from the constant value
        if (\Str::contains(\Config::get('app.url'), 'https://')) {
            \URL::forceScheme('https');
            //use \URL:forceSchema('https') if you use laravel < 5.4
}
>>>>>>> 618d5a84e3460e9d830f42d69dd19295c6b2cbbd
    }
}
