<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(\App\Http\Services\SyncService::class, function ($app) {
            return new \App\Http\Services\SyncService(
                $app->make(\App\Http\Services\ApiClientService::class),
                $app->make(\App\Http\Services\DebugService::class),
                [
                    $app->make(\App\Handlers\OrderSyncHandler::class),
                    $app->make(\App\Handlers\IncomeSyncHandler::class),
                    $app->make(\App\Handlers\SaleSyncHandler::class),
                    $app->make(\App\Handlers\StockSyncHandler::class),
                ],
                $app->make(\App\Repositories\AccountRepository::class)
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
