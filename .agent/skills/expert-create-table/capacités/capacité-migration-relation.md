# Capacité : Migration de Relation entre Deux Tables

Cette capacité définit les standards pour ajouter ou modifier des relations de base de données (clés étrangères ou tables pivots) entre des tables existantes dans le projet Solicode LMS.

---

## 1. Relation Many-To-One (Ajout d'une clé étrangère)

Lorsque vous ajoutez une relation Many-To-One sur une table existante, vous devez modifier cette table pour ajouter une colonne de clé étrangère.

### Syntaxe de la Migration

```php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('nom_table_source', function (Blueprint $table) {
            // Utiliser foreignId avec constrained et spécifier l'action onDelete
            $table->foreignId('nom_relation_id')
                  ->nullable() // Rendre nullable si la relation est optionnelle
                  ->constrained('nom_table_cible')
                  ->onDelete('cascade'); // Ou onDelete('set null') selon le besoin métier
        });
    }

    public function down(): void
    {
        Schema::table('nom_table_source', function (Blueprint $table) {
            // Il est obligatoire de d'abord supprimer la contrainte avant de drop la colonne
            $table->dropForeign(['nom_relation_id']);
            $table->dropColumn('nom_relation_id');
        });
    }
};
```

---

## 2. Relation Many-To-Many (Création d'une table pivot)

Lorsque la relation est de type Many-ToMany, vous devez créer une nouvelle table pivot intermédiaire.

### Règles d'Or pour les Tables Pivots :
1. **Pas d'ID principal** : Ne pas mettre `$table->id()`.
2. **Nomenclature** : Clés étrangères nommées exactement selon le modèle cible en snake_case (ex: `label_projet_id`).
3. **Ordre dans `down()`** : Supprimer la table pivot pour éviter des blocages de contraintes.

### Syntaxe de la Migration

```php
return new class extends Migration {
    public function up(): void
    {
        Schema::create('table1_table2', function (Blueprint $table) {
            $table->foreignId('table1_model_id')->constrained('table1_name')->onDelete('cascade');
            $table->foreignId('table2_model_id')->constrained('table2_name')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('table1_table2');
    }
};
```

---

## 3. Workflow de Régénération avec Gapp

Une fois la relation ajoutée via la migration :
1. Proposer à l'utilisateur d'exécuter la migration (`php artisan migrate`).
2. Indiquer qu'il faut synchroniser les métadonnées de Gapp :
   ```bash
   php artisan gapp meta:sync
   ```
3. Indiquer qu'il faut régénérer les CRUD des modèles concernés par la relation :
   ```bash
   php artisan gapp make:crud NomModelSource
   php artisan gapp make:crud NomModelCible
   ```
