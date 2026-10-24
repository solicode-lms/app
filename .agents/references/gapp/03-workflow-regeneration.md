# Référence Gapp : Workflow de Régénération CRUD

Lorsqu'une table ou une relation est ajoutée/modifiée, voici l'ordre strict des opérations à suivre :

1. **Migration BDD :** Le développeur exécute `php artisan migrate`.
2. **Synchronisation Gapp :** Exécuter `gapp meta:sync` pour importer les nouvelles colonnes dans Gapp.
3. **Génération CRUD :** Exécuter `gapp make:crud [NomModel]` pour régénérer le modèle et ses classes associées.
   - *Rappel :* Régénérer également tous les modèles en relation (ManyToOne / ManyToMany) car leurs vues sont impactées.
4. **Seeders de Permissions :** Exécuter le seeder généré pour insérer les permissions Spatie :
   ```bash
   php artisan db:seed --class=Modules\[Module]\Database\Seeders\[Model]Seeder
   ```
5. **Traductions :** Compléter le fichier `modules/[Module]/resources/lang/fr/[model].php`.
