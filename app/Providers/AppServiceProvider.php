<?php

namespace App\Providers;

//use App\Models\Setting;
use Laravel\Cashier\Cashier;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;
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
        $this->app->bind('path.public',function(){
            return'/home/u366672294/domains/subcontratame.com/public_html';
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
    */
    public function boot()
    {
        //try { if(!cache()->has('settings')) { Cache::set('settings',Setting::first());}} catch(\Exception $e) {}
        //\App\Models\User::observe(\App\Observers\UserObserver::class);
        // \App\Models\UserAddress::observe(\App\Observers\UserAddressObserver::class);
        // \App\Models\Workday::observe(\App\Observers\WorkDayObserver::class);
        // \App\Models\Service::observe(\App\Observers\ServiceObserver::class);
        Paginator::useBootstrap();
        Cashier::calculateTaxes();
    }
}
