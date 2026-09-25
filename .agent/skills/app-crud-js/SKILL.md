---
name: app-crud-js
description: Expert du framework JavaScript "crud" généré par Gapp (interception AJAX, modales).
---

# Skill : Expert Framework CRUD JS (Gapp)

## 🎯 Périmètre Global
**Mission** : Gérer, documenter et orienter les développements front-end liés au micro-framework JavaScript généré par Gapp (situé dans `resources/js/crud/`). Ce framework dynamise l'interface utilisateur en transformant les liens et formulaires en requêtes AJAX et en affichant les résultats dans des fenêtres modales.

### 🚫 Interdictions Globales (Règles d'Or)
1. **Intégrité du Framework** : Il est interdit de modifier les fichiers cœurs du framework dans `resources/js/crud/` sans une excellente raison architecturale. Les personnalisations doivent se faire dans les vues Blade via `@push('scripts')`.
2. **Conflit d'Événements** : Ne jamais attacher d'événements jQuery classiques sur des classes réservées au framework (comme `showIndex`, `editEntity`) sous peine de créer des conflits de gestion d'événements.

---

## ⚡ Actions (Orchestration)

### Action A : Expliquer le fonctionnement des requêtes AJAX
> **Description** : Expliquer comment le framework intercepte les clics et soumissions pour éviter le rechargement de la page.
- **Capacités Utilisées** : `capacités/capacite-interception-ajax.md`
- **Entrées** : Une question sur le non-rechargement des pages ou le fonctionnement d'un lien.
- **Sorties** : Explication du rôle du JS et des méthodes Fetch/Axios.

### Action B : Gérer l'affichage dans les Modales (showIndex, editEntity)
> **Description** : Guider le développeur sur l'utilisation (ou la désactivation) des classes déclenchant l'ouverture de fenêtres modales Bootstrap.
- **Capacités Utilisées** : `capacités/capacite-modal-display.md`
- **Entrées** : Besoin d'ouvrir une page en modale, ou au contraire, besoin de désactiver l'ouverture en modale pour un lien (ex: `target="_blank"`).
- **Sorties** : Instructions sur l'ajout ou le retrait des classes spécifiques (`showIndex`, `showEntity`, etc.).

---

## 🛠️ Capacités (Savoir-Faire Technique)
*Documentation des fichiers situés dans le dossier `.agent/skills/app-crud-js/capacités/`*

### 1. `capacite-interception-ajax.md`
- **Rôle** : Base de connaissances sur la manière dont les événements par défaut (`e.preventDefault()`) sont annulés et remplacés par des appels réseaux asynchrones.

### 2. `capacite-modal-display.md`
- **Rôle** : Documente la liste des classes CSS réservées (`showIndex`, `editEntity`, etc.) qui indiquent au framework d'injecter la réponse HTTP directement dans le corps d'une modale.
