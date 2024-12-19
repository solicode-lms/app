<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgBlog\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\PkgBlog\Models\Comment;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

class CommentSeeder extends Seeder
{
    public static int $order = 6;

    public function run(): void
    {
        $AdminRole = User::ADMIN;
        $MembreRole = User::MEMBRE;

        Schema::disableForeignKeyConstraints();
        Comment::truncate();
        Schema::enableForeignKeyConstraints();

        $csvFile = fopen(base_path("modules/PkgBlog/Database/data/comments.csv"), "r");
        $firstline = true;
        while (($data = fgetcsv($csvFile)) !== false) {
            if (!$firstline) {
                Comment::create([
                    "content" => $data[0] ,
                    "user_id" => $data[1] ,
                    "article_id" => $data[2] 
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
            'getComments'
        ];

        foreach ($actions as $action) {
            Permission::create(['name' => $action . '-CommentController', 'guard_name' => 'web']);
        }

        $admin = Role::where('name', $AdminRole)->first();
        $membre = Role::where('name', $MembreRole)->first();

        $admin->givePermissionTo([
            'index-CommentController',
            'show-CommentController',
            'create-CommentController',
            'store-CommentController',
            'edit-CommentController',
            'update-CommentController',
            'destroy-CommentController',
            'export-CommentController',
            'import-CommentController',
            'getComments-CommentController',
        ]);

        $membre->givePermissionTo([
            'index-CommentController',
            'show-CommentController'
        ]);
    }
}
