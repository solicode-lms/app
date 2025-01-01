<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\Core\Services;

use Modules\Core\Models\SysColor;
use Modules\Core\Services\BaseService;
use Illuminate\Http\Request;

/**
 * Classe SysColorService pour gérer la persistance de l'entité SysColor.
 */
class ContextState 
{
    protected $variables = [];

/**
 * Définir une variable.
 *
 * @param string $key
 * @param mixed $value
 * @return void
 */
public function set(string $key, $value)
{
    $this->variables[$key] = $value;
}

/**
 * Récupérer une variable.
 *
 * @param string $key
 * @param mixed $default
 * @return mixed
 */
public function get(string $key, $default = null)
{
    return $this->variables[$key] ?? $default;
}

/**
 * Obtenir toutes les variables.
 *
 * @return array
 */
public function all(): array
{
    return $this->variables;
}

    /**
     * Lire les valeurs de la requête et les stocker comme variables de la page.
     *
     * @param Request $request
     * @return void
     */
    public function readFromRequest(Request $request)
    {
        // Extraire les paramètres de routage scop_entity et scop_id
        $scop_entity = $request->get('scop_entity', null);
        $scop_id = $request->get('scop_id', null);

        // Stocker les valeurs si elles existent
        if ($scop_entity) {
            $this->set('scop_entity', $scop_entity);
        }

        if ($scop_id) {
            $this->set('scop_id', $scop_id);
        }
    }
}
