---
name: app-blade
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

### Action E : Ajout et Configuration de Filtres
> **Description** : Guider et accompagner le développeur dans la mise en place de filtres sur les listes (index).
- **Capacités Utilisées** :
  - `capacités/capacité-filtres-donnees.md`
- **Entrées** : `Modèle cible`, `Filtre désiré`
- **Sorties** : `Bloc JSON Gapp` ou `Instructions de modification de Service`
- **📝 Instructions d'Orchestration** :
  1. Lire `capacité-filtres-donnees.md`.
  2. Déterminer si le besoin est basique (relation, métadonnée JSON) ou complexe (surcharge Service `initFieldsFilterable`).
  3. Fournir la solution correspondante sans jamais écraser soi-même les fichiers JSON de métadonnées de Gapp.

### Action F : Configurer l'édition en ligne (Inline Edit)
> **Description** : Configurer la visibilité et la validation d'un champ éditable en ligne.
- **Capacités Utilisées** :
  - `capacités/capacité-validation-inline.md`
- **Entrées** : `Nom de l'entité`, `Nom du champ`, `Règles de validation`
- **Sorties** : Fichiers modifiés `[Model]Request.php` et `[Model]Service.php`
- **📝 Instructions d'Orchestration** :
  1. Utiliser `capacité-validation-inline` pour configurer le FormRequest avec la validation dynamique et le mécanisme de repli (fallback) sur la requête globale.
  2. S'assurer que le service métier surcharge `beforeUpdateRules` pour jeter une `ValidationException` en cas de dépassement.

### Action G : Initialiser et Exploiter le ViewState (Vues)
> **Description** : S'assurer que la vue exploite correctement les paramètres d'état (ViewState).
- **Capacités Utilisées** :
  - `capacités/capacité-view-state.md`
- **Entrées** : `Nom de la vue`, `Variables d'état requises`
- **Sorties** : Modification du rendu conditionnel ou des appels AJAX dans la vue Blade.

### Action H : Personnaliser un Bouton d'Action (Tableau)
> **Description** : Surcharger le rendu HTML ou la condition d'affichage d'un bouton d'action dans un tableau `_table.blade.php`.
- **Capacités Utilisées** :
  - `capacités/capacité-blade-actions.md`
- **Entrées** : `Nom du Modèle`, `Nom de l'Action (actionName)`
- **Sorties** : `Fichier dans custom/actions/`
- **📝 Instructions d'Orchestration** :
  1. Ne pas modifier `_table.blade.php`.
  2. Créer le fichier `custom/actions/{actionName}.blade.php` pour la surcharge automatique (composant `<x-action-button>`).

---

## 🛠️ Capacités (Savoir-Faire Technique)
*Documentation des fichiers situés dans le dossier `.agent/skills/app-blade/capacités/`*

### 1. `capacité-blade-architecture.md`
- **Rôle** : Connaissance de la hiérarchie globale (Héritage `_*.blade.php` → `custom/_*.blade.php`).

### 2. `capacité-blade-table-fields.md`
- **Rôle** : Logique de la surcharge des colonnes de l'index via `include` généré.

### 3. `capacité-blade-form-fields.md`
- **Rôle** : Logique de la surcharge des champs de formulaire via le composant XML `<x-form-field>`.

### 4. `capacité-filtres-donnees.md`
- **Rôle** : Méthodes et règles pour ajouter des filtres de recherche (JSON Gapp ou Service).

### 5. `capacité-validation-inline.md`
- **Rôle** : Méthode technique pour ajouter la validation dynamique sur un champ éditable en ligne en gérant les particularités d'instanciation de Gapp.

### 6. `capacité-view-state.md`
- **Rôle** : Base de connaissances sur la manipulation du ViewState côté frontend (impact sur Select2 et datatables).

### 7. `capacité-blade-actions.md`
- **Rôle** : Documentation de la surcharge dynamique des boutons d'actions via le composant `<x-action-button>`.

### 8. `capacité-crud-js.md`
- **Rôle** : Explication des classes CSS (comme `showIndex`) interceptées par le JavaScript (crud-js) pour le chargement AJAX et les fenêtres modales.

---

## 🔄 Scénarios d'Exécution (Algorithmes)
- Si personnalisation basique d'affichage -> Intervenir dans `fields/` ou `forms/`.
- Si changement lourd d'UI -> Intervenir sur le fichier `custom/_table.blade.php` principal ou `custom/_fields.blade.php` en enlevant le commentaire de Gapp.

