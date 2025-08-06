<?php


namespace Modules\PkgApprentissage\Services;

use Modules\PkgApprentissage\Models\RealisationUaProjet;
use Modules\PkgApprentissage\Services\Base\BaseRealisationUaProjetService;

/**
 * Classe RealisationUaProjetService pour gérer la persistance de l'entité RealisationUaProjet.
 */
class RealisationUaProjetService extends BaseRealisationUaProjetService
{
    
  public function afterUpdateRules($realisationUaProjet): void
    {
        // Détection du changement de note ou de barème
        if ($realisationUaProjet->wasChanged(['note', 'bareme'])) {
            if ($realisationUaProjet->realisationUa) {
                (new RealisationUaService())->calculerProgressionEtNote($realisationUaProjet->realisationUa);
            }
        }

         // 🔁 2. Recalculer la note de la tâche à partir des realisationUaProjet
        if ($realisationUaProjet->realisation_tache_id) {
            $tache = $realisationUaProjet->realisationTache;

            if ($tache) {

                $realisationUaProjets = RealisationUaProjet::where('realisation_tache_id', $tache->id)->get();

                $noteTotale = $realisationUaProjets->sum(function ($proto) {
                    return min($proto->note ?? 0, $proto->bareme ?? 0);
                });

                $tache->note = round($noteTotale, 2);

                // Attention si on appelle realisationTacheServiceUpdate, il va lancer la modification
                // de realisationUaProjet ce qui créer un boucle infinie
                $tache->save();
            }
        }
    }
    
 
}
