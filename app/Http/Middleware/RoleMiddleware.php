<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // jika user tidak dikenal
        if (!Auth::check()) {
            return redirect()->route('auth.index');
        }

        $user = Auth::user();

        // Cek apakah role user ada dalam daftar yang diizinkan
        if (!in_array($user->role, $roles)) {
            abort(403, 'Akses Dilarang');
        }

        // memproses login berhasil
        return $next($request);
    }
}
