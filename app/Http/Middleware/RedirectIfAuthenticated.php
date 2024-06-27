<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
		if (Auth::guard($guard)->check()) {
			if(Auth::user()->role==0){
				return redirect('admin/dashboard');
			}
            if(Auth::user()->role==1){
                return redirect('admin/job/create');
            }
			else{
				Session::flush();
				return redirect('/login')->with('hasError','You are not Valid User');
			}
		}

        return $next($request);
    }
}
