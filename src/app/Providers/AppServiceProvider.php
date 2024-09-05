<?php

namespace App\Providers;

use Illuminate\Support\Facades\Validator;
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
        Validator::extend('csv_format', function ($attribute, $value, $parameters, $validator) {
            $allowedExtensions = ['csv'];
            $extension = strtolower($value->getClientOriginalExtension());

            return in_array($extension, $allowedExtensions);
        });
    }
}
