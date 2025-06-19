<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->hasHeader("locale")  && in_array($request->header("locale"), config('app.available_locales'))) {
            /**
             * If locale header found and it's available in our app then set it to the default locale
             */
            app()->setLocale($request->header("locale"));
        }
        return $next($request);
    }
}
