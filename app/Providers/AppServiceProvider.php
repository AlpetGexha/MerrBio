<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use TomatoPHP\FilamentEcommerce\Models\Coupon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::unguard();

        // skip 2021_04_14_182305_fill_all_vars  migration while migrating on production on postgres

        if (app()->environment('production')) {
            $this->app['db']->whenSchemaIs('pgsql')->skipMigration('2021_04_14_182305_fill_all_vars');
        }


    }
}
