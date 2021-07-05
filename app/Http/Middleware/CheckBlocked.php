<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckBlocked
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
        if (auth()->check() && auth()->user()->removed == 1) {
            $message = 'This account was deleted, please create another one.';
            auth()->logout();
            return redirect()->route('login')->withMessage($message);
        }

        return $next($request);
    }
}
