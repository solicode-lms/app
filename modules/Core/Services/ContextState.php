<?php

namespace Modules\Core\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Session;
use JsonSerializable;

class ContextState implements JsonSerializable
{
    /**
     * Nom de la session principale où les données sont stockées.
     */
    protected string $sessionKey = 'context_state';

    /**
     * Récupérer une valeur depuis le ContextState.
     *
     * @param string $key La clé à récupérer.
     * @param mixed $default Valeur par défaut si la clé n'existe pas.
     * @return mixed La valeur stockée ou la valeur par défaut.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return Arr::get(Session::get($this->sessionKey, []), $key, $default);
    }

    /**
     * Définir une valeur dans le ContextState.
     *
     * @param string $key La clé à définir.
     * @param mixed $value La valeur à stocker.
     */
    public function set(string $key, mixed $value): void
    {
        $data = Session::get($this->sessionKey, []);
        Arr::set($data, $key, $value);
        Session::put($this->sessionKey, $data);
    }

    /**
     * Vérifier si une clé existe dans le ContextState.
     *
     * @param string $key La clé à vérifier.
     * @return bool Retourne `true` si la clé existe, sinon `false`.
     */
    public function has(string $key): bool
    {
        return Arr::has(Session::get($this->sessionKey, []), $key);
    }

    /**
     * Récupérer toutes les valeurs du ContextState.
     *
     * @return array Toutes les valeurs stockées.
     */
    public function all(): array
    {
        return Session::get($this->sessionKey, []);
    }

    /**
     * Supprimer une clé du ContextState.
     *
     * @param string $key La clé à supprimer.
     */
    public function remove(string $key): void
    {
        $data = Session::get($this->sessionKey, []);
        Arr::forget($data, $key);
        Session::put($this->sessionKey, $data);
    }

    /**
     * Réinitialiser complètement le ContextState.
     */
    public function clear(): void
    {
        Session::forget($this->sessionKey);
    }

    /**
     * Implémentation de JsonSerializable pour permettre la sérialisation en JSON.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->all();
    }
}
