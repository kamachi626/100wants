<?php

namespace App\Http\Middleware;

use Closure;
use App\Auth;

class Authenticate
{
    
    /* ログインチェック */
    public function handle($req, Closure $next, $guard = null)
    {
    	if(is_null(Auth::user($req))) {
    		return redirect("/");
    	}
        return $next($req);
    }
    
}
