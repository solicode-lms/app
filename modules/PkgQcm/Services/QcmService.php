<?php
namespace Modules\PkgQcm\Services;
use Modules\PkgQcm\Services\Base\BaseQcmService;

/**
 * Classe QcmService pour gérer la persistance de l'entité Qcm.
 */
class QcmService extends BaseQcmService
{
    /**
     * Surcharge de la création d'instance pour injecter des valeurs par défaut
     * avant l'envoi vers le formulaire de création (Create).
     *
     * @param array $data
     * @return \Modules\PkgQcm\Models\Qcm
     */
    public function createInstance(array $data = [])
    {
        if (!isset($data['is_publie'])) {
            $data['is_publie'] = true;
        }
        
        return parent::createInstance($data);
    }
}
