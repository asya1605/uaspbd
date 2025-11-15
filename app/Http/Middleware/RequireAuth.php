<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RequireAuth
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->session()->get('user');

        if (!$user || empty($user)) {
            $request->session()->forget('user'); // bersih
            return redirect('/login')->withErrors([
                'msg' => 'Silakan login terlebih dahulu.'
            ]);
        }

        return $next($request);
    }
}
