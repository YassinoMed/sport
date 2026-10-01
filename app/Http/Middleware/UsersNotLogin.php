<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersNotLogin
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
        if (Auth::check()) {
            $user = Auth::user();
            // Si l'utilisateur a le rôle 'user' OU s'il n'a aucun rôle assigné
            if ($user->hasRole('user') || $user->roles()->count() === 0) {
                $redirectUrl = config('ban.redirect_url', null);
                $errors = [
                    'login' => 'Vous n\'avez pas les droits d\'accès au système de gestion.',
                ];
                $responseCode = $request->header('X-Inertia') ? 303 : 302;
                
                // Déconnexion de l'utilisateur pour qu'il puisse se reconnecter avec un autre compte
                Auth::logout();
                
                if ($redirectUrl === null) {
                    return redirect()->route('login')->withErrors($errors);
                } else {
                    return redirect($redirectUrl, $responseCode)->withInput()->withErrors($errors);
                }
            }
        }
        
        return $next($request);
    }
}
