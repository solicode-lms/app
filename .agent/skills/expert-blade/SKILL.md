---
name: expert-blade
description: Expert de l'architecture et de la personnalisation des vues Blade sous le générateur Gapp.
---

# Skill : Expert Blade (Gapp)

## 🎯 Périmètre Global
**Mission** : Gérer la personnalisation de la couche présentation (Blade) de SoliLMS tout en respectant l'architecture de surcharge Gapp, afin de garantir la pérennité des modifications lors des régénérations de code.

### 🚫 Interdictions Globales (Règles d'Or)
1. **Fichiers Maintenus par Gapp** : Il est STRICTEMENT INTERDIT de modifier un fichier `_*.blade.php` natif (ex: `_table.blade.php`, `_fields.blade.php`) généré directement dans la racine de la vue métier.
2. **Protection des Fichiers Custom** : Avant de modifier un fichier complet dans le dossier `custom/`, le commentaire `{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}` doit ABSOLUMENT être supprimé pour éviter l'écrasement par Gapp.
3. **Régénération (`fields`) vs Résolution (`forms`)** : Ne jamais oublier que l'ajout d'une vue dans `custom/fields` nécessite l'exécution de `gapp make:crud [Modele]` pour être inclus dans le tableau, alors que les vues dans `custom/forms/` sont résolues dynamiquement par Laravel.

---

## ⚡ Actions (Orchestration)

### Action A : Expliquer l'Architecture Front-End Gapp
> **Description** : Transmettre l'organisation des vues, la relation entre partiels originaux et le dossier `custom/`.
- **Capacités Utilisées** :
  - `capacités/capacité-blade-architecture.md`
- **Entrées** : `Demande d'explication ou contexte`
- **Sorties** : `Explications et recommandations d'intervention`
- **📝 Instructions d'Orchestration** :
  1. Utiliser `capacité-blade-architecture.md` pour cibler le bon dossier d'intervention (`custom`, `custom/fields`, ou `custom/forms`).

### Action B : Personnaliser une Colonne (Liste / Table)
> **Description** : Surcharger le rendu HTML d'une colonne précise dans un tableau récapitulatif.
- **Capacités Utilisées** :
  - `capacités/capacité-blade-table-fields.md`
- **Entrées** : `Nom du Modèle`, `Nom du Champ`
- **Sorties** : `Fichier dans custom/fields/`
- **📝 Instructions d'Orchestration** :
  1. Utiliser `capacité-blade-table-fields.md`.
  2. Créer/Modifier le fichier cible.
  3. **Obligatoire** : Proposer et appliquer la commande `php artisan gapp make:crud [Modele]` pour intégrer le rendu.

### Action C : Personnaliser un Champ de Saisie (Formulaire)
> **Description** : Surcharger le rendu HTML d'un input dans un formulaire de création/édition.
- **Capacités Utilisées** :
  - `capacités/capacité-blade-form-fields.md`
- **Entrées** : `Nom du Modèle`, `Nom du Champ`
- **Sorties** : `Fichier dans custom/forms/`
- **📝 Instructions d'Orchestration** :
  1. Utiliser `capacité-blade-form-fields.md`.
  2. Modifier le rendu de l'élément (classes, balise, affichage conditionnel).

### Action D : Surcharger Intégralement un Layout
> **Description** : Refaire intégralement la structure d'une vue CRUD (index, table, fields, edit, show).
- **Capacités Utilisées** :
  - `capacités/capacité-blade-architecture.md`
- **Entrées** : `Nom du Modèle`, `Nom du layout (ex: _table.blade.php)`
- **Sorties** : `Fichier layout modifié dans custom/`
- **❌ Interdictions Spécifiques** : 
  - Toujours supprimer le commentaire "maintenu par ESSARRAJ Fouad".

---

## 🛠️ Capacités (Savoir-Faire Technique)
*Documentation des fichiers situés dans le dossier `.agent/skills/expert-blade/capacités/`*

### 1. `capacité-blade-architecture.md`
- **Rôle** : Connaissance de la hiérarchie globale (Héritage `_*.blade.php` → `custom/_*.blade.php`).

### 2. `capacité-blade-table-fields.md`
- **Rôle** : Logique de la surcharge des colonnes de l'index via `include` généré.

### 3. `capacité-blade-form-fields.md`
- **Rôle** : Logique de la surcharge des champs de formulaire via le composant XML `<x-form-field>`.

---

## 🔄 Scénarios d'Exécution (Algorithmes)
- Si personnalisation basique d'affichage -> Intervenir dans `fields/` ou `forms/`.
- Si changement lourd d'UI -> Intervenir sur le fichier `custom/_table.blade.php` principal ou `custom/_fields.blade.php` en enlevant le commentaire de Gapp.
