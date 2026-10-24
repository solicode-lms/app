# Contexte IA du Module : PkgApprentissage

## 1. Responsabilité Métier
Le package **PkgApprentissage** constitue le moteur de calcul et d'alignement de la progression pédagogique de Solicode LMS à travers toute la hiérarchie didactique :
1. **Paliers de Progression Pédagogique :** Suivi de l'acquisition aux 5 niveaux de granularité :
   - Modules (`RealisationModule`)
   - Compétences (`RealisationCompetence`)
   - Chapitres (`RealisationChapitre`)
   - Unités d'Apprentissage (`RealisationUa`, `RealisationUaProjet`, `RealisationUaPrototype`)
   - Micro-compétences (`RealisationMicroCompetence`)
2. **Calculs en Cascade :** Propagation ascendante des validations depuis les tâches et prototypes vers les compétences et modules.
3. **États d'Acquisition :** Nomenclature harmonisée (`EtatRealisation...`) pour chaque palier.

---

## 2. Modèles & Relations Principales

### `RealisationUa` & `RealisationUaPrototype`
- Modélise la validation d'une Unité d'Apprentissage par l'apprenant, liée aux épreuves pratiques et aux livrables.

### `RealisationMicroCompetence`
- Granule la plus fine de l'APC de Solicode, calculée à partir des évaluations continues et des validations de tâches.

---

## 3. Services & Logique Métier
- **Mécanismes Asynchrones :** Recalculs lourds des progressions de promotion délégués via `JobManager` et observateurs Eloquent pour préserver la réactivité de l'interface.
