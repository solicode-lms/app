---
name: app-controller
description: Expert de l'architecture des Contrôleurs, FormRequests, Web routes et Traits d'action.
---

# Skill : Expert Contrôleurs (App)

## 🎯 Périmètre Global
**Mission** : Assurer la bonne implémentation de la couche HTTP (Controllers), de la validation des requêtes entrantes (FormRequests), des routes web, et de l'extraction de la logique transversale dans des Traits.

## 📐 Règles d'Architecture

### 1. Héritage des Contrôleurs (Règle Stricte)
- **Héritage Obligatoire** : Tous les contrôleurs DOIVENT hériter soit de `Modules\Core\Controllers\Base\AdminController` soit de `PublicController`.
  - `AdminController` : Pour toutes les interfaces nécessitant une authentification et une gestion fine des permissions/droits d'accès.
  - `PublicController` : Pour les interfaces accessibles publiquement (non restreintes).
- **Raison** : La vérification des droits d'accès est gérée dynamiquement et centralisée dans `AdminController`. Ne jamais hériter directement du `Controller` de base de Laravel.

### 2. Fat Models, Skinny Controllers (ou Services)
- Le contrôleur ne doit contenir que la logique liée à la requête HTTP :
  - Autorisation (Gate / Policy).
  - Validation (via `FormRequest`).
  - Appel à la couche Service (`app-service`) pour la logique métier.
  - Retour de la réponse (Vue, JSON, Redirection).
- **Séparation des Responsabilités (CRITIQUE)** :
  - Toute manipulation d'un service doit être effectuée en présence du skill `app-service`.
  - Toutes les règles de gestion et tous les calculs métier doivent être implémentés dans le service concerné.
  - Toutes les opérations de modification (création, mise à jour, changement d'état) sur un objet doivent être effectuées par son service, car le service est le seul responsable de la gestion de ses données et de l'application des règles de gestion avant et après chaque opération.
  - Le contrôleur peut sélectionner des données depuis la base de données (pour l'affichage), mais pour les opérations de modification de la base de données, il doit impérativement faire appel au service concerné.

**Exemples : Contrôleur vs Service**
- ❌ **À NE PAS FAIRE DANS LE CONTRÔLEUR (Doit être dans le Service)** :
  ```php
  // Calculs ou règles de gestion
  $dureeMax = ($realisationQcm->qcm->duree_minutes ?? 60) * 60;
  $tempsEcoule = now()->diffInSeconds($realisationQcm->date_debut);
  $timeRemaining = max(0, $dureeMax - $tempsEcoule);

  // Modification directe et règles métier
  $realisationQcm->date_debut = now();
  $etatEnCours = \Modules\PkgQcm\Models\EtatRealisationQcm::where('reference', 'EN_COURS')->first();
  $realisationQcm->etat_realisation_qcm_id = $etatEnCours->id;
  $realisationQcm->save();
  ```
- ✅ **À FAIRE DANS LE CONTRÔLEUR** :
  ```php
  // Appel du service pour exécuter la logique de démarrage
  $this->realisationQcmService->start($realisationQcm);
  
  // Récupération du temps calculé par le service
  $timeRemaining = $this->realisationQcmService->calculateTimeRemaining($realisationQcm);
  ```

### 2. Validation (FormRequests)
- Toujours utiliser une classe `FormRequest` pour valider les données entrantes.
- Ne jamais utiliser `$request->validate()` directement dans le contrôleur.

### 3. Traits
- Si une logique de contrôleur est commune à plusieurs entités (ex: Export CSV, Import), elle doit être extraite dans un `Trait` situé dans le dossier approprié du module ou dans un dossier partagé.

### 4. Code Généré (Gapp) et Surcharge des Méthodes CRUD
- Respecter les fichiers générés par Gapp :
  - L'en-tête de protection Gapp (`// Ce fichier est maintenu par ESSARRAJ Fouad`) ne doit être supprimé que si une modification directe est inévitable et validée par l'utilisateur.
- **Surcharge des Vues CRUD (Template Method Pattern)** : 
  - Ne **JAMAIS** surcharger entièrement les méthodes `edit()`, `create()` ou `show()` en copiant-collant leur logique.
  - Surcharger **uniquement** les sous-méthodes dédiées à la préparation des données : `dataForEditView(string $id): array`, `dataForCreateView(): array`, et `dataForShowView(string $id): array`.
  - **Pattern de surcharge :**
    ```php
    protected function dataForEditView(string $id): array {
        // 1. Appeler le parent pour récupérer les données de base (variables communes, relations, etc.)
        $viewData = parent::dataForEditView($id);
        
        // 2. Injecter, modifier ou filtrer des données
        $viewData['ma_nouvelle_variable'] = 'Valeur';
        
        // 3. Retourner le tableau fusionné
        return $viewData;
    }
    ```
  - *(Voir `cahiers-charges/PkgGapp/issues/002-generalisation-data-for-views-pattern.md` pour plus de détails).*

### 5. Gestion des Permissions (Nouvelles Méthodes)
- Lors de l'ajout d'une nouvelle méthode personnalisée dans un contrôleur, le Middleware de vérification dynamique des permissions cherche une permission correspondante qui n'existe potentiellement pas.
- **Règle Stricte** : Il faut TOUJOURS ignorer la permission dynamique pour les nouvelles méthodes via l'annotation PHPDoc `/** @DynamicPermissionIgnore */`.
- **Ensuite, vérifier manuellement l'accès dans la méthode** : 
  - **Option 1 (Standard)** : Relier la méthode à une permission CRUD existante de l'entité via `$this->authorizeAction('nom_action');` (ex: `update`, `view`).
  - **Option 2 (Permission Personnalisée)** : Si une permission exacte a été créée (ex: `passer-qcm` via un Seeder), la vérifier directement avec `abort_if(!auth()->user()->can('passer-qcm'), 403, 'Permission refusée');`.

**Exemple :**
```php
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');
        // ... logique
    }
```

---

## ⚡ Actions (Orchestration)

### Action A : Configurer un Filtre ViewState (Controller)
> **Description** : Ajouter ou modifier un paramètre de filtrage via ViewState dans un contrôleur.
- **Capacités Utilisées** :
  - `capacités/capacité-view-state.md`
- **Entrées** : `Modèle cible`, `Filtre désiré`
- **Sorties** : Code injecté dans le Controller (méthode `index` ou `prepareDataForIndexView`).
- **📝 Instructions d'Orchestration** :
  1. Utiliser la capacité `capacité-view-state.md`.
  2. Ajouter `$this->viewState->set(...)` au bon endroit dans le contrôleur enfant.

---

## 🛠️ Capacités (Savoir-Faire Technique)
*Documentation des fichiers situés dans le dossier `.agent/skills/app-controller/capacités/`*

### 1. `capacité-view-state.md`
- **Rôle** : Base de connaissances sur la manipulation du ViewState (`where`, `scope`, relations) dans les contrôleurs.

### 2. `../capacites-globales/capacité-crud-jobs.md` (Capacité Globale)
- **Rôle** : Explication du mécanisme de traitement asynchrone des requêtes longues via `$this->service->getCrudJobToken()`.
