<?php
namespace Modules\PkgQcm\Services;

use Modules\PkgQcm\Services\Base\BaseRealisationQcmService;
use Modules\PkgQcm\Models\EtatRealisationQcm;
use Modules\PkgRealisationProjets\Models\RealisationProjet;
use Modules\PkgApprentissage\Models\RealisationUaPrototype;

/**
 * Classe RealisationQcmService pour gérer la persistance de l'entité RealisationQcm.
 */
class RealisationQcmService extends BaseRealisationQcmService
{
    public function initQcm(int $realisationQcmId)
    {
        $realisationQcm = $this->find($realisationQcmId);
        if (!$realisationQcm) {
            $this->pushServiceMessage("danger", "Erreur", "Réalisation QCM introuvable.");
            return false; 
        }

        // 1. Supprimer toutes les réponses (et détacher les propositions associées)
        foreach ($realisationQcm->reponseQcms as $reponseQcm) {
            $reponseQcm->propositionReponses()->sync([]);
            $reponseQcm->delete();
        }

        // 2. Réinitialiser les dates et l'état
        $realisationQcm->date_debut = null;
        $realisationQcm->date_soumission = null;
        
        $etatAFaire = EtatRealisationQcm::where('reference', 'A_FAIRE')->first();
        if ($etatAFaire) {
            $realisationQcm->etat_realisation_qcm_id = $etatAFaire->id;
        }

        $value = $realisationQcm->save();
        $this->pushServiceMessage("success", "Initialisation réussie", "Le QCM a été réinitialisé avec succès et peut être repassé.");
        return $value;
    }

    public function afterUpdateRules($item, array $data)
    {
        // 1. Vérifier si l'état est "VALIDE"
        $etatValide = EtatRealisationQcm::where('reference', 'VALIDE')->first();
        
        if ($etatValide && $item->etat_realisation_qcm_id == $etatValide->id) {
            $affectationQcmProjet = $item->affectationQcmProjet;
            if (!$affectationQcmProjet) return;

            $affectationProjet = $affectationQcmProjet->affectationProjet;
            if (!$affectationProjet) return;

            // Trouver la RealisationProjet
            $realisationProjet = RealisationProjet::where('affectation_projet_id', $affectationProjet->id)
                ->where('apprenant_id', $item->apprenant_id)
                ->first();
                
            if (!$realisationProjet) return;

            // Trouver les RealisationUaPrototypes liées
            $realisationTachesIds = $realisationProjet->realisationTaches()->pluck('id');
            if ($realisationTachesIds->isEmpty()) return;

            $realisationUaPrototypes = RealisationUaPrototype::whereIn('realisation_tache_id', $realisationTachesIds)->get();

            foreach($realisationUaPrototypes as $rup) {
                $uniteApprentissageId = $rup->realisationUa->unite_apprentissage_id ?? null;
                
                if ($uniteApprentissageId) {
                    $resultat = $this->calculerNoteUa($item, $uniteApprentissageId);
                    $rup->note_qcm = $resultat['note'];
                    $rup->barem_qcm = $resultat['bareme'];
                    
                    if ($affectationQcmProjet->saise_automatique_note_qcm) {
                        $rup->note = ($rup->note ?? 0) + $resultat['note'];
                    }
                    
                    $rup->save();
                }
            }
        }
    }

    /**
     * Calcule la note et le barème obtenus par l'apprenant pour une Unité d'Apprentissage spécifique.
     *
     * @param \Modules\PkgQcm\Models\RealisationQcm $realisationQcm
     * @param int $uniteApprentissageId
     * @return array ['note' => float, 'bareme' => float]
     */
    public function calculerNoteUa($realisationQcm, $uniteApprentissageId)
    {
        $note = 0;
        $bareme = 0;
        
        $qcm = $realisationQcm->qcm;
        if (!$qcm) return ['note' => 0, 'bareme' => 0];
        
        $questionsUa = $qcm->questions()->where('unite_apprentissage_id', $uniteApprentissageId)->get();
        
        foreach($questionsUa as $question) {
            $bareme += $question->bareme;
            
            $reponse = $realisationQcm->reponseQcms()->where('question_id', $question->id)->first();
            if ($reponse) {
                $propositionsCorrectes = $question->propositionReponses()->where('is_correcte', true)->pluck('id')->toArray();
                $propositionsChoisies = $reponse->propositionReponses()->pluck('id')->toArray();
                
                sort($propositionsCorrectes);
                sort($propositionsChoisies);
                
                if (!empty($propositionsCorrectes) && $propositionsCorrectes == $propositionsChoisies) {
                    $note += $question->bareme;
                }
            }
        }
        
        return ['note' => $note, 'bareme' => $bareme];
    }
}
