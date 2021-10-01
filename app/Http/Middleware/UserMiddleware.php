<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        /*
        $roles = auth()->user() ? auth()->user()->user_role : '';
        
        if ($roles != '3') {
            throw new UnauthorizedHttpException('Unauthorized! Please Login');
        }
        return $next($request);
        */
        
        $roles = auth()->user() ? auth()->user()->roles()->pluck('role_id')->toArray() : [];
        
        if (!in_array('3',$roles)) {
            throw new UnauthorizedHttpException('Unauthorized! Please Login');
        }
        return $next($request);
    }
}
