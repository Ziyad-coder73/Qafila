<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsPartnerOrAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || (! $user->isAdmin() && ! $user->isPartner())) {
            abort(403);
        }

        if ($user->isPartner() && ! $user->is_active) {
            abort(403, 'Your partner account has been deactivated.');
        }

        return $next($request);
    }
}
