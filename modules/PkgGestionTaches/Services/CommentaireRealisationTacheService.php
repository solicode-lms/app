<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgRealisationTache\Services;
use Modules\PkgRealisationTache\Services\Base\BaseCommentaireRealisationTacheService;

/**
 * Classe CommentaireRealisationTacheService pour gérer la persistance de l'entité CommentaireRealisationTache.
 */
class CommentaireRealisationTacheService extends BaseCommentaireRealisationTacheService
{
    public function dataCalcul($commentaireRealisationTache)
    {
        // En Cas d'édit
        if(isset($commentaireRealisationTache->id)){
          
        }
      
        return $commentaireRealisationTache;
    }
   
}
