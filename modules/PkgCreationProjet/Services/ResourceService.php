<?php


namespace Modules\PkgCreationProjet\Services;
use Modules\PkgCreationProjet\Services\Base\BaseResourceService;

/**
 * Classe ResourceService pour gérer la persistance de l'entité Resource.
 */
class ResourceService extends BaseResourceService
{

      protected array $index_with_relations = ['projet'];

      /**
       * Hook appelé après la création d’une ressource.
       */
      public function afterCreateRules($resource)
      {
            if (isset($resource->projet)) {
                  $resource->projet->touch();
            }
      }

      /**
       * Hook appelé après la mise à jour d’une ressource.
       */
      public function afterUpdateRules($resource)
      {
            if (isset($resource->projet)) {
                  $resource->projet->touch();
            }
      }

      /**
       * Surcharge de la suppression pour mettre à jour la date du projet.
       */
      public function destroy($id)
      {
            $resource = $this->find($id);
            $result = parent::destroy($id);

            if ($resource && isset($resource->projet)) {
                  $resource->projet->touch();
            }

            return $result;
      }


}
