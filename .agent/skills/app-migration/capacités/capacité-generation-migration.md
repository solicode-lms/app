# Capacité : Génération de Migration

Cette capacité définit les standards de code pour les fichiers de migration dans le projet Solicode LMS.

## 1. Structure de Table Principale
Se référer à `capacité-regles-table.md` pour connaître les champs obligatoires (ex: `reference`). Dans la migration, implémentez ces champs correctement.

## 2. Typage des Champs Texte
Le choix du type pour un champ texte est **critique** :
- **`string`** : Pour les courtes chaînes de caractères (titre, nom, code, etc.) — max 255 caractères.
- **`text`** : Pour les textes simples et moyennement longs (commentaires courts, notes).
- **`longText`** : Pour tout champ susceptible de contenir du **texte riche** (HTML, Markdown, éditeur WYSIWYG), comme une description de projet, des consignes, des critères, une explication de question QCM, etc.

> **Règle** : En cas de doute sur la longueur ou si le champ sera affiché dans un éditeur riche (ex: CodeJar, TinyMCE), préférer `longText`.

## 2. Relations Many-To-One
Les clés étrangères doivent utiliser la méthode `foreignId` avec `constrained`.
Exemple de relation :
```php
$table->foreignId('projet_id')->constrained('projets');
// ou avec onDelete :
$table->foreignId('projet_id')->constrained('projets')->onDelete('cascade');
```

## 3. Relations Many-To-Many (Table Pivot)
Se référer à `capacité-regles-table.md` pour les interdictions d'ID et le nommage des clés. Dans la migration :
- Utilisez `foreignId()->constrained()->onDelete('cascade')` pour chaque clé.
- Ajoutez `$table->timestamps();`.

Exemple de table pivot générée (ex: pour la relation entre Apprenant et EquipeProjet) :
```php
Schema::create('apprenant_equipe_projet', function (Blueprint $table) {
    $table->timestamps();
    $table->foreignId('equipe_projet_id')->constrained('equipe_projets')->onDelete('cascade');
    $table->foreignId('apprenant_id')->constrained('apprenants')->onDelete('cascade');
});
```

## 4. Déroulement down()
La méthode `down()` doit supprimer toutes les tables créées, en commençant par les tables pivot pour éviter les erreurs de clés étrangères.
