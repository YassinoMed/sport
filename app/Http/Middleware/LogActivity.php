<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class LogActivity
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

        // On n'enregistre que les requêtes qui ne sont pas de simples affichages de ressources statiques
        // et on évite de logger la consultation des logs eux-mêmes pour éviter une boucle infinie
        if (!$request->is('activity-logs*') && !$request->is('assets/*') && !$request->is('vendor/*')) {
            try {
                $user = Auth::user();
                
                ActivityLog::create([
                    'user_id' => $user ? $user->id : null,
                    'user_name' => $user ? $user->name : 'Invité',
                    'action' => $this->getActionDescription($request),
                    'url' => $request->fullUrl(),
                    'method' => $request->method(),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'payload' => $request->except(['password', 'password_confirmation', '_token']),
                ]);
            } catch (\Exception $e) {
                // On log l'erreur en silence pour ne pas bloquer l'utilisateur
                \Illuminate\Support\Facades\Log::error('Erreur lors de l\'enregistrement de l\'activité : ' . $e->getMessage());
            }
        }

        return $response;
    }

    protected function getActionDescription(Request $request)
    {
        $method = $request->method();
        $path = $request->path();

        if ($method === 'GET') {
            return "Consultation de la page : " . $path;
        } elseif ($method === 'POST') {
            return "Création / Soumission sur : " . $path;
        } elseif ($method === 'PUT' || $method === 'PATCH') {
            return "Mise à jour sur : " . $path;
        } elseif ($method === 'DELETE') {
            return "Suppression sur : " . $path;
        }

        return $method . " " . $path;
    }
}
