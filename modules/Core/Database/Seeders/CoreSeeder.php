<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\Core\Database\Seeders;
use Modules\Core\Database\Seeders\BaseSeeder;

class CoreSeeder extends BaseSeeder
{
    public function run(): void
    {
        parent::loadAndRun(__DIR__,__NAMESPACE__);
    }
}