<?php

namespace App\Http\Middleware;

use App\Services\HttpClient;
use Closure;
use Illuminate\Http\Request;

class Operator
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (env('BRAMON_API_ROLE', HttpClient::ROLE_PUBLIC) !== HttpClient::ROLE_OPERATOR) {
            return abort(401);
        }

        return $next($request);
    }
}
