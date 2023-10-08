<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\ThrottleRequests;

class ThrottleFailedLogins
{
    public function handle($request, Closure $next, $maxAttempts = 3, $decayMinutes = 30)
    {
        $key = $this->resolveRequestSignature($request);

        if (Cache::has($key . ':lockout')) {
            abort(429, 'Too many login attempts. Please try again later.');
        }

        $limiter = new ThrottleRequests($this->resolveRequestSignature($request), $maxAttempts, $decayMinutes);

        if ($limiter->tooManyAttempts()) {
            Cache::put($key . ':lockout', time() + $decayMinutes * 60, $decayMinutes);

            abort(429, 'Too many login attempts. Please try again later.');
        }

        return $next($request);
    }

    protected function resolveRequestSignature($request)
    {
        return sha1(
            $request->method() .
            '|' . $request->server('SERVER_NAME') .
            '|' . $request->path() .
            '|' . $request->ip()
        );
    }
}
