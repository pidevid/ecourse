<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
	    if(env('FORCE_HTTPS',false)) {
		    URL::forceScheme('https');
	    }
        try {
            view()->share('categories', Category::get());
        } catch (\Exception $e) {
            view()->share('categories', collect());
        }

        Paginator::useBootstrap();
    }
}
