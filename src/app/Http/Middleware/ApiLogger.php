<?php

namespace App\Http\Middleware;

use App\Traits\ClickHouseLoggable;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiLogger
{
    use ClickHouseLoggable;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $this->logToClickHouse($request->getRequestUri(), $request->getMethod(), $request->getContent());
        return $next($request);
    }
}
