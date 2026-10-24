# Référence : Framework Frontend TableUI & FilterUI

## 1. Vue d'Ensemble (`resources/js/crud/`)
L'interface utilisateur de Solicode LMS s'appuie sur une suite de modules ES6 pour gérer les tableaux et les formulaires asynchrones sans rechargement de page :
- `TableUI.js` : Gestion des tableaux, tri multi-colonnes, suppression groupée (`BulkAction`), édition inline (`CellOrchestrator`).
- `FilterUI.js` : Écoute du formulaire de filtre avec debounce (500ms), adaptation au contexte (`adapterPourContext`), et déclenchement de listes déroulantes dynamiques.
- `ModalUI.js` & `CrudModalManager.js` : Affichage et empilement des modales asynchrones via `iziModal`.
- `FormUI.js` : Soumission AJAX, gestion des calculs dynamiques (`data-calcule`) et affichage des messages d'erreur.

## 2. Filtrage Asynchrone & Dynamic Dropdowns
Pour lier une liste déroulante dépendante dans un formulaire ou filtre :
- Ajouter l'attribut `data-target-dynamic-dropdown` sur le select parent avec le sélecteur du select enfant cible.
- Fournir la route AJAX qui renvoie le JSON des options filtrées.
- `FilterUI.js` instancie automatiquement `DynamicDropdownTreatment` sur tous les éléments correspondants.

## 3. Vues Blade et Personnalisation
Pour personnaliser l'affichage sans modifier les templates cœurs générés par Gapp :
- Créer ou surcharger la vue dans le sous-dossier `custom/` (ex: `resources/views/[model]/custom/_table.blade.php`).
- Étendre la vue de base avec `@extends('Pkg...::[model]._table')` et surcharger les sections Blade prévues (`@section('[model]-table-tbody')`, etc.).
