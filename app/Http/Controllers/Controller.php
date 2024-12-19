<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // Traite les autorisations d'accès pour les utilisateurs.
use Illuminate\Foundation\Validation\ValidatesRequests;  // Gère la validation des données des requêtes HTTP.
use Illuminate\Routing\Controller as BaseController;      // Contrôleur de base fourni par Laravel.

class Controller extends BaseController
{
    // Utilisation des traits Laravel pour ajouter des fonctionnalités au contrôleur
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Le trait AuthorizesRequests permet de gérer les autorisations :
     * - Vérifie si un utilisateur a les permissions nécessaires pour exécuter une action.
     * - Utilise les "policies" et "gates" définies dans l'application pour centraliser les règles d'accès.
     * - Fournit des méthodes comme `authorize`, `authorizeForUser` et `can`.
     * 
     * Exemple d'utilisation :
     * $this->authorize('update', $post); // Vérifie si l'utilisateur peut mettre à jour le post.
     */

    /**
     * Le trait ValidatesRequests facilite la validation des données :
     * - Permet de valider les données envoyées par l'utilisateur via des requêtes HTTP.
     * - Fournit la méthode `validate()` pour appliquer des règles de validation sur les données.
     * - Retourne automatiquement une réponse avec les erreurs en cas d'échec de validation.
     * 
     * Exemple d'utilisation :
     * $validatedData = $this->validate($request, [
     *     'title' => 'required|string|max:255',
     *     'content' => 'required|string',
     * ]);
     */
}
