<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;

class AdminOrAuthorMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user && ($user->isAdmin() || $user->role === UserRole::USER)) {
            return $next($request);
        }

        abort(403, 'Brak uprawnień.');
    }
}
