<?php

use App\Console\Commands\SyncDataBase;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpFoundation\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (Throwable $e) {
            if ($e instanceof \App\Http\Exceptions\DtoNotFoundException) {
                return response()->json([
                    'error' => $e->getMessage(),
                ], Response::HTTP_NOT_FOUND);
            }

            if ($e instanceof \App\Http\Exceptions\HandlerNotFoundException) {
                return response()->json([
                    'error' => $e->getMessage(),
                ], Response::HTTP_NOT_FOUND);
            }

            if ($e instanceof \App\Http\Exceptions\ServiceNotSupportTokenException) {
                return response()->json([
                    'error' => $e->getMessage(),
                ], Response::HTTP_FORBIDDEN);
            }

            if ($e instanceof \App\Http\Exceptions\TokenNotFoundException) {
                return response()->json([
                    'error' => $e->getMessage(),
                ], Response::HTTP_NOT_FOUND);
            }

            if ($e instanceof \App\Http\Exceptions\CompanyNameIsTakenException) {
                return response()->json([
                    'error' => $e->getMessage(),
                ], Response::HTTP_CONFLICT);
            }

            if ($e instanceof \App\Http\Exceptions\AccountAlreadyExistsException) {
                return response()->json([
                    'error' => $e->getMessage(),
                ], Response::HTTP_CONFLICT);
            }

            if ($e instanceof \App\Http\Exceptions\ApiServiceAlreadyExistsException) {
                return response()->json([
                    'error' => $e->getMessage(),
                ], Response::HTTP_CONFLICT);
            }

            if ($e instanceof \App\Http\Exceptions\TokenTypeAlreadyExistsException) {
                return response()->json([
                    'error' => $e->getMessage(),
                ], Response::HTTP_CONFLICT);
            }

            if ($e instanceof \App\Http\Exceptions\AccountNotFoundException) {
                return response()->json([
                    'error' => $e->getMessage(),
                ], Response::HTTP_NOT_FOUND);
            }

            if ($e instanceof \App\Http\Exceptions\LoginPasswordRequiredException) {
                return response()->json([
                    'error' => $e->getMessage(),
                ], Response::HTTP_BAD_REQUEST);
            }

            if ($e instanceof \App\Http\Exceptions\ApiServiceTokenTypeAlreadyExistsException) {
                return response()->json([
                    'error' => $e->getMessage(),
                ], Response::HTTP_CONFLICT);
            }
        });
    })
    ->withSchedule(function (Schedule $schedule): void {
        $schedule->command(SyncDataBase::class)->dailyAt('08:00');
        $schedule->command(SyncDataBase::class)->dailyAt('20:00');
    })->create();
