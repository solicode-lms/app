<?php


namespace Modules\PkgCreationProjet\Services;
use Modules\PkgCreationProjet\Services\Base\BaseLivrableService;

/**
 * Classe LivrableService pour gérer la persistance de l'entité Livrable.
 */
class LivrableService extends BaseLivrableService
{

     protected array $index_with_relations = ['projet'];

     /**
      * Hook appelé après la création d’un livrable.
      */
     public function afterCreateRules($livrable)
     {
          if (isset($livrable->projet)) {
               $livrable->projet->touch();
          }
     }

     /**
      * Hook appelé après la mise à jour d’un livrable.
      */
     public function afterUpdateRules($livrable)
     {
          if (isset($livrable->projet)) {
               $livrable->projet->touch();
          }
     }

     /**
      * Surcharge de la suppression pour mettre à jour la date du projet.
      */
     public function destroy($id)
     {
          $livrable = $this->find($id);
          $result = parent::destroy($id);

          if ($livrable && isset($livrable->projet)) {
               $livrable->projet->touch();
          }

          return $result;
     }

}
