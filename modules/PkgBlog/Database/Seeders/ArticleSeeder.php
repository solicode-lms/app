<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgBlog\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\PkgBlog\Models\Article;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

class ArticleSeeder extends Seeder
{
    public static int $order = 3;

    public function run(): void
    {
        $AdminRole = User::ADMIN;
        $MembreRole = User::MEMBRE;

        Schema::disableForeignKeyConstraints();
        Article::truncate();
        Schema::enableForeignKeyConstraints();

        $csvFile = fopen(base_path("modules/PkgBlog/Database/data/articles.csv"), "r");
        $firstline = true;
        while (($data = fgetcsv($csvFile)) !== false) {
            if (!$firstline) {
                Article::create([
                    "title" => $data[0] ,
                    "slug" => $data[1] ,
                    "content" => $data[2] ,
                    "category_id" => $data[3] ,
                    "user_id" => $data[4] 
                ]);
            }
            $firstline = false;
        }
        fclose($csvFile);

        $actions = [
            'index',
            'show',
            'create',
            'store',
            'edit',
            'update',
            'destroy',
            'export',
            'import',
            'getArticles'
        ];

        foreach ($actions as $action) {
            Permission::create(['name' => $action . '-ArticleController', 'guard_name' => 'web']);
        }

        $admin = Role::where('name', $AdminRole)->first();
        $membre = Role::where('name', $MembreRole)->first();

        $admin->givePermissionTo([
            'index-ArticleController',
            'show-ArticleController',
            'create-ArticleController',
            'store-ArticleController',
            'edit-ArticleController',
            'update-ArticleController',
            'destroy-ArticleController',
            'export-ArticleController',
            'import-ArticleController',
            'getArticles-ArticleController',
        ]);

        $membre->givePermissionTo([
            'index-ArticleController',
            'show-ArticleController'
        ]);
    }
}
