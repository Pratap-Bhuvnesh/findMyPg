<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
//use App\Models\PG;

class PreventOwnerReview
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
         $user = auth()->user();
        // If not logged in, let auth middleware handle it
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        if ($user->role === 'owner') {
            return response()->json([
                'error' => 'You are not authorized to review any pg. '
            ], 403);
        }
        return $next($request);
    }
}
