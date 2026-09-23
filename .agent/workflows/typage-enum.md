# Workflow : Typage en Enum (/typage-enum)

## 📌 Présentation
- **Description** : Workflow d'exécution permettant de convertir un attribut existant (généralement un `string` libre) en une énumération stricte (Enum PHP) afin de garantir l'intégrité des données métier.
- **Déclencheurs** : `/typage-enum`, `typer en enum`, `convertir en enum`

---

## ⚡ Étapes d'Exécution (Orchestration)

Lorsqu'un utilisateur demande de typer un attribut en Enum, l'agent **DOIT** suivre ces étapes séquentielles en mobilisant les skills correspondants.

### Étape 1 : Création de l'Enum et Typage du Modèle
- **Skill Délégué** : `app-model`
- **Actions** :
  1. Analyser le module concerné (ex: `PkgQcm`) et l'entité cible (ex: `QuestionLib`).
  2. Créer le fichier Enum natif PHP 8.1+ dans le dossier `Models/Enums/` du module (ex: `QuestionTypeEnum.php`) avec ses cas (cases) et sa méthode `label()`.
  3. Ajouter la propriété `$casts` au modèle Eloquent enfant pour effectuer le casting automatique (`'attribut' => MonEnum::class`).
  > **Note (Protection Gapp)** : Demander l'autorisation à l'utilisateur pour retirer l'en-tête de protection Gapp si le fichier modèle en contient une.

### Étape 2 : Adaptation de l'Interface Utilisateur (Blade)
- **Skill Délégué** : `app-blade`
- **Actions** :
  1. Identifier le formulaire de saisie généré par Gapp pour l'entité.
  2. S'appuyer sur la capacité `capacité-blade-form-fields.md` pour créer un fichier de surcharge dans le dossier `custom/forms/` (ex: `custom/forms/type.blade.php`).
  3. Remplacer l'input standard par un menu déroulant `<select>` itérant sur les cas de l'Enum (`MonEnum::cases()`).

### Étape 3 : Mise à jour de la Conception (Diagramme de classe)
- **Skill Délégué** : `sys-conception`
- **Actions** :
  1. Localiser le diagramme Mermaid du module concerné (ex: `pkg_qcm_classes.mmd`).
  2. Modifier le type de l'attribut dans la classe correspondante (remplacer `string` par `enum`).

## 📝 Rapport Final
À la fin du workflow, fournir à l'utilisateur un résumé de :
- L'Enum créé.
- Le modèle modifié.
- La vue de formulaire surchargée.
- Le diagramme mis à jour.
