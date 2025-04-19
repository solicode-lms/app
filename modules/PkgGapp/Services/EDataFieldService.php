<?php



namespace Modules\PkgGapp\Services;
use Modules\PkgGapp\Services\Base\BaseEDataFieldService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Core\App\Traits\GappCommands;
use Modules\PkgGapp\Models\EMetadataDefinition;
use Modules\PkgGapp\Models\EMetadatum;

/**
 * Classe EDataFieldService pour gérer la persistance de l'entité EDataField.
 */
class EDataFieldService extends BaseEDataFieldService
{
    use GappCommands;
    
    public function dataCalcul($eDataField)
    {
        // En Cas d'édit
        if(isset($eDataField->id)){
          
        }
      
        return $eDataField;
    }
   
    public function create($data)
    {
        $entity = parent::create($data);
        $this->metaSeedByDataFieldReference(true, $entity->reference);
        $this->updateGappCrud($entity->eModel);
        return $entity;
    }

    public function update($id, array $data)
    {
        $entity = parent::update($id, $data);

        // Mettre à jour l'ordre
        $this->updateOrCreateMetadata($entity->id, "displayOrder",$data['ordre']);


        $this->metaSeedByDataFieldReference(true, $entity->reference);
        
        $this->updateGappCrud($entity->eModel);
        return $entity;
    }
   

    

    public function destroy($id){
        $record = parent::destroy($id);
        $this->metaExport();
        return  $record;
    }
    
  /**
 * Paginer les EDataField en les triant par leur ordre (`displayOrder`).
 *
 * @param array $params
 * @param int $perPage
 * @param array $columns
 * @return LengthAwarePaginator
 */
/**
 * Paginer les EDataField en les triant par leur ordre (`displayOrder`).
 *
 * @param array $params
 * @param int $perPage
 * @param array $columns
 * @return LengthAwarePaginator
 */
public function paginate(array $params = [], int $perPage = 0, array $columns = ['*']): LengthAwarePaginator
{
    $perPage = $perPage ?: $this->paginationLimit;

    return $this->model::withScope(function () use ($params, $perPage, $columns) {
        $query = $this->allQuery($params);

        // Joindre e_metadata pour récupérer l'ordre de tri `displayOrder`
        $query->leftJoin('e_metadata as meta_order', function ($join) {
            $join->on('meta_order.e_data_field_id', '=', 'e_data_fields.id')
                 ->whereRaw('meta_order.e_metadata_definition_id IN (
                     SELECT id FROM e_metadata_definitions WHERE reference = "displayOrder"
                 )');
        });

       
        // Correction de l’ambiguïté en précisant `e_data_fields.e_model_id`
        if (!empty($params['e_model_id'])) {
            $query->whereRaw('e_data_fields.e_model_id = ?', [$params['e_model_id']]);
        }

      


        // Trier les résultats en fonction de `displayOrder`, en mettant les NULL en dernier
        $query->orderByRaw('COALESCE(meta_order.value_integer, 9999) ASC')
              ->select('e_data_fields.*'); // Sélectionner uniquement les colonnes de e_data_fields

        // Calcul du nombre total des résultats filtrés avant la pagination
        $this->totalFilteredCount = $query->count();

         // dd($query->toSql(), $query->getBindings());
        return $query->paginate($perPage, $columns);
    });
}


protected function updateOrCreateMetadata(int $eDataFieldId, string $reference, mixed $value): void
{
    $definition = EMetadataDefinition::where('reference', $reference)->first();

    if (!$definition) {
        throw new \Exception("La metadata definition '{$reference}' est introuvable.");
    }

    EMetadatum::updateOrCreate(
        [
            'e_data_field_id' => $eDataFieldId,
            'e_metadata_definition_id' => $definition->id,
        ],
        [
            'value_integer' => $value,
        ]
    );
}

}
