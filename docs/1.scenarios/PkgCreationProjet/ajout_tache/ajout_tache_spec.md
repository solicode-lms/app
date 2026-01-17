# Spécification Technique : Cas d'Utilisation "Ajout Tâche"

**Référence Diagramme** : [`ajout_tache_workflow.mmd`](./ajout_tache_workflow.mmd)
**Module Responsable** : `PkgCreationTache`

---

## 1. Contexte
Ajout d'une tâche à un projet existant.

## 2. Règles Métier
- **Propagation Immédiate** : Si le projet cible a déjà des apprenants affectés (il existe des `RealisationProjet`), la nouvelle tâche doit apparaître immédiatement pour eux.
- **SRP** :
    - `TacheService` : Crée la définition de la tâche.
    - `TacheService` : Délegue à `RealisationProjetService` la récupération des réalisations actives.
    - `TacheService` : Commande à `RealisationTacheService` de créer les instances.
