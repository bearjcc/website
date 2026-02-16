<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Trust Railway proxies for HTTPS detection
        $middleware->trustProxies(at: '*');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Game slug not found or not published: show wireframe 404 (Laravel converts ModelNotFoundException to NotFoundHttpException)
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if (! $request->isMethod('GET')) {
                return null;
            }
            $path = trim($request->path(), '/');
            $segments = $path === '' ? [] : explode('/', $path);
            $reserved = ['games', 'about', 'login', 'register', 'health', 'api', 'lore', 'admin'];
            $isGamePath = (count($segments) === 1 && ! in_array($segments[0], $reserved, true))
                || (count($segments) === 2 && $segments[1] === 'play' && ! in_array($segments[0], $reserved, true));
            if ($isGamePath) {
                return response()->view('errors.game-not-found', [], 404);
            }
            return null;
        });
    })->create();
