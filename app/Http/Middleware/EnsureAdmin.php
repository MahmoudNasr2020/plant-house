<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('admin.login');
        }

        if (!auth()->user()->is_active) {
            auth()->logout();
            return redirect()->route('admin.login')
                ->withErrors(['email' => 'حسابك موقف. تواصل مع المدير العام.']);
        }

        return $next($request);
    }
}
