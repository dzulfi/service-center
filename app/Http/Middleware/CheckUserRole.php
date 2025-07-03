<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect('/login'); // Redirect ke login jika belum login
        }

        $user = auth()->user();

        foreach ($roles as $role) {
            if ($user->hasRole($role)) { // Menggunakan helper hasRole() di model User
                return $next($request);
            }
        }

        // Jika user tidak memiliki salah satu role yang diizinkan
        abort(403, 'Akses Ditolak. Anda tidak memiliki izin yang cukup.');
    }
}
