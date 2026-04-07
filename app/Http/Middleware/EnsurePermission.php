<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePermission
{
    public function handle(Request $request, Closure $next, string ...$permissions): Response
    {
        $user = auth()->user();

        if (! $user) {
            return redirect()->route('admin.login');
        }

        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        foreach ($permissions as $permission) {
            if ($user->hasPermission($permission)) {
                return $next($request);
            }
        }

        abort(403, 'ليس لديك صلاحية للوصول لهذه الصفحة');
    }
}
