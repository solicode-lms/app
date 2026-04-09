# Capacité : Architecture Blade Gapp (Couche Présentation)

## 1. Philosophie & Organisation
La couche présentation de SoliLMS est générée par l'outil Gapp pour accélérer le développement CRUD. 
Les fichiers générés (par ex. pour `PkgRealisationTache/realisationTache`) ne **doivent jamais être altérés directement** pour préserver la possibilité d'utiliser la commande `gapp make:crud`.

## 2. Le Dossier `custom/`
Chaque module métier contient un sous-dossier `custom/`. Ce dossier contient toutes les surcharges de présentation.
- **Extensions par défaut** : Gapp génère `custom/_table.blade.php`, `custom/_fields.blade.php`, etc., qui font simplement un `@extends` vers les partiels natifs correspondants de Gapp.
- **Le verrou Gapp** : Tous les fichiers portent l'entête : `{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}`.
- **Mécanisme de Surcharge Totale** : 
  - Si l'IA ou le développeur doit modifier totalement un layout, il doit agir sur le fichier dans `custom/` et **supprimer impérativement le commentaire de maintien**. 
  - Sans le commentaire, Gapp comprend que le fichier est détaché (customisé) et ne l'écrasera plus.

## 3. Les Sous-Répertoires de Personnalisation
Au lieu de modifier un `_fields.blade.php` ou `_table.blade.php` en entier, on utilise une personnalisation granulaire via trois sous-dossiers :
1. **`custom/fields/`** : Personnalisation de l'affichage en lecture/liste (tableaux).
2. **`custom/forms/`** : Personnalisation des inputs (formulaires de saisie).
3. **`custom/actions/`** : Boutons d'actions spécifiques (boutons CRUD ou métier annexes).
