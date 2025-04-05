<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\PkgAutorisation\Models\Permission;
use ReflectionMethod;

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
        [$controllerClass, $method] = explode('@', $action);
        [$controller, $method] = explode('@', class_basename($action));

        // 🔍 Vérifier si la méthode a une annotation @DynamicPermissionIgnore
        if ($this->hasDynamicPermissionIgnoreAnnotation($controllerClass, $method)) {
            return $next($request);
        }

        // Supprimer "Controller" du nom du contrôleur
        $controller = lcfirst(preg_replace('/Controller$/', '', $controller));

        // Construire dynamiquement le nom de la permission
        $permission = "{$method}-{$controller}";
     
        // ForcePasswordChange
        if (Auth::check() && Auth::user()->must_change_password &&  $controller != 'profile') {
            return redirect()
            ->route('profiles.index',['action' => 'edit' , 'id'=> Auth::user()->profile->id]);
        }

        // Bypass les vérifications si l'utilisateur est Super Admin
        if (Auth::user()->hasRole('root')) {
            return $next($request);
        }

      
       
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

     /**
     * Vérifie si la méthode du contrôleur possède l'annotation @DynamicPermissionIgnore
     */
    protected function hasDynamicPermissionIgnoreAnnotation($controllerClass, $method)
    {
        try {
            $reflection = new ReflectionMethod($controllerClass, $method);
            $docComment = $reflection->getDocComment();
            return $docComment && str_contains($docComment, '@DynamicPermissionIgnore');
        } catch (\ReflectionException $e) {
            return false;
        }
    }
}
