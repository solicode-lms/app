<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\Core\Services;

use Modules\Core\Models\SysColor;
use Modules\Core\Services\BaseService;
use Illuminate\Http\Request;
use JsonSerializable;

/**
 * Classe SysColorService pour gérer la persistance de l'entité SysColor.
 */
class ContextState  implements JsonSerializable
{
    protected $title = null;
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
     * Lire les valeurs de la requête et de la route avec un préfixe spécifique,
     * puis les stocker dans le contexte.
     *
     * @param Request $request
     * @param string $prefix - Préfixe des clés à extraire (par exemple, "context_").
     * @return void
     */
    public function readFromRequest(Request $request, string $prefix = 'context_')
    {
        // Fusionner les données de la requête et de la route
        $allParams = array_merge($request->all(), $request->route() ? $request->route()->parameters() : []);

        // Parcourir tous les paramètres
        foreach ($allParams as $key => $value) {
            // Vérifier si la clé commence par le préfixe
            if (str_starts_with($key, $prefix)) {
                // Supprimer le préfixe avant de stocker dans le contexte
                $cleanKey = substr($key, strlen($prefix));
                $this->set($cleanKey, $value);
            }
        }
    }

   

/**
     * Définit le titre du contexte.
     *
     * @param string|null $title
     * @return void
     */
    public function setTitle(?string $title)
    {
        $this->title = $title;
    }

    /**
     * Retourne le titre du contexte, ou le génère si vide.
     * Le titre est construit à partir des variables du contexte.
     *
     * @return string
     */
    public function getOrGenerateTitle(): string
    {
        if (empty($this->title)) {
            $this->title = $this->generateTitleFromVariables();
        }

        return $this->title;
    }

    /**
     * Génère un titre basé sur les variables du contexte.
     *
     * @return string
     */
    protected function generateTitleFromVariables(): string
    {
        $parts = [];

        foreach ($this->variables as $key => $value) {
            $parts[] = ucfirst($key) . ': ' . $value;
        }

        return implode(' | ', $parts);
    }

        /**
     * Personnalise la structure JSON de l'objet.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'title' => $this->getOrGenerateTitle(), // Utilise getOrGenerateTitle() si title est null
            'variables' => $this->all(),
        ];
    }
}
