<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Response;
use Illuminate\Pagination\Paginator;

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

        // use bootstrap pagination in datatable
        Paginator::useBootstrap();

        //* success API macro
        Response::macro('success', function ($message, $data, $httpResponseCode) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $data
            ], $httpResponseCode);
        });

        //* error API macro
        Response::macro('error', function ($message, $errors, $httpResponseCode) {
            return response()->json([
                'success' => false,
                'message' => $message,
                'errors' => $errors
            ], $httpResponseCode);
        });
    }
}
