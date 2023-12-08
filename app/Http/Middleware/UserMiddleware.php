<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = config('users.'.$request->header('Authorization'));

        abort_if($user === null, 401);

        $request->attributes->set('user', $user);
        return $next($request);
    }
}
