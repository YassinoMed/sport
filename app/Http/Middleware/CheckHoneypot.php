<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CheckHoneypot
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
        // Le nom du champ piège (doit sembler légitime pour un bot)
        $honeypotField = 'website_url';

        // Debug
        // Log::info('Honeypot check', ['all' => $request->all(), 'has_field' => $request->has($honeypotField), 'value' => $request->input($honeypotField)]);

        // Si le champ existe et n'est pas vide, c'est un bot
        if ($request->has($honeypotField) && !empty($request->input($honeypotField))) {
            Log::warning('🤖 Bot detected via Honeypot', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'input' => $request->input($honeypotField)
            ]);

            // On retourne une page blanche ou une erreur 422 sans explication
            return response('Error processing request', 422);
        }

        return $next($request);
    }
}
