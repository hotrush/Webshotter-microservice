<?php

namespace App\Http\Middleware;

use Closure;

class Authenticate
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
        $accessKey = env('ACCESS_KEY');

        if (!$request->query('key') || $request->query('key') !== $accessKey)
        {
            return response('Unauthorized.', 401);
        }

        return $next($request);
    }
}
