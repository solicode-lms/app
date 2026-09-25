# Architecture du Service TacheService

Ce document cartographie l'organisation modulaire du `TacheService` pour faciliter la maintenance et la navigation.

## Vue d'ensemble
Le service est découpé en Traits thématiques pour respecter le principe de Responsabilité Unique (SRP) tout en conservant une interface unifiée.

## Cartographie des Fichiers

| Fichier / Trait                      | Responsabilité Principale             | Méthodes Clés                                                                                                      |
| :----------------------------------- | :------------------------------------ | :----------------------------------------------------------------------------------------------------------------- |
| **`TacheService.php`**               | **Point d'entrée** & Composition      | `index`, `show` (hérités), Configuration                                                                           |
| **`Traits/TacheCrudTrait.php`**      | **Cycle de vie CRUD** & Règles Métier | `beforeCreateRules`, `beforeUpdateRules`, `afterCreateRules`, `destroy`, `applyBusinessRules` (Calcul Note, Phase) |
| **`Traits/TacheRelationsTrait.php`** | **Synchronisation & Relations**       | `createRealisationTaches` (Apprenants), `syncRealisationPrototypeOrProjet` (Compétences UA)                        |
| **`Traits/TacheActionsTrait.php`**   | **Actions Métier Spécifiques**        | `createN1TutorielsTasksFromUa` (Génération Tutoriels N1)                                                           |
| **`Traits/TacheGetterTrait.php`**    | **Lecture & Filtres**                 | `getTacheByFormateurId`, `getTacheByApprenantId`, `allQuery`                                                       |

## Flux de Données Critiques

### Création d'une Tâche
1. `TacheService::create($data)`
2. -> `TacheCrudTrait::beforeCreateRules` : Calcul note, assignation Phase Projet.
3. -> `Parent::create` (Insertion DB).
4. -> `TacheCrudTrait::afterCreateRules` :
    - Appel `TacheRelationsTrait::createRealisationTaches` (Pour chaque apprenant).
    - Appel `TacheRelationsTrait::syncRealisationPrototypeOrProjet` (Si N2/N3).

### Création depuis une Mobilisation (UA)
1. `MobilisationUaService` appelle `TacheActionsTrait::createN1TutorielsTasksFromUa`.
2. -> Génération des tâches "Tutoriels".
