<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return response()->json(['message' => 'Не авторизован.'], 401);
        }

        $allowed = collect($roles)->flatMap(fn (string $role) => explode('|', $role));

        if (! $user->hasAnyRole($allowed->all())) {
            return response()->json(['message' => 'Недостаточно прав доступа.'], 403);
        }

        return $next($request);
    }
}
