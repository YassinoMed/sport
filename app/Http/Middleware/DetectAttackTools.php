<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DetectAttackTools
{
    /**
     * User agents connus des outils d'attaque
     */
    protected $suspiciousUserAgents = [
        'sqlmap',
        'nikto',
        'nmap',
        'masscan',
        'nessus',
        'openvas',
        'metasploit',
        'burp',
        'havij',
        'acunetix',
        'w3af',
        'dirbuster',
        'wpscan',
        'joomla',
        'wordpress',
        'python-requests',
        'curl',
        'wget',
        'scanner',
        'bot',
        'spider',
        'crawler',
        'scraper',
    ];

    /**
     * Patterns suspects dans les requêtes
     */
    protected $suspiciousPatterns = [
        // SQL Injection patterns
        '/(\%27)|(\')|(\-\-)|(\%23)|(#)/i',
        '/((\%3D)|(=))[^\n]*((\%27)|(\')|(\-\-)|(\%3B)|(;))/i',
        '/\w*((\%27)|(\'))((\%6F)|o|(\%4F))((\%72)|r|(\%52))/i',
        '/union.*select/i',
        '/select.*from/i',
        '/insert.*into/i',
        '/delete.*from/i',
        '/drop.*table/i',
        '/update.*set/i',
        '/exec(\s|\+)+(s|x)p\w+/i',
        
        // XSS patterns
        '/<script[^>]*>.*?<\/script>/is',
        '/javascript:/i',
        '/on\w+\s*=/i',
        
        // Path traversal
        '/\.\.\//i',
        '/\.\.\\\\/i',
        
        // Command injection
        '/;.*?(ls|cat|wget|curl|nc|bash|sh|cmd)/i',
        '/\|.*?(ls|cat|wget|curl|nc|bash|sh|cmd)/i',
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $userAgent = $request->userAgent() ?? '';
        $fullUrl = $request->fullUrl();
        $input = json_encode($request->all());

        // Exception pour le rapport (filtres multiples vides)
        if ($request->is('reports') || $request->is('reports/*') || $request->routeIs('reports.*')) {
            // On bypass la vérification des patterns stricts pour cette route spécifique
            // car elle utilise beaucoup de paramètres GET qui peuvent être vides
            return $next($request);
        }

        if ($request->is('coach/show/*') || $request->routeIs('coach.show')) {
            return $next($request);
        }

        // Exception pour les dépenses (filtres multiples)
        if ($request->is('expenses') || $request->is('expenses/*') || $request->routeIs('expenses.*')) {
            return $next($request);
        }

        // Exception pour les utilisateurs (filtres multiples)
        if ($request->is('allUsers') || $request->is('allUsers/*') || $request->routeIs('allUsers.*')) {
            return $next($request);
        }

        // Exception pour les paramètres, logs d'activité, rôles et modérateurs
        if ($request->is('settings') || $request->routeIs('settings.*') || 
            $request->is('activity-logs') || $request->routeIs('activity-logs.*') ||
            $request->is('roles') || $request->is('roles/*') || $request->routeIs('roles.*') ||
            $request->is('moderators') || $request->is('moderators/*') || $request->routeIs('moderators.*')) {
            return $next($request);
        }

        // Détecter les User-Agents suspects
        foreach ($this->suspiciousUserAgents as $suspiciousAgent) {
            if (stripos($userAgent, $suspiciousAgent) !== false) {
                $this->logSuspiciousActivity($request, "Suspicious User-Agent detected: {$suspiciousAgent}");
                return $this->blockRequest("Accès refusé - Outil d'attaque détecté");
            }
        }

        // Détecter les patterns suspects dans l'URL et les paramètres
        $dataToCheck = $fullUrl . ' ' . $input;
        
        foreach ($this->suspiciousPatterns as $pattern) {
            if (preg_match($pattern, $dataToCheck)) {
                $this->logSuspiciousActivity($request, "Suspicious pattern detected: {$pattern}");
                return $this->blockRequest("Accès refusé - Requête suspecte détectée");
            }
        }

        // Détecter les tentatives de SQLMap (paramètres multiples identiques)
        $queryParams = $request->query();
        if (count($queryParams) > 0) {
            // Filtrer uniquement les valeurs scalaires (string, int) pour array_count_values
            $scalarParams = array_filter($queryParams, function($value) {
                return is_scalar($value);
            });
            
            if (count($scalarParams) > 0) {
                $paramValues = array_count_values($scalarParams);
                foreach ($paramValues as $value => $count) {
                    if ($count > 5) { // Plus de 5 paramètres avec la même valeur
                        $this->logSuspiciousActivity($request, "Possible SQLMap scan detected");
                        return $this->blockRequest("Accès refusé - Scan automatisé détecté");
                    }
                }
            }
        }

        return $next($request);
    }

    /**
     * Log suspicious activity
     */
    protected function logSuspiciousActivity(Request $request, string $reason)
    {
        Log::warning('🚨 SECURITY ALERT - Attack Tool Detected', [
            'reason' => $reason,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'timestamp' => now()->toDateTimeString(),
        ]);
    }

    /**
     * Block the request
     */
    protected function blockRequest(string $message)
    {
        return response()->json([
            'error' => $message,
            'code' => 'SECURITY_VIOLATION',
        ], 403);
    }
}
