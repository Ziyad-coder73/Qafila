<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAgentOrAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || (! $user->isAdmin() && ! $user->isAgent())) {
            abort(403);
        }

        if ($user->isAgent() && ! $user->is_active) {
            abort(403, 'Your agent account has been deactivated.');
        }

        return $next($request);
    }
}
