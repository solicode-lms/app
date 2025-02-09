<?php

namespace Modules\Core\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Session;

class ViewState
{
    /**
     * Nom de la session principale où les données sont stockées.
     */
    protected string $sessionKey = 'view_state';

    protected $viewStateData;

    protected $title = null;

    /**
     * Clé de la vue en cours.
     */
    protected string $currentViewKey;

    /**
     * Constructeur de ViewState.
     *
     * @param string $viewKey Clé unique de la vue (ex: 'projet.edit').
     */
    public function __construct(string $viewKey)
    {
        $this->currentViewKey = $viewKey;
    }

    /**
     * Récupérer la clé actuelle de la vue.
     *
     * @return string Clé de la vue active.
     */
    public function getViewKey(): string
    {
        return $this->currentViewKey;
    }

    /**
     * Récupérer une valeur depuis le ViewState pour la vue actuelle.
     *
     * @param string $key La clé à récupérer (ex: 'scope.livrable.projet_id').
     * @param mixed $default Valeur par défaut si la clé n'existe pas.
     * @return mixed La valeur stockée ou la valeur par défaut.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return Arr::get(Session::get("{$this->sessionKey}.views.{$this->currentViewKey}", []), $key, $default);
    }

    /**
     * Définir une valeur dans le ViewState pour la vue actuelle.
     *
     * @param string $key La clé à définir (ex: 'scope.livrable.projet_id').
     * @param mixed $value La valeur à stocker.
     */
    public function set(string $key, mixed $value): void
    {
        $data = Session::get("{$this->sessionKey}.views.{$this->currentViewKey}", []);
        Arr::set($data, $key, $value);
        Session::put("{$this->sessionKey}.views.{$this->currentViewKey}", $data);
        $this->viewStateData = $this->getViewData();
    }

    /**
     * Vérifier si une clé existe dans le ViewState pour la vue actuelle.
     *
     * @param string $key La clé à vérifier.
     * @return bool Retourne `true` si la clé existe, sinon `false`.
     */
    public function has(string $key): bool
    {
        return Arr::has(Session::get("{$this->sessionKey}.views.{$this->currentViewKey}", []), $key);
    }

    /**
     * Supprimer une clé spécifique de la vue actuelle.
     *
     * @param string $key La clé à supprimer.
     */
    public function remove(string $key): void
    {
        $data = Session::get("{$this->sessionKey}.views.{$this->currentViewKey}", []);
        Arr::forget($data, $key);
        Session::put("{$this->sessionKey}.views.{$this->currentViewKey}", $data);
    }

    /**
     * Réinitialiser complètement le ViewState de la vue actuelle.
     */
    public function clear(): void
    {
        Session::forget("{$this->sessionKey}.views.{$this->currentViewKey}");
    }

    /**
     * Récupérer toutes les données de la vue actuelle.
     *
     * @return array Données stockées pour cette vue.
     */
    public function getViewData(): array
    {
        $data =  Session::get("{$this->sessionKey}.views.{$this->currentViewKey}", []);
        $data =  Arr::dot($data) ;
        return $data;
    }
    public function getArrayData(): array
    {
        $data =  Session::get("{$this->sessionKey}.views.{$this->currentViewKey}", []);
        return $data;
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

        foreach ($this->getViewData() as $key => $value) {
            $parts[] = ucfirst($key) . ': ' . $value;
        }

        return implode(' | ', $parts);
    }




    public function getScopeVariables(string $modelName): array
    {
        return $this->extractVariables($modelName, ['scope']);
    }

    /**
     * Récupérer les variables du formulaire pour un modèle donné.
     *
     * @param string $modelName
     * @return array
     */
    public function getFormVariables(string $modelName): array
    {
        return $this->extractVariables($modelName, ['scope','form']);
    }

    /**
     * Récupérer les variables de la table pour un modèle donné.
     *
     * @param string $modelName
     * @return array
     */
    public function getTableVariables(string $modelName): array
    {
        return $this->extractVariables($modelName, ['scope','table']);
    }

    /**
     * Récupérer les variables de filtre pour un modèle donné.
     *
     * @param string $modelName
     * @return array
     */
    public function getFilterVariables(string $modelName): array
    {
        return $this->extractVariables($modelName, ['scope','filter']);
    }

    /**
     * Extraire les variables selon le type et le modèle donné.
     *
     * @param string $modelName
     * @param array $types
     * @return array
     */
    private function extractVariables(string $modelName, array $types): array
    {
        $viewData = $this->getViewData();
        $filteredVariables = [];

        foreach ($viewData as $key => $value) {
            foreach ($types as $type) {
                if (str_starts_with($key, "$type.$modelName.") ) {
                    $filteredKey = str_replace("$type.$modelName.", '', $key);
                    $filteredVariables[$filteredKey] = $value;
                }
                if (str_starts_with($key, "$type.global.")) {
                    $filteredKey = str_replace("$type.global.", '', $key);
                    $filteredVariables[$filteredKey] = $value;
                }
            }
        }

        return $filteredVariables;
    }

}
