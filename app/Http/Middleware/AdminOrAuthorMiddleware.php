<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Enums\UserRole;
use App\Models\Post;
use App\Models\User;


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