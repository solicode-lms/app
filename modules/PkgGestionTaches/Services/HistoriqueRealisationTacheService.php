<?php


namespace Modules\PkgGestionTaches\Services;

use Illuminate\Support\Facades\Auth;
use Modules\Core\Utils\DateUtil;
use Modules\PkgGestionTaches\Models\HistoriqueRealisationTache;
use Modules\PkgGestionTaches\Models\RealisationTache;
use Modules\PkgGestionTaches\Services\Base\BaseHistoriqueRealisationTacheService;

/**
 * Classe HistoriqueRealisationTacheService pour gérer la persistance de l'entité HistoriqueRealisationTache.
 */
class HistoriqueRealisationTacheService extends BaseHistoriqueRealisationTacheService
{
    public function dataCalcul($historiqueRealisationTache)
    {
        // En Cas d'édit
        if(isset($historiqueRealisationTache->id)){
          
        }
      
        return $historiqueRealisationTache;
    }

    /**
     * Enregistrer les changement effectuer sur un objet realisationTache
     * @param \Modules\PkgGestionTaches\Services\RealisationTache $realisationTache
     * @param array $nouveauxChamps
     * @return void
     */
    public function enregistrerChangement(RealisationTache $realisationTache, array $nouveauxChamps)
    {
        $champsModifies = [];

        foreach ($nouveauxChamps as $champ => $nouvelleValeur) {
            $ancienneValeur = $realisationTache->$champ ?? null;

            // 🔍 Si l'ancien OU le nouveau est une date / datetime, on formate avant comparaison
            if (DateUtil::estDateOuDateTime($ancienneValeur) || DateUtil::estDateOuDateTime($nouvelleValeur)) {
                $ancienneFormatee = DateUtil::formatterDate($ancienneValeur);
                $nouvelleFormatee = DateUtil::formatterDate($nouvelleValeur);

                if ($ancienneFormatee !== $nouvelleFormatee) {
                    $champsModifies[$champ] = $nouvelleValeur;
                }
            } else {
                // Cas normal
                if ($ancienneValeur != $nouvelleValeur) {
                    $champsModifies[$champ] = $nouvelleValeur;
                }
            }
        }

        if (!empty($champsModifies)) {

            // 🧠 Détecter si c'est un feedback
            $isFeedback = isset($champsModifies['remarques_formateur']);
            
            $changement = collect($champsModifies)
                ->map(function ($value, $key) use ($realisationTache) {
                    $label = ucfirst(__("PkgGestionTaches::realisationTache.$key")); // 💬 traduction via lang('fields.nom_champ')

                    // 🛠️ Vérifier si c'est une relation ManyToOne
                    // 🛠️ Est-ce que ce champ est une clé étrangère ManyToOne ?
                    if (isset($realisationTache->manyToOne)) {
                        foreach ($realisationTache->manyToOne as $relationName => $relationData) {
                            if (array_key_exists('foreign_key', $relationData) && $relationData['foreign_key'] === $key) {
                                // Charger la nouvelle entité par son ID
                                $modelClass = $relationData['model'];
                                $nouvelObjet = $modelClass::find($value);
                                if ($nouvelObjet) {
                                    return "$label : " . $nouvelObjet->__toString();
                                }
                            }
                        }
                    }




                    return "$label : " . (is_scalar($value) ? $value : json_encode($value));
                })
                ->implode(' </br> ');

                HistoriqueRealisationTache::create([
                    'realisation_tache_id' => $realisationTache->id,
                    'dateModification' => now(),
                    'changement' => $changement,
                    'user_id' => Auth::user()?->id,
                    'isFeedback' => $isFeedback,
                ]);
        }
    }

   
        
   
}
