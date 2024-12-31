<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\Core\Services;

use Modules\Core\Models\SysColor;
use Modules\Core\Services\BaseService;

/**
 * Classe SysColorService pour gérer la persistance de l'entité SysColor.
 */
class PageVariables 
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
}
