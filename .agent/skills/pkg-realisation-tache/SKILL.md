---
name: pkg-realisation-tache
description: Expert du module PkgRealisationTache — architecture de données et gestion des états de réalisation.
---

# Skill : Expert Réalisation des Tâches

## 🎯 Périmètre Global
**Mission** : Fournir à l'IA une connaissance exhaustive de l'architecture du module PkgRealisationTache : modèle de données pour la réalisation des tâches, gestion des états, historiques, et affectations.

### 🚫 Interdictions Globales
1. **Intégrité Gapp** : Ne jamais modifier les fichiers `Base/` générés par Gapp sans autorisation explicite.
2. **Cohérence des données** : Les modifications d'état sur une `RealisationTache` doivent respecter les règles de permissions (`is_editable_only_by_formateur`).

---

## ⚡ Actions (Orchestration)

### Action A : Comprendre la Structure de Données
> **Description** : Expliquer la hiérarchie des entités du module réalisation des tâches et leurs relations Eloquent.
- **Capacités Utilisées** :
  - `règles-gestion/règle-gestion-bdd-pkg-realisation-tache.md`
- **Entrées** : Question sur une entité ou une relation du module
- **Sorties** : Explication de la hiérarchie et des relations Eloquent

### Action B : Comprendre les Fonctionnalités et Cas d'Utilisation
> **Description** : Expliquer le fonctionnement métier et le cycle de vie d'une tâche.
- **Capacités Utilisées** :
  - `règles-gestion/règle-gestion-fonctionnalites-pkg-realisation-tache.md`
- **Entrées** : Question sur le workflow ou un cas d'utilisation
- **Sorties** : Description de la fonctionnalité et des critères métier


---

## 🛠️ Capacités (Savoir-Faire Technique)
*Documentation des fichiers situés dans le dossier `règles-gestion/`*

### 1. `règle-gestion-bdd-pkg-realisation-tache.md`
- **Rôle** : Décrire la hiérarchie complète des entités (`RealisationTache`, `EtatRealisationTache`, etc.) et leurs relations Eloquent.
- **Règles Clés** : Chaque `RealisationTache` centralise les relations avec la tâche, le projet, et gère le cycle de vie de la réalisation.

### 2. `règle-gestion-fonctionnalites-pkg-realisation-tache.md`
- **Rôle** : Décrire les cas d'utilisation du module en format texte.
- **Règles Clés** : Pas de diagramme complexe, description textuelle simple et précise pour guider l'implémentation fonctionnelle.

### 3. `règle-gestion-live-coding-pkg-realisation-tache.md`
- **Rôle** : Documenter la fonctionnalité automatisée de sélection d'un apprenant pour un live coding à 50% d'avancement.
- **Règles Clés** : Algorithme de sélection basé sur le moins grand nombre de live codings dans l'année (équité) et bascule d'état.



---

## 🔄 Scénarios d'Exécution

### Scénario 1 : "Quelles sont les relations de RealisationTache ?"
1. Lire `règle-gestion-bdd-pkg-realisation-tache.md`.
2. Identifier les relations : `tache`, `etatRealisationTache`, `realisationProjet`, `tacheAffectation`.

### Scénario 2 : "Comment marche le changement d'état ?"
1. Lire `règle-gestion-fonctionnalites-pkg-realisation-tache.md`.
2. Appliquer les règles de vérification de `is_editable_only_by_formateur` et la création de l'historique.

### Scénario 3 : "Comment est choisi l'apprenant pour le live coding ?"
1. Lire `règle-gestion-live-coding-pkg-realisation-tache.md`.
2. Consulter la méthode `lancerLiveCodingSiEligible` dans `TacheAffectationService`.
3. Vérifier les conditions : `is_live_coding_task`, >= 50% de réalisation, et tri par minimum de participations sur l'année.
