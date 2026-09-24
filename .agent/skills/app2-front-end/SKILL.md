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

---

## 🛠️ Capacités (Savoir-Faire Technique)
*Documentation des fichiers situés dans le dossier `capacités/`*

### 1. `capacité-alpine-composants.md`
- **Rôle** : Définir l'architecture, la structure et les bonnes pratiques pour créer des composants isolés avec Alpine.js.
