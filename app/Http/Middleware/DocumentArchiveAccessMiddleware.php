<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DocumentArchiveAccessMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(401);
        }

        // Admins automatically have access.
        if ($user->isAdmin()) {
            return $next($request);
        }

        // Only designated Staff can access General Document Archives.
        if (! $user->can_access_document_archives) {
            abort(403, 'You do not have permission to access General Document Archives.');
        }

        return $next($request);
    }
}