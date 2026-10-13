---
name: sys-analyse
description: Expert de l'analyse des besoins, de l'audit de compatibilité SoliLMS (skills pkg-*), de la détermination des fonctionnalités et de la structuration des plans de réalisation.
---

# Skill : Analyste des Besoins (Sys)

## 🎯 Périmètre Global
**Mission** : Assurer l'analyse méthodique des besoins fonctionnels et didactiques, confronter chaque exigence à la structure actuelle de l'application en s'appuyant sur les skills métier (`pkg-*`), déterminer les fonctionnalités à créer ou adapter, et produire un plan de réalisation rigoureusement ordonné en sprints.

### 🚫 Interdictions Globales (Règles d'Or)
1. **Pas de Supposition sur l'Existant (RÈGLE STRICTE)** : Il est formellement interdit de présumer de l'architecture de SoliLMS. L'analyste DOIT impérativement consulter les skills métiers (`pkg-apprentissage`, `pkg-realisation-tache`, `pkg-qcm`, etc.) ou le schéma de base de données (`app-db-savoir`) pour vérifier les états, champs et relations existants.
2. **Toute la BDD au Sprint 1** : Lors de l'élaboration d'un plan de réalisation, l'ensemble des modifications de la base de données (migrations, nouveaux champs, nouveaux statuts en table) DOIT obligatoirement être concentré dans le **Sprint 1**.
3. **Traçabilité des Skills** : Chaque étape du plan doit obligatoirement spécifier les skills responsables de son implémentation.
4. **Pas de Code Source** : Ce skill produit des documents d'analyse et de planification Markdown (`.md`) dans `cahiers-charges/` ; il ne modifie aucun fichier de code source PHP/Blade.

---

## ⚡ Actions (Orchestration)

### Action A : Diagnostic d'Écart & Audit de Compatibilité SoliLMS
> **Description** : Analyser une expression de besoin (ou annexe d'indicateurs) et la confronter aux capacités des packages SoliLMS existants pour identifier les données ou statuts manquants.
- **Capacités Utilisées** :
  - `capacités/capacité-diagnostic-existant.md`
- **Entrées** : `Documents de besoin (Cahier des charges, Annexes)`, `Liste des packages potentiellement impactés`
- **Sorties** : Fiche d'analyse d'écart / Diagnostic de compatibilité
- **❌ Interdictions Spécifiques** :
  - Conclure sans vérifier si un état ou un timestamp nécessaire existe déjà dans le module cible.
- **✅ Points de Contrôle** :
  - Les skills `pkg-*` pertinents ont été consultés.
  - Les modifications à apporter à chaque package sont formulées de manière explicite.
- **📝 Instructions d'Orchestration** :
  1. Lire le document de besoin et identifier les entités concernées.
  2. Invoquer ou consulter le skill métier responsable (ex: `pkg-realisation-tache` pour les états de tâches).
  3. Identifier les écarts (nouveaux champs, nouveaux états, nouveaux hooks de calcul).
  4. Rédiger la synthèse des adaptations requises par package.

### Action B : Détermination des Fonctionnalités (Backlog)
> **Description** : Découper le besoin en fonctionnalités unitaires, claires et actionnables.
- **Capacités Utilisées** :
  - `capacités/capacité-diagnostic-existant.md`
- **Entrées** : `Fiche d'analyse d'écart`, `Besoins métiers`
- **Sorties** : Backlog fonctionnel structuré (User Stories / Fonctionnalités clés)
- **✅ Points de Contrôle** :
  - Chaque fonctionnalité précise son acteur (Apprenant, Formateur, Responsable), ses règles de calcul et les packages impactés.

### Action C : Élaboration du Plan de Réalisation
> **Description** : Structurer la feuille de route du projet en sprints séquentiels avec matrice de dépendances.
- **Capacités Utilisées** :
  - `capacités/capacité-structuration-plan.md`
- **Entrées** : `Backlog fonctionnel`, `Matrice d'impacts par package`
- **Sorties** : Fichier `Plan-realisation-[Nom].md` dans `cahiers-charges/[NomModule]/`
- **❌ Interdictions Spécifiques** :
  - Disperser les migrations BDD sur plusieurs sprints (seul le Sprint 1 contient les migrations).
- **📝 Instructions d'Orchestration** :
  1. Charger le template `resources/template-plan-realisation.md`.
  2. Regrouper toutes les évolutions de base de données dans le Sprint 1 (suivi de `gapp meta:sync`).
  3. Ordonner les sprints suivants : Métier/Services $\rightarrow$ Contrôleurs/Routage $\rightarrow$ UI/Vues.
  4. Assigner les skills compétents pour chaque tâche.

---

## 🛠️ Capacités (Savoir-Faire Technique)
*Documentation des fichiers situés dans le dossier `capacités/`*

### 1. `capacité-diagnostic-existant.md`
- **Rôle** : Méthodologie d'interrogation croisée des packages SoliLMS via les skills `pkg-*` et détection des écarts (états, timestamps, calculs, niveaux d'agrégation).
- **Règles Clés** : Consultation systématique des skills métier, vérification des machines à états.

### 2. `capacité-structuration-plan.md`
- **Rôle** : Directives de découpage en sprints ordonnés et traçabilité des dépendances techniques.
- **Règles Clés** : Règle d'or BDD au Sprint 1, enchaînement BDD $\rightarrow$ Service $\rightarrow$ Contrôleur $\rightarrow$ UI.

---

## 🔄 Scénarios d'Exécution (Algorithmes)

### Scénario 1 : Analyse d'un nouveau besoin ou module
*Déclencheur : "Analyse ce besoin et propose un plan de réalisation"*
1. **Lecture & Cadrage** : L'analyste prend connaissance du document d'expression de besoin.
2. **Audit Existant** : Il consulte les skills `pkg-*` concernés pour cartographier l'existant.
3. **Gap Analysis** : Il détermine les modifications nécessaires dans chaque package existant.
4. **Backlog & Plan** : Il formalise la liste des fonctionnalités et génère le fichier `Plan-realisation-[Nom].md` découpé en sprints avec attribution des skills.

### Scénario 2 : Spécification d'un indicateur / métrique métier
*Déclencheur : "Comment calculer l'indicateur X et quelles modifications apporter à SoliLMS ?"*
1. L'analyste identifie les données sources de l'indicateur.
2. Il vérifie dans le skill `pkg-*` adéquat si les données sont déjà tracées.
3. Il spécifie les nouveaux états ou champs à créer dans le package d'origine pour rendre le calcul possible.
