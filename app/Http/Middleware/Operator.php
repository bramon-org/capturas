<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Services\HttpClient;
use Closure;
use Illuminate\Support\Facades\Log;

class Operator
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
        if (env('BRAMON_API_ROLE', HttpClient::ROLE_GUEST) !== HttpClient::ROLE_OPERATOR) {
            return abort(401);
        }

        return $next($request);
    }
}
