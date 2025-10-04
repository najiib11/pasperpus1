<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string $roles = null): Response
    {
        if (! $request->user()) {
            return redirect()->route('login');
        }

        if ($roles) {
            $allowed = explode('|', $roles); // contoh: 'admin|editor'
            if (! in_array($request->user()->role, $allowed)) {
                abort(403, 'Unauthorized.');
            }
        }

        return $next($request);
    }
}
