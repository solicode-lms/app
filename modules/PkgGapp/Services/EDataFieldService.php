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

    /**
     * Met à jour un champ `EDataField` ainsi que ses métadonnées associées (ex : `displayOrder`, `widthColumn`).
     *
     * Cette méthode applique les modifications sur l'entité principale (`e_data_fields`) 
     * et, si des valeurs comme `ordre` ou `widthColumn` sont présentes dans `$data`, 
     * elle met à jour les métadonnées correspondantes.
     *
     * 🔁 Si un nouvel ordre (`ordre`) est fourni :
     * - Il est appliqué à l'entité cible.
     * - Puis, les autres champs du même modèle sont automatiquement réordonnés
     *   pour garantir une séquence d'affichage continue et sans conflit.
     *
     * @param int $id               ID du champ `EDataField` à mettre à jour.
     * @param array $data           Données de mise à jour (attributs du modèle + métadonnées éventuelles).
     *                              Exemples de clés : 'name', 'data_type', 'ordre', 'widthColumn'.
     *
     * @return \Modules\PkgGapp\Models\EDataField  L'entité mise à jour.
     *
     * @throws \Exception           Si une métadonnée comme `displayOrder` n’a pas de définition existante.
     */
    public function update($id, array $data)
    {
        $entity = parent::update($id, $data);

        // 🔁 Mise à jour des métadonnées si présentes
        if (array_key_exists('ordre', $data)) {
            $this->updateOrCreateMetadata($entity->id, "displayOrder", $data['ordre']);
            $this->reorderMetadataDisplayOrder($entity->e_model_id, $entity->id, $data['ordre']);
        }

        if (array_key_exists('widthColumn', $data)) {
            $this->updateOrCreateMetadata($entity->id, "widthColumn", $data['widthColumn']);
        }

        $this->metaSeedByDataFieldReference(true, $entity->reference);
        $this->updateGappCrud($entity->eModel);

        return $entity;
    }

    /**
     * Met à jour ou crée une métadonnée (`e_metadata`) liée à un champ `EDataField`.
     *
     * Cette méthode permet d'enregistrer dynamiquement une valeur associée à une clé (définie par `reference`)
     * dans la table `e_metadata`, en liant la valeur au champ `$eDataFieldId` via sa définition (`e_metadata_definition`).
     *
     * Elle est générique et peut être utilisée pour tout type de métadonnée simple, par exemple :
     * - `displayOrder` (ordre d'affichage)
     * - `widthColumn` (largeur de colonne)
     * - `displayInTable` (booléen d'affichage)
     *
     * ⚙️ Fonctionnement :
     * - Recherche la définition de la métadonnée via sa `reference`.
     * - Si elle existe, applique la valeur dans le champ adapté (ici `value_integer`).
     * - Si une entrée existe déjà → elle est mise à jour ; sinon → elle est créée.
     *
     * @param int $eDataFieldId  L'identifiant du champ (`e_data_fields.id`) concerné.
     * @param string $reference  La référence unique de la métadonnée (`e_metadata_definitions.reference`).
     * @param mixed $value       La valeur à associer (actuellement stockée dans `value_integer`).
     *
     * @throws \Exception        Si la définition de la métadonnée est introuvable.
     */
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

    /**
     * Réorganise l'ordre (`displayOrder`) des champs d'un modèle (`EDataField`) de façon cohérente.
     *
     * Cette méthode garantit que le champ ciblé ($targetId) soit positionné exactement à $newOrder,
     * tout en réajustant dynamiquement les `displayOrder` des autres champs du même modèle (`e_model_id`)
     * pour éviter les doublons et assurer une séquence continue (1, 2, 3...).
     *
     * 🔄 Algorithme :
     * - On récupère tous les champs liés à $modelId (incluant ou excluant $targetId).
     * - On les trie selon leur `displayOrder` actuel (les `null` sont traités en dernier).
     * - On parcourt les champs en attribuant les nouveaux ordres :
     *     → Quand on atteint $newOrder, on insère le champ $targetId.
     *     → Les autres sont décalés à partir de cette position.
     * - Si $newOrder dépasse le nombre total de champs, $targetId est inséré à la fin.
     *
     * @param int $modelId   ID de l’EModel auquel les champs appartiennent.
     * @param int $targetId  ID du champ EDataField dont l’ordre est à repositionner.
     * @param int $newOrder  Nouvel ordre souhaité (position 1-based).
     *
     * @return void
     */
    protected function reorderMetadataDisplayOrder(int $modelId, int $targetId, int $newOrder): void
    {
        $definition = EMetadataDefinition::where('reference', 'displayOrder')->first();

        if (!$definition) {
            throw new \Exception("La metadata definition 'displayOrder' est introuvable.");
        }

        // 1. Récupérer tous les champs du modèle concerné
        $allFields = $this->model::with('eMetadata.eMetadataDefinition')
            ->where('e_model_id', $modelId)
            ->get()
            ->sortBy(function ($field) {
                return $field->getOrder() ?? 9999;
            })->values();

        // 2. Réaffectation des ordres avec insertion de $targetId à $newOrder
        $index = 1;
        $inserted = false;

        foreach ($allFields as $field) {
            if ($index === (int) $newOrder) {
                // Insérer l'entité cible à cette position
                $this->updateOrCreateMetadata($targetId, 'displayOrder', $index);
                $index++;
                $inserted = true;
            }

            // Ne pas toucher au champ cible une seconde fois
            if ($field->id === $targetId) {
                continue;
            }

            $this->updateOrCreateMetadata($field->id, 'displayOrder', $index);
            $index++;
        }

        // Si le champ cible n'a pas encore été inséré (ordre trop grand)
        if (!$inserted) {
            $this->updateOrCreateMetadata($targetId, 'displayOrder', $index);
        }
    }

}
