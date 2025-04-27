<?php

namespace Modules\PkgWidgets\Services\WidgetServiceTraits;

use Illuminate\Support\Collection;
use Modules\Core\Services\SysColorService;

trait WidgetResultFormatter
{
    public function resultFormatter($result, array $query, $widget)
    {
        if ($widget->type->type === 'table' && isset($query['tableUI'])) {
            if ($result instanceof Collection) {
                $widget->data = $this->formatCollection($result, $query['tableUI']);
            } elseif (is_array($result)) {
                $widget->data = $this->formatArray($result, $query['tableUI']);
            }
        } else {
            $widget->data = $result;
        }
    }

     /**
     * Formate les résultats en fonction de la configuration `tableUI`.
     *
     * @param \Illuminate\Support\Collection $result Résultats bruts de la requête.
     * @param array $tableUI Configuration des colonnes à afficher.
     * @return array Données formatées sous forme de table.
     */
    private function formatCollection($result, array $tableUI)
    {
        // Trier selon l'ordre défini
        usort($tableUI, fn($a, $b) => ($a['order'] ?? 0) <=> ($b['order'] ?? 0));
        $sysColorService = new SysColorService();
    
        return $result->map(function ($item) use ($tableUI,$sysColorService) {
            $formattedRow = [];
    
            foreach ($tableUI as $columnConfig) {
                $label = $columnConfig['label'];
                $path = $columnConfig['key'];
                $nature = $columnConfig['nature'] ?? "String";
    
                $value = method_exists($item, 'getNestedValue')
                    ? $item->getNestedValue($path)
                    : data_get($item, $path, '');
    
                $formattedRow[$label] = $value;

                switch ($nature) {
                    case "deadline": {
                        if (is_string($value)) {
                            $value = \Carbon\Carbon::parse($value); // Convertir string en Carbon
                        }
                    
                        if ($value instanceof \DateTimeInterface) {
                            $now = now();
                            $inPast = $value < $now; // vérifier si la date est passée ou future
                    
                            if ($inPast) {
                                // Si le temps est dépassé, afficher directement la date formatée
                                $duree = "{$value->format('d/m/Y')}";
                                $formattedRow[$label] = [
                                    'value' => $duree,
                                    'String' => $nature,
                                ];
                            } else {
                                // Sinon afficher la durée restante
                                $diff = $value->diff($now);
                                $jours = $diff->d;
                                $heures = $diff->h;
                    
                                $duree = "{$jours} jours {$heures} heures";

                                if($jours == 0){
                                    $couleur = "#dc3545";
                                }else{
                                    $couleur = "#17a2b8";
                                }
                              

                                $formattedRow[$label] = [
                                    'value' => $duree,
                                    'nature' => "badge",
                                    'couleur' => $couleur,
                                    'textCouleur' => $sysColorService->getTextColorForBackground($couleur)
                                ];
                            }
                        } else {
                            $duree = null;
                        }
                    
                      
                        break;
                    }
                    case "duree": {
                        if (is_string($value)) {
                            $value = \Carbon\Carbon::parse($value); // Convertir string en Carbon
                        }
                    
                        if ($value instanceof \DateTimeInterface) {
                            $now = now();
                            $inPast = $value < $now; // vérifier si la date est passée ou future
                    
                            $diff = $value->diff($now);
                            $jours = $diff->d;
                            $heures = $diff->h;
                    
                            $prefix = $inPast ? '-' : ''; // si date dans le passé => préfixe négatif
                    
                            $duree = "{$prefix}{$jours} jours {$heures} heures";
                        } else {
                            $duree = null;
                        }
                    
                        $formattedRow[$label] = [
                            'value' => $duree,
                            'String' => $nature,
                        ];
                        break;
                    }
                    
                    case "String":{
                        $formattedRow[$label] = [
                            'value' => $value,
                            'nature' => $nature
                        ];
                        break;
                    }
                    case "badge": {
                        $couleur_path = $columnConfig['couleur'];
                        $couleur = method_exists($item, 'getNestedValue')
                        ? $item->getNestedValue($couleur_path)
                        : data_get($item, $path, '');

                        $formattedRow[$label] = [
                            'value' => $value,
                            'nature' => $nature,
                            'couleur' => $couleur,
                            'textCouleur' => $sysColorService->getTextColorForBackground($couleur)
                        ];
                        break;
                    }
                    default : {
                        $formattedRow[$label] = [
                            'value' => $value,
                            'nature' => $nature
                        ];
                    }  
                }

            }
    
            return $formattedRow;
        })->toArray();
    }
    

    /**
     * Formate un tableau brut en fonction de la configuration `tableUI`.
     *
     * @param array $data Données à formater (tableau d’objets ou d’associatifs).
     * @param array $tableUI Configuration des colonnes à afficher.
     * @return array Données formatées sous forme de table.
     */
    private function formatArray(array $data, array $tableUI): array
    {
        // Trier selon l'ordre défini
        usort($tableUI, fn($a, $b) => ($a['order'] ?? 0) <=> ($b['order'] ?? 0));
    
        return array_map(function ($item) use ($tableUI) {
            $formattedRow = [];
    
            foreach ($tableUI as $columnConfig) {
                $label = $columnConfig['label'];
                $path = $columnConfig['key'];
    
                $value = is_array($item) || is_object($item)
                    ? data_get($item, $path)
                    : null;
    
                $formattedRow[$label] = $value;
            }
    
            return $formattedRow;
        }, $data);
    }
 
}
