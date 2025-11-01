<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Register middleware aliases
        $middleware->alias([
            'user.type' => \App\Http\Middleware\CheckUserType::class,
            'school.context' => \App\Http\Middleware\SchoolContextMiddleware::class,
            'role' => \App\Http\Middleware\RoleBasedAccessMiddleware::class,
            'permission' => \App\Http\Middleware\PermissionMiddleware::class,
            'school.active' => \App\Http\Middleware\EnsureActiveSchoolMiddleware::class,
            'school.switch' => \App\Http\Middleware\SchoolSwitchMiddleware::class,
        ]);

        // Add global middleware
        $middleware->web(append: [
            \App\Http\Middleware\SchoolContextMiddleware::class,
        ]);

        // Add middleware to auth group
        $middleware->group('auth', [
            'auth',
            \App\Http\Middleware\EnsureActiveSchoolMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
