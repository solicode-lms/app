# Capacité : Génération de Migration

Cette capacité définit les standards de code pour les fichiers de migration dans le projet Solicode LMS.

## 1. Structure de Table Principale
Toute nouvelle table principale doit obligatoirement inclure :
- Un id classique (`$table->id();`).
- Un champ `reference` unique (`$table->string('reference')->unique();`).
- Les timestamps (`$table->timestamps();`).

## 2. Relations Many-To-One
Les clés étrangères doivent utiliser la méthode `foreignId` avec `constrained`.
Exemple de relation :
```php
$table->foreignId('projet_id')->constrained('projets');
// ou avec onDelete :
$table->foreignId('projet_id')->constrained('projets')->onDelete('cascade');
```

## 3. Relations Many-To-Many (Table Pivot)
Les tables pivot (ex: `label_tache`) ont des règles strictes :
- **AUCUN id** : Ne pas mettre `$table->id();`.
- **Nommage des clés étrangères** : Le nom de la clé étrangère doit correspondre exactement au nom du modèle de la table cible en snake_case (ex: `label_projet_id` pour la table `label_projets`, et non pas `label_id`).
- Les clés étrangères doivent pointer vers les tables cibles en utilisant `constrained()`.
- Les timestamps sont nécessaires.

Exemple :
```php
Schema::create('label_tache', function (Blueprint $table) {
    $table->foreignId('tache_id')->constrained('taches')->onDelete('cascade');
    $table->foreignId('label_projet_id')->constrained('label_projets')->onDelete('cascade');
    $table->timestamps();
});
```

## 4. Déroulement down()
La méthode `down()` doit supprimer toutes les tables créées, en commençant par les tables pivot pour éviter les erreurs de clés étrangères.
