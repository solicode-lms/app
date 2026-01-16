# Spécification Technique : Cas d'Utilisation "Modification Tâche"

**Référence Diagramme** : [`modification_tache_workflow.mmd`](./modification_tache_workflow.mmd)
**Module Responsable** : `PkgCreationTache`

---

## 1. Contexte
Le formateur modifie les propriétés d'une tâche (Titre, Description, Livrables, Dates).

## 2. Règles Métier
- **Mises à jour Critiques** : Si on change une date limite ou un livrable attendu, cela doit se refléter sur les réalisations des apprenants.
- **SRP** :
    - `TacheService` met à jour sa table.
    - Il notifie `RealisationTacheService` pour qu'il mette à jour les métadonnées correspondantes si nécessaire.
