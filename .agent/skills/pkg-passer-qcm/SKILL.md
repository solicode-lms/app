---
name: pkg-passer-qcm
description: Expert du module PkgPasserQcm — règles de gestion de l'interface de passage des QCM par l'apprenant.
---

# Skill : Expert Passage QCM

## 🎯 Périmètre Global
**Mission** : Gérer la conception et l'implémentation de l'interface permettant aux apprenants de passer leurs QCMs, en assurant une UX/UI riche (via Tailwind CSS) et indépendante du back-office (AdminLTE).

### 🚫 Interdictions Globales (Règles d'Or)
1. **Pas de nouvelles tables** : Ce module ne possède aucune table en base de données. Il exploite les tables du module `PkgQcm`.
2. **Indépendance UI** : L'interface doit être développée avec Tailwind CSS (initialement via CDN) et un layout spécifique, ne pas utiliser Bootstrap.

---

## ⚡ Actions (Orchestration)

### Action A : Gérer l'interface de passage
> **Description** : Implémenter ou modifier l'interface pour le passage d'un QCM par un apprenant.
- **Règles de Gestion Utilisées** :
  - `règles-gestion/règle-gestion-passage-qcm.md`
- **Entrées** : `Demande d'UI/UX`, `Layout`
- **Sorties** : Vues Blade avec Tailwind CSS (`passer-qcm.blade.php`), Contrôleurs dédiés.
- **❌ Interdictions Spécifiques** :
  - Ne pas utiliser Vite pour l'intégration Tailwind dans un premier temps (utilisation CDN).
- **📝 Instructions d'Orchestration** :
  1. Lire `règle-gestion-passage-qcm.md` pour comprendre les contraintes UI/UX.

---

## 🛠️ Règles de Gestion (Savoir-Faire Technique)
*Documentation des fichiers situés dans le dossier `règles-gestion/`*

### 1. `règle-gestion-passage-qcm.md`
- **Rôle** : Documenter la charte graphique, l'utilisation de Tailwind et les contraintes UX/UI pour le layout et les composants du passage de QCM.
- **Règles Clés** : Layout isolé, Tailwind via CDN, couleurs compatibles AdminLTE.

---

## 🔄 Scénarios d'Exécution (Algorithmes)
### Scénario 1 : Création de la page de démarrage QCM
1. Vérifier la présence du layout `passer-qcm.blade.php` avec Tailwind.
2. Créer la vue en utilisant les règles de gestion UI définies.
