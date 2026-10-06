<?php

use App\Traits\ApiResponse;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Laravel\Sanctum\Http\Middleware\CheckAbilities;
use Laravel\Sanctum\Http\Middleware\CheckForAnyAbility;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'abilities' => CheckAbilities::class,
            'ability' => CheckForAnyAbility::class,
        ]);
    })
    // ->withExceptions(function (Exceptions $exceptions): void {
    //     $exceptions->shouldRenderJsonWhen(
    //         fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
    //     );
    // }
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );

        $exceptions->render(function (AccessDeniedHttpException $e, Request $request) {

           $responder = new class
            {
                use ApiResponse;
            };
            return $responder->error([
                'message' => 'You are not authorized to perform this action.',
            ], 403);
        });

        $exceptions->render(function (
            Throwable $exception,
            Request $request
        ) {
            $responder = new class
            {
                use ApiResponse;
            };

            // $status = $exception instanceof HttpExceptionInterface
            //     ? $exception->getStatusCode()
            //     : 500;

            return $responder->error([
                'type' => get_class($exception),
                'status' => 0,
                'message' => $exception->getMessage(),
                'source' => 'Line: '
                    .$exception->getLine()
                    .': '
                    .$exception->getFile(),
            ], $exception->getStatusCode());
        });
    }
    )->create();
