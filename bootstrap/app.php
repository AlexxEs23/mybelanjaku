<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'approved.seller' => \App\Http\Middleware\ApprovedSeller::class,
            'role' => \App\Http\Middleware\CheckRole::class,
            'seller.verified' => \App\Http\Middleware\CheckSellerVerified::class,
        ]);
        
        // Exclude file inputs from TrimStrings middleware
        $middleware->trimStrings(except: [
            'password',
            'password_confirmation',
            'gambar',
            'foto',
            'image',
            'file',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
