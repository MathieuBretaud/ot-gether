<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Guard;

class Admin
{
    public function handle(Request $request, Closure $next)
    {
        if($request->user() && !$request->user()->is_admin){
            return response()->json('status', 'Vous n\'avez pas l\'autorisation d\'accéder à cette page.');
        }
        return $next($request);
    }
}
