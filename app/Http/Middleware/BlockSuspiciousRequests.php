<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class BlockSuspiciousRequests
{
    /**
     * Nombre maximum de requêtes par minute par IP
     */
    protected $maxRequestsPerMinute = 1000;

    /**
     * Nombre maximum de 404 par minute avant blocage
     */
    protected $max404PerMinute = 100;

    /**
     * Durée de blocage en minutes
     */
    protected $blockDurationMinutes = 10;

    /**
     * IPs en liste blanche (ne jamais bloquer)
     */
    protected $whitelist = [
        '127.0.0.1',
        'localhost',
        // Ajoutez vos IPs de confiance ici
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $ip = $request->ip();

        // Ne pas bloquer les IPs en liste blanche ou les utilisateurs connectés
        if (in_array($ip, $this->whitelist) || $request->user()) {
            return $next($request);
        }

        // Vérifier si l'IP est déjà bloquée
        if ($this->isBlocked($ip)) {
            $this->logBlockedAttempt($request);
            return response()->json([
                'error' => 'Votre IP a été temporairement bloquée pour activité suspecte',
                'code' => 'IP_BLOCKED',
                'retry_after' => $this->getBlockTimeRemaining($ip),
            ], 429);
        }

        // Compter les requêtes
        $this->incrementRequestCount($ip);

        // Vérifier si le seuil est dépassé
        if ($this->isSuspiciousActivity($ip)) {
            $this->blockIp($ip);
            $this->logSuspiciousActivity($request, 'Too many requests');
            
            return response()->json([
                'error' => 'Trop de requêtes. Votre IP a été bloquée temporairement.',
                'code' => 'RATE_LIMIT_EXCEEDED',
                'blocked_for_minutes' => $this->blockDurationMinutes,
            ], 429);
        }

        $response = $next($request);

        // Compter les erreurs 404
        if ($response->status() == 404) {
            $this->increment404Count($ip);
            
            if ($this->tooMany404($ip)) {
                $this->blockIp($ip);
                $this->logSuspiciousActivity($request, 'Too many 404 errors - possible directory scanning');
            }
        }

        return $response;
    }

    /**
     * Vérifier si l'IP est bloquée
     */
    protected function isBlocked(string $ip): bool
    {
        return Cache::has("blocked_ip:{$ip}");
    }

    /**
     * Bloquer une IP
     */
    protected function blockIp(string $ip): void
    {
        Cache::put("blocked_ip:{$ip}", true, now()->addMinutes($this->blockDurationMinutes));
        
        Log::warning('🚨 IP BLOCKED', [
            'ip' => $ip,
            'duration_minutes' => $this->blockDurationMinutes,
            'timestamp' => now()->toDateTimeString(),
        ]);
    }

    /**
     * Obtenir le temps restant de blocage
     */
    protected function getBlockTimeRemaining(string $ip): int
    {
        $key = "blocked_ip:{$ip}";
        if (Cache::has($key)) {
            return Cache::get($key) ? $this->blockDurationMinutes * 60 : 0;
        }
        return 0;
    }

    /**
     * Incrémenter le compteur de requêtes
     */
    protected function incrementRequestCount(string $ip): void
    {
        $key = "request_count:{$ip}";
        $count = Cache::get($key, 0);
        Cache::put($key, $count + 1, now()->addMinute());
    }

    /**
     * Incrémenter le compteur de 404
     */
    protected function increment404Count(string $ip): void
    {
        $key = "404_count:{$ip}";
        $count = Cache::get($key, 0);
        Cache::put($key, $count + 1, now()->addMinute());
    }

    /**
     * Vérifier si l'activité est suspecte
     */
    protected function isSuspiciousActivity(string $ip): bool
    {
        $count = Cache::get("request_count:{$ip}", 0);
        return $count > $this->maxRequestsPerMinute;
    }

    /**
     * Vérifier si trop de 404
     */
    protected function tooMany404(string $ip): bool
    {
        $count = Cache::get("404_count:{$ip}", 0);
        return $count > $this->max404PerMinute;
    }

    /**
     * Logger une activité suspecte
     */
    protected function logSuspiciousActivity(Request $request, string $reason): void
    {
        Log::warning('🚨 SUSPICIOUS ACTIVITY DETECTED', [
            'reason' => $reason,
            'ip' => $request->ip(),
            'url' => $request->fullUrl(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now()->toDateTimeString(),
        ]);
    }

    /**
     * Logger une tentative d'accès bloquée
     */
    protected function logBlockedAttempt(Request $request): void
    {
        Log::info('🚫 BLOCKED IP ATTEMPT', [
            'ip' => $request->ip(),
            'url' => $request->fullUrl(),
            'timestamp' => now()->toDateTimeString(),
        ]);
    }
}
