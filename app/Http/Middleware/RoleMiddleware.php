<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! $request->user()) {
            return redirect()->route('login');
        }

        if (! $request->user()->isActive()) {
            auth()->logout();
            $request->session()->invalidate();

            return redirect()->route('login')->withErrors([
                'username' => 'Akun Anda belum aktif atau telah ditolak. Hubungi admin.',
            ]);
        }

        if (! in_array($request->user()->role, $roles, true)) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk halaman ini.');
        }

        return $next($request);
    }
}
