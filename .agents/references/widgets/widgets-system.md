# Référence Système : Widgets & Tableaux de Bord (PkgWidgets)

Le module PkgWidgets gère les widgets dynamiques affichés sur les tableaux de bord selon les rôles des utilisateurs (administrateur, formateur, apprenant).

---

## 1. Structure de Configuration JSON (parameters)
Chaque widget est enregistré en base de données dans la table widgets. Sa configuration comportementale est stockée au format JSON dans la colonne parameters :

`json
{
   link: projets.index,
  roles: {
    formateur: {
      titre: Mes Projets en Cours,
      couleur: info,
      icone: fas fa-folder-open
    },
    apprenant: {
      titre: Projets Assignés,
      couleur: primary,
      icone: fas fa-tasks
    }
  },
  dataSource: getStatistiquesProjets,
  conditions: {
    is_active: true
  }
}
`

### Détail des Propriétés :
- **link** : Route Laravel cible lors du clic sur le widget.
- **oles** : Paramètres d'affichage spécifiques par rôle (titre personnalisé, classe couleur AdminLTE, icône FontAwesome).
- **dataSource** : Nom de la méthode de Service responsable de fournir le décompte ou les données statistiques.
- **conditions** : Filtres SQL directs appliqués par défaut sur la requête.

---

## 2. Commande de Synchronisation
Après toute modification de configuration JSON, ajout ou mise à jour de widgets en base de données ou via un Seeder, il est impératif d'exécuter la synchronisation des widgets pour tous les profils utilisateurs :

`php
use Modules\PkgWidgets\Services\WidgetUtilisateurService;

// Synchronise les widgets des utilisateurs selon leurs rôles actuels
WidgetUtilisateurService::syncWidgetsFromRoles();
`

---

## 3. Emplacement dans les Modules
- Modèles : Modules\PkgWidgets\Models\Widget, Modules\PkgWidgets\Models\WidgetUtilisateur
- Services : Modules\PkgWidgets\Services\WidgetService, Modules\PkgWidgets\Services\WidgetUtilisateurService
- Contrôleurs : Modules\PkgWidgets\Controllers\WidgetController
