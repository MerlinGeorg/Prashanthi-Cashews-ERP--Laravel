<?php
namespace App\Http\Middleware;

use Closure;
use Config;
use Illuminate\Support\Facades\Auth;

class AdminAuth
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
        if (Auth::user() /*&&  array_key_exists(Auth::user()->role,Config::get('constants.roles'))*/) {           
            return $next($request);
        }

        Auth::logout();
        return redirect('admin/login')->with('access_error','You don\'t have admin access!');        
        
    }
}