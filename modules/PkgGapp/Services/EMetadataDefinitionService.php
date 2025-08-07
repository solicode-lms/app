<?php

namespace Modules\PkgGapp\Services;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\App\Traits\GappCommands;
use Modules\PkgGapp\Services\Base\BaseEMetadataDefinitionService;

/**
 * Classe EMetadataDefinitionService pour gérer la persistance de l'entité EMetadataDefinition.
 */
class EMetadataDefinitionService extends BaseEMetadataDefinitionService
{
    use GappCommands;
    
    

    /**
     * Override de la méthode create
     */
    public function create($data)
    {
        $value = parent::create($data);
        $this->metaExport();
        return $value;
    }

    /**
     * Override de la méthode update
     */
    public function update($id, array $data)
    {
        $model = $this->find($id); // ou $this->query()->findOrFail($id)

        $model->fill($data); // les données sont modifiées, mais pas encore sauvées
    
        // 👉 Tu peux tester isDirty ici
        if ($model->isDirty('name')) {
            $model->reference = $model->generateReference();
        }
    
        $model->save(); // sauvegarde finale avec ou sans référence
    
        // Export des métadonnées après mise à jour
        $this->metaExport();

        return $model;
    }

    
}
