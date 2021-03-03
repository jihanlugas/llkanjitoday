<?php

namespace App\Http\Middleware;

use App\Libraries\Helpers;
use Closure;

class RequestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
//        $request->request = Helpers::keySnake($request->request);
        return $next($request);
    }
}
