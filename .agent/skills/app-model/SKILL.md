---
name: app-model
description: Expert de l'architecture des Modèles Eloquent et de la gestion des Enums en PHP.
---

# Skill : Expert Modèles & Enums (App)

## 🎯 Périmètre Global
**Mission** : Assurer la bonne implémentation des Modèles Eloquent et la gestion rigoureuse des Énumérations (Enums) PHP dans l'architecture modulaire de Solicode LMS.

## 📐 Règles d'Architecture des Enums

### 1. Emplacement des Enums
- **Enums Modulaires (Spécifiques)** : Si l'Enum n'a de sens que dans un seul module (ex: `PkgQcm`), il doit être placé dans le dossier du module sous : `modules/NomDuModule/Models/Enums/`. (Exemple : `modules/PkgQcm/Models/Enums/QuestionTypeEnum.php`).
- **Enums Globaux (Partagés)** : Si l'Enum est partagé entre plusieurs modules disparates, il peut être placé dans `app/Models/Enums/`.

### 2. Format des Enums (PHP 8.1+)
- Toujours utiliser les Enums natifs introduits en PHP 8.1 (Backed Enums).
- Un Enum doit généralement être typé (ex: `enum QuestionTypeEnum: string`).
- Il est recommandé de fournir une méthode `label()` au sein de l'Enum pour la traduction ou l'affichage frontend si nécessaire.

## 🛠️ Règles de Modification des Modèles Eloquent (Gapp)

L'application Solicode LMS utilise un générateur de code (Gapp) qui impose des règles strictes sur les classes du modèle :
1. **Classes de base (`Base...`)** : **Ne jamais modifier** les classes de modèle générées qui se trouvent souvent dans un dossier ou namespace `Base`.
2. **Modèles Enfants** : Les modifications personnalisées (ajout de Casts, de relations complexes ou de méthodes spécifiques) doivent se faire dans la classe enfant qui étend le modèle de base généré.
3. **En-tête de protection** : Si un modèle enfant possède la mention `// Ce fichier est maintenu par ESSARRAJ Fouad` (ou l'en-tête de protection Gapp), il est **interdit** de le modifier.
   - *Exception* : Si la modification est indispensable (ex: ajouter un cast d'Enum), il faut avertir l'utilisateur et lui demander s'il faut supprimer l'en-tête avant d'appliquer la modification.

## ⚡ Actions (Orchestration)

### Action A : Création d'un Enum et typage
> **Description** : Implémenter une règle d'intégrité métier en utilisant un Enum plutôt qu'une chaîne de caractères libre.

- **Déroulement** :
  1. Identifier le bon module et le bon emplacement (`Models/Enums`).
  2. Créer le fichier d'Enum (ex: `QuestionTypeEnum.php`) avec un Backed Enum (`string` ou `int`).
  3. Mettre à jour le modèle Eloquent cible pour utiliser cet Enum via la propriété `$casts` :
     ```php
     protected $casts = [
         'type' => QuestionTypeEnum::class,
     ];
     ```
  4. S'assurer de respecter les règles Gapp lors de l'ajout de la propriété `$casts`.
