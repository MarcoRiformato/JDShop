<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckEditorRestrictions
{
    /**
     * Handle an incoming request.
     *
     * Editors cannot:
     * - Access customer management routes
     * - Access discount management routes
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Only apply restrictions to editors
        if ($user && $user->hasRole('editor')) {
            // Block customer management routes
            if ($request->is('admin/customers*') || $request->is('admin/inquiries*')) {
                abort(403, 'Gli editor non possono gestire i clienti.');
            }

            // Block discount routes
            if ($request->is('admin/discounts*')) {
                abort(403, 'Gli editor non possono gestire gli sconti.');
            }
        }

        return $next($request);
    }
}

