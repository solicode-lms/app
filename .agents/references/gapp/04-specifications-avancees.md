# Référence Gapp : Spécifications Avancées & Évolutives

> **ESPACE RÉSERVÉ AUX AJOUTS CONTINUS**  
> Ce document accueille les nouvelles spécifications, métadonnées ou conventions de Gapp au fur et à mesure de l'évolution du projet Solicode LMS.

## 1. Champs Calculés Dynamiques (`data-calcule`)
- Attribut HTML `data-calcule="champ_cible"` sur les inputs d'un formulaire.
- Déclenche un appel AJAX asynchrone intercepté par `FormUI.js` vers la méthode `dataCalcul($data)` du service.
- Le service enrichit l'entité en mémoire et renvoie les valeurs recalculées sans persistance en base.

## 2. Généralisation des Scope Variables dans les Badges de Header
- Standardisation du composant `<x-crud-context-badges :scopeVariables="$scopeVariables" />` dans les headers de vues `_index.blade.php`.

## 3. Nouvelles Spécifications (À enrichir au fur et à mesure)
*(Ajoutez ici vos nouvelles conventions Gapp au fil des versions).*
