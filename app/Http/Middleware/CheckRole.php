<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Session;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return \Illuminate\Http\Response
     */
    public function handle(Request $request, Closure $next, $role)
    {
        // Cek apakah user sudah login dan memiliki role yang sesuai
        if (Session::has('login')) {
            $user = User::find(Session::get('id'));
            if ($user->level === 'admin') {
                return $next($request);
            }

            if ($user->level === 'user') {
                return $next($request);
            }
        }

        // Jika tidak sesuai, redirect ke halaman lain (misalnya home atau login)
        return redirect('/');
    }
}
