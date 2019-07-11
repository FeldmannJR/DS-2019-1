<?php

namespace App\Providers;

use App\Indicators\IndicatorsService;
use App\Indicators\Spreadsheets\SpreadsheetDriveService;
use function foo\func;
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
        $this->app->singleton(SpreadsheetDriveService::class, function ($app) {
            return new SpreadsheetDriveService();
        });
        $this->app->singleton(IndicatorsService::class, function () {
            return new IndicatorsService();
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
