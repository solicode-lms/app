# Capacité : Modèle de Données — PkgApprentissage

## 1. Hiérarchie des Entités

Le module d'apprentissage est organisé en **7 niveaux hiérarchiques** imbriqués.
Chaque niveau agrège les données du niveau inférieur via des colonnes `_cache`.

```
Module
 └── Compétence (1..N par Module)
      └── MicroCompétence (1..N par Compétence)
           └── UniteApprentissage / UA (1..N par MicroCompétence)
                ├── RealisationChapitre (1..N par UA)
                ├── RealisationUaPrototype (1..N par UA) → lié à une RealisationTache
                └── RealisationUaProjet (1..N par UA) → lié à une RealisationTache
```

Chaque entité pédagogique (Module, Compétence...) a une contrepartie **Realisation** portant les données de suivi de l'apprenant :

| Entité pédagogique | Réalisation Apprenant          |
|--------------------|-------------------------------|
| `Module`           | `RealisationModule`           |
| `Competence`       | `RealisationCompetence`       |
| `MicroCompetence`  | `RealisationMicroCompetence`  |
| `UniteApprentissage` | `RealisationUa`             |
| `Chapitre`         | `RealisationChapitre`         |
| `Tache` (prototype/projet) | `RealisationTache`  |

---

## 2. Relations Eloquent Clés

### RealisationModule
- `belongsTo` → `Module`, `Apprenant`
- `hasMany` → `RealisationCompetence`

### RealisationCompetence
- `belongsTo` → `Competence`, `Apprenant`, `RealisationModule`
- `hasMany` → `RealisationMicroCompetence`

### RealisationMicroCompetence
- `belongsTo` → `MicroCompetence`, `Apprenant`, `RealisationCompetence`
- `hasMany` → `RealisationUa`

### RealisationUa
- `belongsTo` → `UniteApprentissage`, `RealisationMicroCompetence`
- `hasMany` → `RealisationChapitre`, `RealisationUaPrototype`, `RealisationUaProjet`

### RealisationUaProjet / RealisationUaPrototype
- `belongsTo` → `RealisationTache`, `RealisationUa`
- Portent : `note`, `bareme`, `date_debut`, `date_fin`

### RealisationTache
- `belongsTo` → `Tache`, `EtatRealisationTache`, `RealisationProjet`
- `hasMany` → `RealisationUaProjet`, `RealisationUaPrototype`, `RealisationChapitre`

### RealisationProjet
- `belongsTo` → `AffectationProjet`, `Apprenant`
- `hasMany` → `RealisationTache`

---

## 3. Colonnes Cache (Agrégats Persistés)

Chaque entité `Realisation*` porte les colonnes suivantes calculées automatiquement :

| Colonne                      | Description                                    |
|------------------------------|------------------------------------------------|
| `note_cache`                 | Note totale agrégée                            |
| `bareme_cache`               | Barème total des tâches évaluées               |
| `bareme_non_evalue_cache`    | Barème des tâches non encore notées            |
| `progression_cache`          | Progression réelle (% tâches avancées)         |
| `progression_ideal_cache`    | Progression idéale (% tâches activées)         |
| `taux_rythme_cache`          | Ratio progression réelle / idéale              |
| `pourcentage_non_valide_cache` | % de tâches à corriger                       |
| `dernier_update`             | Timestamp du dernier recalcul                 |

---

## 4. Création en Cascade (Auto-génération)

Quand on crée une `RealisationModule` pour un apprenant :
1. `RealisationModuleService::afterCreateRules()` crée automatiquement une `RealisationCompetence` pour chaque `Competence` du module.
2. `RealisationCompetenceService::afterCreateRules()` crée une `RealisationMicroCompetence` pour chaque `MicroCompetence`.
3. `RealisationMicroCompetenceService::afterCreateRules()` crée une `RealisationUa` pour chaque `UniteApprentissage`.
4. `RealisationUaService::afterCreateRules()` crée une `RealisationChapitre` pour chaque `Chapitre` de l'UA.

> **Note** : Les `RealisationUaProjet` et `RealisationUaPrototype` sont créés lors de l'affectation d'une tâche à l'apprenant (via `RealisationTache`).
