---
name: app2-front-end
description: Expert de l'architecture V2 avec Tailwind CSS et Alpine.js (Composants).
---

# Skill : Expert UI V2 (Tailwind & Alpine)

## 🎯 Périmètre Global
**Mission** : Gérer la conception et le développement de la couche front-end de la version V2 de l'application, en utilisant Tailwind CSS pour le style et Alpine.js avec une architecture orientée composants pour la logique front.

### 🚫 Interdictions Globales (Règles d'Or)
1. **Pas de JQuery/Bootstrap** : Interdiction formelle d'utiliser Bootstrap ou JQuery dans le contexte V2.
2. **Architecture SoliLMS** : Le backend reste inchangé (SoliLMS), seules les vues Blade sont remplacées par des composants Alpine/Tailwind.
3. **Séparation Layout / Contenu (CRITIQUE)** :
   - Les vues métiers étendent obligatoirement `@extends('layouts.admin-v2')`.
   - Interdiction formelle de dupliquer la sidebar, le header et le shell HTML dans les vues de contenu.
   - Les vues de production contiennent exclusivement les indicateurs/sections fonctionnels du sprint en cours (aucune maquette statique non implémentée).

---

## ⚡ Actions (Orchestration)

### Action A : Créer/Gérer un Composant Alpine
> **Description** : Créer ou modifier un composant UI utilisant Alpine.js et Tailwind CSS.
- **Capacités Utilisées** :
  - `capacités/capacité-alpine-composants.md`
- **Entrées** : `Nom du composant`, `Fonctionnalités attendues`
- **Sorties** : Fichiers Blade du composant (`x-nom-composant`), logique Alpine associée.
- **📝 Instructions d'Orchestration** :
  1. Utiliser les conventions définies dans `capacité-alpine-composants.md`.

### Action B : Intégrer une Vue dans le Layout V2
> **Description** : Développer ou refactoriser une vue Blade V2 dans le respect du gabarit partagé.
- **Capacités Utilisées** :
  - `capacités/capacité-layout-v2.md`
- **Entrées** : `Nom de la vue`, `Données fonctionnelles`
- **Sorties** : Vue Blade épurée étendant `layouts.admin-v2`.

---

## 🛠️ Capacités (Savoir-Faire Technique)
*Documentation des fichiers situés dans le dossier `capacités/`*

### 1. `capacité-alpine-composants.md`
- **Rôle** : Définir l'architecture, la structure et les bonnes pratiques pour créer des composants isolés avec Alpine.js.

### 2. `capacité-layout-v2.md`
- **Rôle** : Règles d'héritage du layout `layouts.admin-v2`, suppression du boilerplate HTML et exclusion des maquettes statiques non fonctionnelles.

### 3. `../capacites-globales/capacité-crud-jobs.md` (Capacité Globale)
- **Rôle** : Standardisation de l'intégration UI des traitements longs. À lire impérativement lors du développement de formulaires ou d'actions asynchrones lourdes nécessitant un Polling (via Alpine/JS) pour afficher la progression (`traitement_token`).
