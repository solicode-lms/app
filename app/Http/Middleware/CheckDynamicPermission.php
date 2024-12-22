<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\PkgAutorisation\Models\Permission;

class CheckDynamicPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Récupérer l'action actuelle (Contrôleur@Méthode)
        $action = $request->route()->getActionName();
        [$controller, $method] = explode('@', class_basename($action));
    
        // Supprimer "Controller" du nom du contrôleur
        $controller = lcfirst(preg_replace('/Controller$/', '', $controller));

        // Construire dynamiquement le nom de la permission
        $permission = "{$method}-{$controller}";
    
        // Vérifier si l'utilisateur a la permission ou une permission parent
        if (!Auth::user()->can($permission)) {
            abort(403, 'Access Denied - Insufficient Permissions');
        }
    
        return $next($request);
    }

    /**
     * Vérifie si l'utilisateur a la permission ou une permission parent.
     *
     * @param  \App\Models\User  $user
     * @param  string  $permission
     * @return bool
     */
    protected function hasPermissionOrParent($user, $permission)
    {
        // Vérifier si l'utilisateur a directement la permission
        if ($user->can($permission)) {
            return true;
        }

        // Vérifier si une permission parent contient cette permission
        $permissionModel = Permission::where('name', $permission)->first();
        if ($permissionModel) {
            // Vérifier les parents de la permission
            foreach ($permissionModel->parents as $parentPermission) {
                if ($user->can($parentPermission->name)) {
                    return true;
                }
            }
        }

        return false;
    }
}
