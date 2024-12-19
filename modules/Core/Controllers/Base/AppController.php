<?php

namespace Modules\Core\Controllers\Base;

use App\Http\Controllers\Controller; // Importation du contrôleur de base de l'application Laravel.

/**
 * AppController est le contrôleur principal pour tous les contrôleurs de l'application.
 * 
 * - Il sert de base pour les contrôleurs définis dans le module "Core".
 * - Tous les contrôleurs spécifiques du module devraient hériter de ce contrôleur.
 * - En étendant `Controller`, il bénéficie des fonctionnalités fournies par Laravel, 
 *   telles que les traits `AuthorizesRequests` et `ValidatesRequests`.
 */
class AppController extends Controller
{
    // Ajoutez ici des propriétés ou des méthodes communes à tous les contrôleurs de ce module.
}
