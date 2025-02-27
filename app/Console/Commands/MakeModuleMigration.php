<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputArgument;

class MakeModuleMigration extends GeneratorCommand
{
    /**
     * exemple : php artisan make:module-migration fixDeleteOnCascade PkgCreationProjet
     *
     * @var string
     */
    protected $name = 'make:module-migration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new migration file in a custom module directory';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Migration';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return $this->resolveStubPath('/stubs/migration.stub');
    }

    /**
     * Resolve the fully-qualified path to the stub.
     *
     * @param  string  $stub
     * @return string
     */
    protected function resolveStubPath($stub)
    {
        return file_exists($customPath = base_path(trim($stub, '/')))
            ? $customPath
            : __DIR__ . '/stubs/migration.stub';
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {
        // Récupérer le module passé en argument
        $module = $this->argument('module');
    
        // Nettoyer le nom de la migration pour éviter les espaces ou caractères inutiles
        $fileName = str_replace(['App', '\\', '/'], '', $name); // Supprimer "App" ou autres éléments indésirables

        // Construire le chemin complet
        return base_path("modules/$module/Database/Migrations") . '/' . $this->getDatePrefix() . "_{$fileName}.php";
    }

    /**
     * Get the date prefix for the migration.
     *
     * @return string
     */
    protected function getDatePrefix()
    {
        return date('Y_m_d_His');
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the migration'],
            ['module', InputArgument::REQUIRED, 'The name of the module'],
        ];
    }

    protected function buildClass($name)
    {
        $stub = parent::buildClass($name);
    
        // Remplacer la placeholder {{ tableName }} par le nom réel de la table
        $tableName = $this->argument('name'); // Récupère le nom passé en argument
        $tableName = str_replace('create_', '', $tableName); // Supprime le préfixe "create_"
        $tableName = str_replace('_table', '', $tableName); // Supprime le suffixe "_table"
    
        return str_replace('{{ tableName }}', $tableName, $stub);
    }
    

}
