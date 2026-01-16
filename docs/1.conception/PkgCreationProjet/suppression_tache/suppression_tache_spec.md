# Spécification Technique : Cas d'Utilisation "Suppression Tâche"

**Référence Diagramme** : [`suppression_tache_workflow.mmd`](./suppression_tache_workflow.mmd)
**Module Responsable** : `PkgCreationTache`

---

## 1. Contexte
Le retrait d'une tâche du projet.

## 2. Règles Métier
- **Intégrité** : On ne peut pas supprimer une tâche "parent" s'il reste des réalisations "enfants" orphelines.
- **Nettoyage Préalable** :
    1. Supprimer d'abord les `RealisationTache`.
    2. Supprimer la `Tache`.
- **SRP** : `TacheService` n'a pas le droit de faire `DELETE FROM realisation_taches`. Il doit appeler `RealisationTacheService::deleteByTache($tacheId)`.
