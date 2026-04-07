<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCustomer
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth('customer')->check()) {
            return redirect()->route('store.login')
                ->with('redirect_to', $request->url());
        }

        return $next($request);
    }
}
