# Spécification Technique : Cas d'Utilisation "Création Projet"

**Référence Diagramme** : [`creation_projet_workflow.mmd`](./creation_projet_workflow.mmd)
**Module Responsable** : `PkgCreationProjet`

---

## 1. Contexte
Ce processus décrit l'initialisation d'un projet pédagogique par le Formateur.
Il y a une distinction fondamentale selon que le projet est "Planifié" (Session) ou "Libre".

## 2. Règles Métier

### A. Création du Conteneur "Projet"
- **Projet Planifié** : Lien obligatoire avec une Session.
- **Projet Libre** : Pas de lien temporel.

### B. Séquencement Mixte (Ordonnancement Strict)
- Pour garantir un ordre pédagogique cohérent (ex: "Analyse" doit apparaître **avant** "Réalisation Technique"), la création ne se fait pas en blocs séparés.
- **Le Driver** : `ProjetService::getTasksConfig()` définit l'ordre global. Cette configuration contient à la fois :
    1. Des définitions de tâches standards (Analyse, Synthèse).
    2. Des **marqueurs** (placeholders) indiquant où insérer les UA mobilisées.
- **Action SRP** : `ProjetService` boucle sur cette config.
    - Si c'est une Tâche : Il appelle `TacheService`.
    - Si c'est le marqueur UA : Il appelle `MobilisationUaService`, qui à son tour appelle `TacheService` pour les chapitres.

### C. Gestion des Cas
- **Projet Planifié** : La boucle s'exécute complètement. Les Tâches Standards sont créées ET les Mobilisations sont insérées au bon moment (entre deux tâches standards).
- **Projet Libre** : La boucle est ignorée ou stoppée. Le projet reste vide.

## 3. Flux d'Interactions
Le `ProjetService` suit un "Script de création" linéaire. L'appel à `MobilisationUaService` est imbriqué dans ce script pour garantir que les tâches de chapitres se retrouvent avec un `ordre` (BDD) supérieur aux tâches d'analyse mais inférieur aux tâches de synthèse.
