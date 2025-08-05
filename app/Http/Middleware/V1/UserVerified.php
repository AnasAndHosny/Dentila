<?php

namespace App\Http\Middleware\V1;

use App\Helpers\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (!$user->hasVerifiedPhone()) {
            $data = [
                'error' => 'Not Verified'
            ];
            return ApiResponse::Error($data, __('messages.notVerified'), 403);
        }

        return $next($request);
    }
}
