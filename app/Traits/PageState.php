<?php

namespace App\Traits;

trait PageState
{
    // Stocke les variables pour toutes les pages
    protected static $states = [];

    /**
     * Obtenir une clé unique pour la page courante.
     *
     * @return string
     */
    protected static function currentPageKey()
    {
        return request()->url(); // Utilise l'URL comme clé unique
    }

    /**
     * Définit une variable pour la page courante.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public static function setState(string $key, $value)
    {
        $contextStateKey = static::currentPageKey();
        static::$states[$contextStateKey][$key] = $value;
    }

    /**
     * Récupère une variable de la page courante.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function getState(string $key, $default = null)
    {
        $contextStateKey = static::currentPageKey();
        return static::$states[$contextStateKey][$key] ?? $default;
    }

    /**
     * Récupère toutes les variables pour la page courante.
     *
     * @return array
     */
    public static function allState()
    {
        $contextStateKey = static::currentPageKey();
        return static::$states[$contextStateKey] ?? [];
    }

    /**
     * Supprime une variable pour la page courante.
     *
     * @param string $key
     * @return void
     */
    public static function removeState(string $key)
    {
        $contextStateKey = static::currentPageKey();
        unset(static::$states[$contextStateKey][$key]);
    }
}
