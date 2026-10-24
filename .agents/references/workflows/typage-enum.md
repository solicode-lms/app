# Workflow : Typage en Enum (/typage-enum)

## 📌 Présentation
- **Description** : Workflow d'exécution permettant de convertir un attribut existant (généralement un `string` libre) en une énumération stricte (Enum PHP 8.1+) afin de garantir l'intégrité des données métier.
- **Déclencheurs** : `/typage-enum`, `typer en enum`, `convertir en enum`.
- **Compétence Principale** : `feature-dev`.

---

## ⚡ Étapes d'Exécution (Orchestration)

Lorsqu'un développeur demande de typer un attribut en Enum, l'agent suit ces étapes séquentielles :

### Étape 1 : Création de l'Enum et Typage du Modèle
1. Analyser le module concerné (ex: `PkgQcm`) et l'entité cible (ex: `QuestionLib`).
2. Créer le fichier Enum natif PHP 8.1+ dans le dossier `Models/Enums/` du module (ex: `QuestionTypeEnum.php`) avec ses cas (`cases`) et sa méthode `label()`.
3. Ajouter la propriété `$casts` au modèle Eloquent enfant pour effectuer le casting automatique :
```php
protected $casts = [
    'type' => \Modules\PkgQcm\Models\Enums\QuestionTypeEnum::class,
];
```
> **Note (Protection Gapp)** : Supprimer l'en-tête de protection Gapp si le fichier modèle enfant en contient une.

### Étape 2 : Adaptation de l'Interface Utilisateur (Blade)
1. Identifier le formulaire de saisie généré par Gapp pour l'entité.
2. Créer un fichier de surcharge dans le dossier `resources/views/[model]/custom/` (ex: `custom/_fields.blade.php`).
3. Remplacer l'input standard par un menu déroulant `<select>` itérant sur les cas de l'Enum :
```blade
<select name="type" class="form-control select2">
    @foreach(\Modules\PkgQcm\Models\Enums\QuestionTypeEnum::cases() as $case)
        <option value="{{ $case->value }}" {{ old('type', $item->type?->value) === $case->value ? 'selected' : '' }}>
            {{ $case->label() }}
        </option>
    @endforeach
</select>
```

### Étape 3 : Mise à jour de la Conception (Diagramme de classe)
1. Localiser le diagramme Mermaid du module concerné (ex: `pkg_qcm_classes.mmd`).
2. Modifier le type de l'attribut dans la classe correspondante (remplacer `string` par `enum`).

---

## 📝 Rapport Final
À la fin du workflow, fournir un résumé :
- L'Enum créé dans `Models/Enums/`.
- Le modèle enfant mis à jour (`$casts`).
- La vue de formulaire surchargée dans `custom/`.
- La conformité des données existantes.
