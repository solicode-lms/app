---
name: app-controller
description: Expert de l'architecture des Contrôleurs, FormRequests, Web routes et Traits d'action.
---

# Skill : Expert Contrôleurs (App)

## 🎯 Périmètre Global
**Mission** : Assurer la bonne implémentation de la couche HTTP (Controllers), de la validation des requêtes entrantes (FormRequests), des routes web, et de l'extraction de la logique transversale dans des Traits.

## 📐 Règles d'Architecture

### 1. Fat Models, Skinny Controllers (ou Services)
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

### 4. Code Généré (Gapp)
- Respecter les fichiers générés par Gapp :
  - Surcharger les méthodes dans les contrôleurs enfants (qui héritent de `Base...Controller`) plutôt que de modifier les fichiers de base générés.
  - L'en-tête de protection Gapp (`// Ce fichier est maintenu par ESSARRAJ Fouad`) ne doit être supprimé que si une modification directe est inévitable et validée par l'utilisateur.

### 5. Gestion des Permissions (Nouvelles Méthodes)
- Lors de l'ajout d'une nouvelle méthode personnalisée dans un contrôleur, le Middleware de vérification dynamique des permissions cherche une permission correspondante qui n'existe potentiellement pas.
- **Règle** : Il faut toujours ignorer la permission dynamique pour la nouvelle méthode via l'annotation PHPDoc `@DynamicPermissionIgnore`.
- **Ensuite** : Relier manuellement la méthode à une permission existante (la plus proche fonctionnellement) via `$this->authorizeAction('nom_action');` (ex: `update`, `view`, `create`).

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
