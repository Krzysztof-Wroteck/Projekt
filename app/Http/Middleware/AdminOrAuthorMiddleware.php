<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminOrAuthorMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user && ($user->isAdmin() || $user->id === $request->route('post')->user_id||$user->id === $request->route('comment')->user_id)) {
            return $next($request);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'User not authenticated.',
        ])->setStatusCode(401);
    }
}
