<?php

namespace Modules\PkgWidgets\Entities;

/**
 * Classe représentant un widget en tant qu'objet métier pour l'affichage.
 */
class WidgetEntity
{
    public string $title;
    public string $icon;
    public int $count;
    public array $data;
    public array $config;

    /**
     * Constructeur du widget.
     *
     * @param string $title Titre du widget.
     * @param string $icon Icône associée au widget.
     * @param int $count Nombre d'éléments affichés.
     * @param array $data Données associées au widget.
     * @param array $config Configuration supplémentaire.
     */
    public function __construct(string $title, string $icon, int $count, array $data = [], array $config = [])
    {
        $this->title = $title;
        $this->icon = $icon;
        $this->count = $count;
        $this->data = $data;
        $this->config = $config;
    }

    /**
     * Convertit le widget en tableau.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'icon' => $this->icon,
            'count' => $this->count,
            'data' => $this->data,
            'config' => $this->config
        ];
    }

    /**
     * Crée un widget à partir d'un tableau.
     *
     * @param array $attributes Données du widget.
     * @return self
     */
    public static function fromArray(array $attributes): self
    {
        return new self(
            $attributes['title'] ?? 'Widget',
            $attributes['icon'] ?? 'default-icon',
            $attributes['count'] ?? 0,
            $attributes['data'] ?? [],
            $attributes['config'] ?? []
        );
    }
}
