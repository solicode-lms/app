<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCompetences\Services;

use Modules\PkgCompetences\Models\Module;
use Modules\Core\Services\BaseService;

/**
 * Classe ModuleService pour gérer la persistance de l'entité Module.
 */
class ModuleService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour modules.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'nom',
        'description',
        'masse_horaire',
        'filiere_id'
    ];

    /**
     * Renvoie les champs de recherche disponibles.
     *
     * @return array
     */
    public function getFieldsSearchable(): array
    {
        return $this->fieldsSearchable;
    }

    /**
     * Constructeur de la classe ModuleService.
     */
    public function __construct()
    {
        parent::__construct(new Module());
    }

    /**
     * Crée une nouvelle instance de module.
     *
     * @param array $data Données pour la création.
     * @return mixed
     */
    public function create(array $data)
    {
        $module = parent::create([
            'nom' => $data['nom'],
            'description' => $data['description'],
            'masse_horaire' => $data['masse_horaire'],
            'filiere_id' => $data['filiere_id'],
        ]);

        return $module;
    }
}
