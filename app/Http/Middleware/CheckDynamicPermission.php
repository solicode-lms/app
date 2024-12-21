<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckDynamicPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        // Récupérer l'action actuelle (Contrôleur@Méthode)
        $action = $request->route()->getActionName();
        [$controller, $method] = explode('@', class_basename($action));
    
        // Supprimer "Controller" du nom du contrôleur
        $controllerName = str_replace('Controller', '', $controller);
    
        // Construire dynamiquement le nom de la permission
        $permission = "{$method}-{$controller}";
    
        // Vérifier si l'utilisateur a la permission
        if (!Auth::user()->can($permission)) {
            abort(403, 'Access Denied - Gate');
        }

        // // Vérifier si l'utilisateur a la permission
        // if (!Gate::allows($permission)) {
        //     abort(403, 'Access Denied - Gate');
        // }
    
        return $next($request);
    }
    
}
