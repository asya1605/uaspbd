<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RequireRole
{
    public function handle(Request $request, Closure $next, $requiredRole)
    {
        $user = $request->session()->get('user');

        // Cek user login
        if (!$user || empty($user)) {
            return redirect('/login')->withErrors(['msg' => 'Silakan login dulu.']);
        }

        // Ambil role
        $role = $user['role'] ?? $user['nama_role'] ?? null;

        if (!$role) {
            return response('Akses ditolak: role tidak ditemukan.', 403);
        }

        // RULE: super_admin = akses penuh
        if ($role === 'super_admin') {
            return $next($request);
        }

        // RULE: admin = admin OR super_admin
        if ($requiredRole === 'admin' && $role === 'admin') {
            return $next($request);
        }

        // RULE: role spesifik
        if ($requiredRole === $role) {
            return $next($request);
        }

        return response('Forbidden (role tidak diizinkan).', 403);
    }
}
