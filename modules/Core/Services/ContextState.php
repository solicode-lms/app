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

    // array key value : [formateur_id => "3" ]
    protected $userContexte = null;

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

    
    public function setGlobalContext(string $key, $value)
    {
        $key =  "__" . "global" ."__" . $key ;
        $this->variables[$key] = $value;
    }

    public function setModelContext(string $modelName, string $key, $value)
    {
        $key = $modelName . "__" . "model" ."__" . $key ;
        $this->variables[$key] = $value;
    }

    public function setFormContext(string $modelName,string $key, $value)
    {
        $key = $modelName . "__" . "form" ."__" . $key ;
        $this->variables[$key] = $value;
    }
    public function setFilterContext(string $modelName,string $key, $value)
    {
        $key = $modelName . "__" . "filter" ."__" . $key ;
        $this->variables[$key] = $value;
    }

    public function setTableContext(string $modelName,string $key, $value)
    {
        $key = $modelName . "__" . "table" ."__" . $key ;
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
    public function getModel(string $modelName,string $key, $default = null)
    {
        $key = $modelName . "__" . "model" ."__" . $key ;
        return $this->variables[$key] ?? $default;
    }
    public function getModelForm(string $modelName,string $key, $default = null)
    {
        $key = $modelName . "__" . "form" ."__" . $key ;
        return $this->variables[$key] ?? $default;
    }
    public function getModelFilter(string $modelName,string $key, $default = null)
    { 
        $key = $modelName . "__" . "filter" ."__" . $key ;
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


    public function getFormVariables(string $modelName): array
    {
        return $this->extractVariables($modelName, ['model', 'form']);
    }

    public function getTableVariables(string $modelName): array
    {
        return $this->extractVariables($modelName, ['table']);
    }

    public function getFilterVariables(string $modelName): array
    {
        return $this->extractVariables($modelName, ['filter']);
    }

    private function extractVariables(string $modelName, array $types): array
    {
        $variables = [];

        foreach ($this->variables as $key => $value) {
            foreach ($types as $type) {
                if (str_contains($key, "{$modelName}__{$type}__") || str_contains($key, '__global__')) {
                    // Nettoyer la clé en supprimant le préfixe
                    $cleanKey = preg_replace("/^({$modelName}__{$type}__|__global__)/", '', $key);
                    $variables[$cleanKey] = $value;
                }
            }
        }

        return $variables;
    }



    /**
     * Lire les valeurs de la requête et de la route avec un préfixe spécifique,
     * puis les stocker dans le contexte.
     *
     * @param Request $request
     * @param string $prefix - Préfixe des clés à extraire (par exemple, "context_").
     * @return void
     */
    public function readFromRequest(Request $request)
    {
        // Fusionner les données de la requête et de la route
        $allParams = array_merge($request->all(), $request->route() ? $request->route()->parameters() : []);
        
        $globalVariables = [];
        $contextualVariables = [];
    
        // Parcourir tous les paramètres
        foreach ($allParams as $key => $value) {
            if (
                str_contains($key, '__form__') 
                || str_contains($key, '__filter__') 
                || str_contains($key, '__table__')
                || str_contains($key, '__model__')
                || str_contains($key, '__global__')
                ) 
                {
                // Stocker directement les variables contextuelles (form, filter, table)
                $contextualVariables[$key] = $value;
            }
        }
    
        // Ajouter les variables globales au contexte
        foreach ($globalVariables as $key => $value) {
            $this->set($key, $value);
        }
    
        // Ajouter les variables contextuelles au contexte
        foreach ($contextualVariables as $key => $value) {
            $this->set($key, $value);
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
    public function getTitle(): string
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
            'title' => $this->getTitle(), // Utilise getOrGenerateTitle() si title est null
            'variables' => $this->all(),
        ];
    }

    /**
     * Vérifie si le Context State est activé.
     *
     * @return bool
     */
    public function isContextStateEnable(): bool
    {
        return !empty($this->variables);
    }

    public function setUserContexe($userContexte): void{
        $this->userContexte = $userContexte;
        foreach ($this->userContexte as $key => $value) {
            $this->set($key, $value);
        }
    }
}
