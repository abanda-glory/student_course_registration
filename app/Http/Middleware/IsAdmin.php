<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Checks if the authenticated user is an admin
        if ($request->user()->is_admin !== true) {
            return response()->json([
                "message" => "Forbidden"
            ], 403);
        }

        // is user is admin continue request to controller
        return $next($request);
    }
}
