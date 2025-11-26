<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Alert;
use Session;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Session::get('level')=='admin' && Session::get('login')==TRUE) {
            return $next($request);
        }
        else{
            Session::flush();
            Alert::error('Gagal Akses','Hanya dapat diakses admin!');
            return redirect('/login')->with('gagal','Hanya dapat diakses admin!');
        }
        return redirect('/login')->with('gagal','Hanya dapat diakses admin!');
    }
}
