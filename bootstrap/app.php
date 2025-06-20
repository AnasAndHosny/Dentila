<?php

use App\Models\User;
use App\Models\Medication;
use App\Helpers\ApiResponse;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->api(prepend: [
            \App\Http\Middleware\Localization::class, //change locale language for api routes
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // handle NotAuthorized exceptions from api requests and send JsonResponse
        $exceptions->render(function (AccessDeniedHttpException $e, $request) {
            if ($request->is('api/*')) {
                return Response::Error(['error' => 'Not Authorized'], __('messages.notAuthorized'), 403);
            }
        });

        // handle route model binding exceptions from api requests and send JsonResponse
        $exceptions->render(function (NotFoundHttpException $e, $request) {
            if ($request->is('api/*') && ($e->getPrevious() instanceof ModelNotFoundException)) {
                $class = match ($e->getPrevious()->getModel()) {
                    User::class => 'user',
                    Medication::class => 'medication',
                    default => 'record'
                };

                return ApiResponse::Error([], __('messages.notFound', ['class' => __($class)]), 404);
            }
        });
    })->create();
