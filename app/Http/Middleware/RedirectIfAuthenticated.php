<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

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
        if (Auth::guard('tutor')->check()) {
            $guard = 'tutor';
        };

        switch ($guard) {
            case 'tutor' :
                if (Auth::guard($guard)->check()) {
                    return redirect('/tutors/home');
                }
                break;
            default :
                if (Auth::guard($guard)->check()) {
                    return redirect('/users/home');
                }
                break;
        }
        return $next($request);
    }
}
