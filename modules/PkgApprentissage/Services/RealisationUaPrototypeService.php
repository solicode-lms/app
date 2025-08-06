<?php


namespace Modules\PkgApprentissage\Services;
use Modules\PkgApprentissage\Services\Base\BaseRealisationUaPrototypeService;

/**
 * Classe RealisationUaPrototypeService pour gérer la persistance de l'entité RealisationUaPrototype.
 */
class RealisationUaPrototypeService extends BaseRealisationUaPrototypeService
{
public function afterUpdateRules($realisationUaPrototype): void
{
        // ✅ Si la note ou le barème du prototype a changé
        if ($realisationUaPrototype->wasChanged(['note', 'bareme'])) {

            // 🔁 1. Recalculer la note globale de l'UA
            if ($realisationUaPrototype->realisationUa) {
                (new RealisationUaService())->calculerProgressionEtNote($realisationUaPrototype->realisationUa);
            }

            // 🔁 2. Recalculer la note de la tâche à partir des prototypes
            if ($realisationUaPrototype->realisation_tache_id) {
                $tache = $realisationUaPrototype->realisationTache;

                if ($tache) {
                    $prototypes = \Modules\PkgApprentissage\Models\RealisationUaPrototype::where('realisation_tache_id', $tache->id)->get();

                    $noteTotale = $prototypes->sum(function ($proto) {
                        return min($proto->note ?? 0, $proto->bareme ?? 0);
                    });

                    $tache->note = round($noteTotale, 2);

                    // Attention si on appelle realisationTacheServiceUpdate, il va lancer la modification
                    // de realisationUaPrototype ce qui créer un boucle infinie
                    $tache->save();
                }
            }
        }
    }


}
