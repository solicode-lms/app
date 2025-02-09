<?php

namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Session;

class ViewState
{
    /**
     * Nom de la session principale où les données sont stockées.
     */
    protected string $sessionKey = 'view_state';

    /**
     * Définir la vue actuelle dans la session.
     *
     * @param string $viewKey Nom unique de la vue (ex: 'projet.edit').
     */
    public function setViewKey(string $viewKey): void
    {
        Session::put("{$this->sessionKey}.current_view", $viewKey);
    }

    /**
     * Récupérer la clé actuelle de la vue.
     *
     * @return string|null Clé de la vue active.
     */
    public function getViewKey(): ?string
    {
        return Session::get("{$this->sessionKey}.current_view");
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
        $viewKey = $this->getViewKey();
        return Arr::get(Session::get("{$this->sessionKey}.views.{$viewKey}", []), $key, $default);
    }

    /**
     * Définir une valeur dans le ViewState pour la vue actuelle.
     *
     * @param string $key La clé à définir (ex: 'scope.livrable.projet_id').
     * @param mixed $value La valeur à stocker.
     */
    public function set(string $key, mixed $value): void
    {
        $viewKey = $this->getViewKey();
        $data = Session::get("{$this->sessionKey}.views.{$viewKey}", []);
        Arr::set($data, $key, $value);
        Session::put("{$this->sessionKey}.views.{$viewKey}", $data);
    }

    /**
     * Vérifier si une clé existe dans le ViewState pour la vue actuelle.
     *
     * @param string $key La clé à vérifier.
     * @return bool Retourne `true` si la clé existe, sinon `false`.
     */
    public function has(string $key): bool
    {
        $viewKey = $this->getViewKey();
        return Arr::has(Session::get("{$this->sessionKey}.views.{$viewKey}", []), $key);
    }

    /**
     * Supprimer une clé spécifique de la vue actuelle.
     *
     * @param string $key La clé à supprimer.
     */
    public function remove(string $key): void
    {
        $viewKey = $this->getViewKey();
        $data = Session::get("{$this->sessionKey}.views.{$viewKey}", []);
        Arr::forget($data, $key);
        Session::put("{$this->sessionKey}.views.{$viewKey}", $data);
    }

    /**
     * Réinitialiser complètement le ViewState de la vue actuelle.
     */
    public function clear(): void
    {
        $viewKey = $this->getViewKey();
        Session::forget("{$this->sessionKey}.views.{$viewKey}");
    }
}
