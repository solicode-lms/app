---
name: expert-apprentissage
description: Expert du module PkgApprentissage — architecture de données, calculs de notes/progression en cascade, et affichage Blade.
---

# Skill : Expert Apprentissage

## 🎯 Périmètre Global
**Mission** : Fournir à l'IA une connaissance exhaustive de l'architecture du module PkgApprentissage : modèle de données hiérarchique, algorithmes de calcul de note et de progression en cascade, et patterns d'affichage dans les vues Blade.

### 🚫 Interdictions Globales
1. **Intégrité Gapp** : Ne jamais modifier les fichiers `Base/` générés par Gapp sans autorisation explicite.
2. **Isolation des Services** : Ne jamais recalculer une note ou une progression dans un contrôleur — toujours déléguer au Service du niveau concerné.
3. **Cohérence des niveaux** : Ne pas calculer la note d'un niveau supérieur dans le service d'un niveau inférieur (ex: ne pas calculer la note du Module dans `RealisationUaService`).
4. **Cache obligatoire** : Toujours persister les valeurs calculées via les colonnes `_cache` — ne jamais les calculer à la volée dans une vue Blade.

---

## ⚡ Actions (Orchestration)

### Action A : Comprendre la Structure de Données
> **Description** : Expliquer la hiérarchie des entités du module apprentissage et leurs relations Eloquent.
- **Capacités Utilisées** : `capacités/capacité-modele-donnees.md`
- **Entrées** : Question sur une entité ou une relation
- **Sorties** : Explication de la hiérarchie et des relations Eloquent

### Action B : Calculer ou Déboguer une Note
> **Description** : Expliquer ou corriger le calcul de note à un niveau donné (Tâche → Projet → UA → MicroCompétence → Compétence → Module).
- **Capacités Utilisées** : `capacités/capacité-calcul-note.md`
- **Entrées** : Niveau cible (module, compétence, ua...), entité concernée
- **Sorties** : Explication du calcul, correction du service ou du hook concerné

### Action C : Calculer ou Déboguer une Progression
> **Description** : Expliquer ou corriger le calcul de progression (taux d'avancement) à tout niveau.
- **Capacités Utilisées** : `capacités/capacité-calcul-progression.md`
- **Entrées** : Niveau cible, entité concernée
- **Sorties** : Explication du calcul de progression, correction du service ou du hook

### Action D : Affichage Blade (Barres de Progression & Notes)
> **Description** : Guider l'intégration ou la correction des vues affichant notes et progression.
- **Capacités Utilisées** : `capacités/capacité-affichage-blade.md`
- **Entrées** : Vue cible, données disponibles
- **Sorties** : Code Blade corrigé ou implémenté

---

## 🛠️ Capacités (Savoir-Faire Technique)

### 1. `capacité-modele-donnees.md`
- **Rôle** : Décrire la hiérarchie complète des entités et leurs relations Eloquent.
- **Règles Clés** : Chaque niveau agrège les données du niveau inférieur via les colonnes `_cache`.

### 2. `capacité-calcul-note.md`
- **Rôle** : Documenter les formules de calcul de note à chaque niveau et les hooks Service déclencheurs.
- **Règles Clés** : Le calcul est toujours déclenché en cascade par `afterUpdateRules`.

### 3. `capacité-calcul-progression.md`
- **Rôle** : Documenter les formules de progression (réelle, idéale, taux de rythme, non-valide).
- **Règles Clés** : La progression est stockée dans `progression_cache`, `progression_ideal_cache`, `taux_rythme_cache`, `pourcentage_non_valide_cache`.

### 4. `capacité-affichage-blade.md`
- **Rôle** : Documenter les patterns d'affichage des notes et barres de progression dans les vues Blade.
- **Règles Clés** : Toujours utiliser les colonnes `_cache` — ne jamais recalculer dans la vue.

---

## 🔄 Scénarios d'Exécution

### Scénario 1 : "Comment est calculée la note de module ?"
1. Lire `capacité-calcul-note.md` → section Module.
2. Identifier la chaîne : `RealisationTache` → `RealisationUaProjet/RealisationUaPrototype` → `RealisationUa` → `RealisationMicroCompétence` → `RealisationCompétence` → `RealisationModule`.
3. Pointer vers `RealisationModuleService::calculerProgression()`.

### Scénario 2 : "La progression ne se met pas à jour"
1. Lire `capacité-calcul-progression.md`.
2. Vérifier que `afterUpdateRules` est bien surchargé dans le Service enfant concerné.
3. Vérifier que le service appelle `calculerProgression()` et que la cascade remonte bien.

### Scénario 3 : "Afficher la barre de progression d'un apprenant"
1. Lire `capacité-affichage-blade.md`.
2. Vérifier que la variable `$realisationModule` (ou le niveau ciblé) est bien passée à la vue.
3. Utiliser les colonnes `_cache` comme source de données.
