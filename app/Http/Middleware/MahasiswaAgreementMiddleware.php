<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MahasiswaAgreementMiddleware
{
    /**
     * Handle an incoming request.
     * Redirect first-time Mahasiswa login to the rules agreement page.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (
            $user
            && $user->isMahasiswa()
            && ! $user->has_agreed_to_rules
            && ! $request->routeIs('mahasiswa.panduan.*')
        ) {
            return redirect()->route('mahasiswa.panduan.index');
        }

        return $next($request);
    }
}
