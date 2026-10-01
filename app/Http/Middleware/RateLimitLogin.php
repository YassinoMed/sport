<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class RateLimitLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $key = $this->resolveRequestSignature($request);
        
        // Limite: 5 tentatives par minute par IP
        $maxAttempts = 5;
        $decayMinutes = 1;

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Trop de tentatives de connexion. Veuillez réessayer dans ' . $seconds . ' secondes.',
                    'retry_after' => $seconds
                ], Response::HTTP_TOO_MANY_REQUESTS);
            }

            return redirect()->back()
                ->withInput($request->only('email', 'remember'))
                ->withErrors([
                    'email' => 'Trop de tentatives de connexion. Veuillez réessayer dans ' . $seconds . ' secondes.',
                ]);
        }

        RateLimiter::hit($key, $decayMinutes * 60);

        $response = $next($request);

        return $response;
    }

    /**
     * Resolve request signature.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function resolveRequestSignature(Request $request)
    {
        return sha1(
            $request->method() .
            '|' . $request->server('SERVER_NAME') .
            '|' . $request->path() .
            '|' . $request->ip()
        );
    }
}
