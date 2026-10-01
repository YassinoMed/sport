<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecurityHeaders
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
        $response = $next($request);

        // Protection contre le clickjacking
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        
        // Protection contre le MIME type sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        
        // Protection XSS intégrée au navigateur
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        
        // Content Security Policy - Politique de sécurité du contenu
        $response->headers->set('Content-Security-Policy', 
            "default-src 'self'; " .
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://fonts.googleapis.com https://cdnjs.cloudflare.com; " .
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; " .
            "font-src 'self' https://fonts.gstatic.com data:; " .
            "img-src 'self' data: https:; " .
            "connect-src 'self';"
        );
        
        // Referrer Policy - Contrôle des informations de référence
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        
        // Permissions Policy - Contrôle des fonctionnalités du navigateur
        $response->headers->set('Permissions-Policy', 
            'geolocation=(), microphone=(), camera=()'
        );
        
        // HSTS - Force HTTPS (si vous utilisez HTTPS)
        // Décommenter si vous avez un certificat SSL
        // $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');

        return $response;
    }
}
