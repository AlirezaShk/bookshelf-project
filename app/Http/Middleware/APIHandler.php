<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class APIHandler
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
        $all = $request->all();
        $request->headers->set('Content-Type', 'application/json');
        foreach($all as $k => $v) {
            $request->merge([$k => $v]);
        }
        return $next($request);
    }
}
