<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\CsvImportService;

class CsvImportServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(CsvImportService::class, function ($app) {
            return new CsvImportService();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
