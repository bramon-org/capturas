<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Services\HttpClient;
use Closure;

class Editor
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
        if (env('BRAMON_API_ROLE', HttpClient::ROLE_GUEST) !== HttpClient::ROLE_EDITOR) {
            return abort(401);
        }

        return $next($request);
    }
}
