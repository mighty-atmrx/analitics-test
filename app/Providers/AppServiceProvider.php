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
                $app->make(\App\Http\Services\PaginatedDataFetcher::class),
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

        $this->app->singleton(\App\Http\Services\ApiHttpClientService::class, function ($app) {
            return new \App\Http\Services\ApiHttpClientService(
                $app->make(\App\Http\Services\DebugService::class),
                config('wb.url'),
                config('wb.key'),
                30,
                3
            );
        });

        $this->app->singleton(\App\Http\Services\PaginatedDataFetcher::class, function ($app) {
            return new \App\Http\Services\PaginatedDataFetcher(
                $app->make(\App\Http\Services\ApiHttpClientService::class),
                $app->make(\App\Http\Services\DateStrategyService::class),
                $app->make(\App\Http\Services\DebugService::class)
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
