<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SanitizeInput
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
        // Nettoyer les entrées utilisateur
        $input = $request->all();
        
        array_walk_recursive($input, function (&$value) {
            if (is_string($value)) {
                // Supprimer les balises HTML dangereuses (sauf pour les champs spécifiques)
                $value = strip_tags($value);
                
                // Supprimer les espaces en début et fin
                $value = trim($value);
                
                // Convertir les caractères spéciaux en entités HTML
                // Note: Laravel fait déjà cela dans les vues avec {{ }}
            }
        });
        
        $request->merge($input);

        return $next($request);
    }
}
