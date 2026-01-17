# Spécification Technique : Workflow Synchronisation Mobilisation UA

**Référence Diagramme** : [`mobilisation_sync_workflow.mmd`](./mobilisation_sync_workflow.mmd)
**Module Responsable** : `PkgRealisationProjets` & `PkgCompetences`

---

## 1. Contexte et Objectifs
Dans la pédagogie par compétences, certaines tâches ne sont pas "fixes" dans le projet au départ. Elles apparaissent dynamiquement parce qu'un Formateur décide de "Mobiliser" une Unité d'Apprentissage (UA) spécifique.
Ce workflow gère cette dynamicité : Ajout/Retrait de tâches en temps réel pour tous les apprenants actifs.

---

## 2. Scénarios Métier

### A. Ajout d'une Mobilisation UA
**Déclencheur** : `MobilisationUaService::create(projet_id, ua_id)`

**Logique de Synchronisation** :
1.  **Action Primaire** : `MobilisationUaService` enregistre le lien Projet <-> UA.
2.  **Détection** : Le système détecte que le périmètre du projet a changé.
3.  **Propagation (Hook)** : `MobilisationUaService` demande à `RealisationProjetService` de se synchroniser (`syncTasksWithMobilisation`).
4.  **Injection** :
    - `RealisationProjetService` demande à `TacheService` les tâches liées à cette UA (souvent des tâches de niveau 2 ou 3).
    - Pour chaque apprenant ayant une réalisation active sur ce projet :
        - Injection des nouvelles tâches via `RealisationTacheService`.

### B. Suppression d'une Mobilisation UA
**Déclencheur** : `MobilisationUaService::delete()`

**Logique de Nettoyage Sécurisé** :
1.  **Vérification** : Avant de supprimer le lien UA, le système doit nettoyer les tâches orphelines.
2.  **Filtrage** : On identifie les tâches qui appartenaient EXCLUSIVEMENT à cette UA.
3.  **Suppression Conditionnelle** :
    - `RealisationProjetService` parcourt les réalisations.
    - Il demande à `RealisationTacheService` de supprimer la tâche **SI ET SEULEMENT SI** son état est "Non Commencé".
    - *Règle Métier* : Si un apprenant a déjà commencé à travailler sur une tâche d'une UA qu'on retire, on ne supprime PAS sa trace (Historique conservé ou blocage de la suppression).

---
## 3. Points d'Attention SRP
- **MobilisationUaService** : Ne touche pas aux tables de réalisations. Il signale juste un changement de configuration.
- **TacheService** : Sert de référentiel ("Quelles tâches appartiennent à l'UA X ?").
- **RealisationTacheService** : Exécute l'ordre de destruction, mais applique sa propre logique de sécurité (ne supprime pas si `state != TODO`).
