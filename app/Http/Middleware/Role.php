<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role1, $role2, $role3, $role4): Response
    {
        if(Auth::user()->role != $role1 && Auth::user()->role != $role2 && Auth::user()->role != $role3 && Auth::user()->role != $role4){
            return abort(403);
        }
        return $next($request);
    }
}
