<?php

namespace Modules\Core\Controllers\Base;

/**
 * PublicController est un contrôleur destiné aux fonctionnalités accessibles publiquement.
 * 
 * - Il hérite de AppController, permettant de partager des fonctionnalités communes avec les autres contrôleurs du module "Core".
 * - Ce contrôleur peut être utilisé pour des pages ou des actions qui ne nécessitent pas d'authentification, 
 *   comme une page d'accueil publique ou des pages d'information.
 */
class PublicController extends AppController
{
    // Ajoutez ici des propriétés ou des méthodes spécifiques pour gérer les pages ou fonctionnalités publiques.
}
