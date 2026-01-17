# Logique Métier : Création Projet Planifié

Ce document décrit l'algorithme complet de création d'un projet planifié (avec Session), en liant la logique métier aux méthodes du code source.

**Fichier Principal** : `Modules\PkgCreationProjet\Services\ProjetService.php` (et ses Traits)
**Workflow de Référence** : `creation_projet_planifie_workflow.mmd`

---

## I. Validation & Pré-traitement

**Méthode** : `ProjetCrudTrait::beforeCreateRules($data)`

1. **Injection du Formateur** :
   - SI l'utilisateur courant a le rôle `formateur` :
     - Récupérer `formateur_id` depuis la session (`SessionState`).
     - Injecter `data['formateur_id']`.
     - Lancer une `BlException` si l'ID est introuvable.

---

## II. Persistance

**Méthode** : `Parent::create($data)` (BaseService)

1. Insertion des données de base du projet dans la table `projets`.

---

## III. Construction de la Structure (Post-Création)

**Méthode** : `ProjetRelationsTrait::initializeProjectStructure($projet, $session)`
*Déclenché par `ProjetCrudTrait::afterCreateRules`*

### Phase 1 : Tâches Standards
**Source** : `ProjetService::getTasksConfig($session)`

*   **Algorithme** :
    1. Récupérer la configuration des tâches (Liste statique ou dynamique selon la session).
    2. **POUR CHAQUE** `taskData` dans la config :
        - SI `taskData['type'] == 'Tutoriels'` :
            - Sauvegarder `phaseProjetApprentissageId` pour plus tard.
            - **CONTINUER** (Sauter cette itération).
        - SI la tâche n'existe pas déjà (`Tache::where...->exists()`) :
            - Créer la tâche via `TacheService::create()`.
            - Paramètres clés : `titre`, `phase_evaluation_id`, `priorite` (auto-incrémenté).

### Phase 2 : Mobilisations & Tutoriels
**Condition** : SI `phaseProjetApprentissageId` est défini (le marqueur "Tutoriels" a été trouvé).

**Méthode** : `ProjetRelationsTrait::createMobilisationFromSession($projet, $session)`

*   **Algorithme** :
    1. **POUR CHAQUE** `alignementUa` dans `session->alignementUas` :
        - Préparer les données (`projet_id`, `unite_apprentissage_id`).
        - Appeler `MobilisationUaService::create($data)`.

        **Cascade (Effets de bord via MobilisationUaService)** :
        - `MobilisationUaService::afterCreateRules` est déclenché :
            a. **Génération Tâches Tutoriels** :
               - Appel `TacheActionsTrait::createTasksFromUa($projetId, $uaId)`.
               - Crée une tâche "Tutoriel" pour chaque Chapitre de l'UA.
            b. **Synchronisation Réalisations** :
               - Appel `MobilisationUaService::triggerSyncTacheEtRealisation`.
               - Met à jour les notes et réalisations N2/N3.

---

## IV. Finalisation

**Méthode** : `ProjetCrudTrait::afterCreateRules` (suite)

1. **Livrables par défaut** :
   - Appel `addDefaultLivrables($projet)`.
2. **Ré-ordonnancement** :
   - Appel `reorderTasksByPhase($projetId)` pour garantir l'ordre chronologique (Analyse < Apprentissage < Réalisation).
