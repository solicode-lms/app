# Capacité : Modèle de Données — PkgQcm

## 1. Hiérarchie des Entités

Le module QCM gère la création de questionnaires à choix multiples et leur liaison à des compétences/projets, ainsi que leur réalisation par les apprenants.

```
Qcm
 ├── AffectationQcmProjet (Liaison QCM -> Projet)
 ├── Question (1..N par Qcm)
 │    └── PropositionReponse (1..N par Question)
 │
 └── RealisationQcm (1..N par Qcm) -> lié à un Apprenant et optionnellement à AffectationQcmProjet
      └── ReponseQcm (1..N par RealisationQcm)
```

## 2. Relations Eloquent Clés

### Qcm
- `belongsTo` → `Formateur`
- `hasMany` → `Question`, `AffectationQcmProjet`, `RealisationQcm`
- **Champs principaux** : `titre`, `duree_minutes`, `is_duree_limitee`, `is_publie`

### Question
- `belongsTo` → `Qcm`, `UniteApprentissage`
- `hasMany` → `PropositionReponse`, `ReponseQcm`
- **Champs principaux** : `enonce`, `type`, `bareme`, `ordre`, `explication`, `is_actif`

### PropositionReponse
- `belongsTo` → `Question`
- `hasMany` → `ReponseQcm`
- **Champs principaux** : `texte`, `est_correcte` (généralement)

### AffectationQcmProjet
- `belongsTo` → `Qcm`, `Projet`
- Permet de lier un QCM à un projet spécifique pour évaluation.

### RealisationQcm
- `belongsTo` → `Qcm`, `Apprenant`, `AffectationQcmProjet`, `EtatRealisationQcm`
- `hasMany` → `ReponseQcm`
- **Champs principaux** : `date_debut`, `date_fin`, `date_soumission`, `note_obtenu`

### ReponseQcm
- `belongsTo` → `RealisationQcm`, `Question`, `PropositionReponse`
- Permet de stocker la réponse de l'apprenant à une question lors d'une réalisation.

### EtatRealisationQcm
- Gère les états possibles d'une réalisation (ex: En cours, Soumis, Validé, Refusé).

---

## 3. Logique Conceptuelle

- Un **Formateur** crée un **Qcm** avec plusieurs **Question**s.
- Chaque **Question** possède un `bareme` et plusieurs **PropositionReponse**s, dont certaines sont correctes.
- Les questions peuvent être liées à une **UniteApprentissage** pour le suivi pédagogique.
- L'apprenant déclenche une **RealisationQcm** et soumet des **ReponseQcm**s pour chaque question.
