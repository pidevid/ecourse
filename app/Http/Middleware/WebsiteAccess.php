<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WebsiteAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user->hasRole('admin') || $user->hasRole('author')) {
            return $next($request);
        }

        if ($user->has_website_access) {
            return $next($request);
        }

        return redirect()->route('member.website.buy')
            ->with('toast_warning', 'Anda perlu membeli akses Personal Website terlebih dahulu.');
    }
}
