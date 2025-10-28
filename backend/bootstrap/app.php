<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__ . '/../routes/api.php',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn(Request $request) => $request->is('api/*') || $request->wantsJson()
        );
        $exceptions->render(function (ModelNotFoundException $e, $request) {
            return response()->json([
                'message' => 'Resource not exists.',
                'error' => 1006
            ], 404);
        });

        $exceptions->render(function (NotFoundHttpException $e, $request) {
            $previous = $e->getPrevious();

            if (method_exists($previous, 'getModel') === false) {
                return response()->json([
                    'message' => 'Resource not exists.',
                    'error' => 1007
                ], 404);
            }

            $model = class_basename($previous->getModel());

            return response()->json([
                'message' => match ($model) {
                    'Obstacle' => 'Obstacle not exists.',
                    'Planet' => 'Planet not exists.',
                    'Rover' => 'Rover not exists.',
                    default => 'Resource not exists.',
                },
                'error' => 1008
            ], 404);
        });
    })->create();
