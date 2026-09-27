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

        $reponseQcmService = app(\Modules\PkgQcm\Services\ReponseQcmService::class);
        // 1. Supprimer toutes les réponses (et détacher les propositions associées)
        foreach ($realisationQcm->reponseQcms as $reponseQcm) {
            $reponseQcm->propositionReponses()->sync([]);
            $reponseQcmService->destroy($reponseQcm->id);
        }

        // 2. Réinitialiser les dates, l'état, la note et la validation
        $dataToUpdate = [
            'date_debut' => null,
            'date_soumission' => null,
            'date_validation' => null,
            'note_obtenu' => null
        ];
        
        $etatAFaire = EtatRealisationQcm::where('reference', 'A_FAIRE')->first();
        if ($etatAFaire) {
            $dataToUpdate['etat_realisation_qcm_id'] = $etatAFaire->id;
        }

        // Utilisation de withoutEvents pour éviter de déclencher l'Observer.
        // L'évaluation (remise à zéro) est gérée explicitement juste en dessous.
        \Modules\PkgQcm\Models\RealisationQcm::withoutEvents(function () use ($realisationQcm, $dataToUpdate) {
            $realisationQcm->update($dataToUpdate);
        });
        $value = $realisationQcm;
        
        // Mettre à jour (effacer) les notes du prototype
        $realisationQcm->refresh();
        $this->evaluerUaPrototypes($realisationQcm);
        
        $this->pushServiceMessage("success", "Initialisation réussie", "Le QCM a été réinitialisé avec succès et peut être repassé.");
        return $value;
    }

    public function evaluerUaPrototypes($item, $jobManager = null)
    {
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

        $etatValide = EtatRealisationQcm::where('reference', 'VALIDE')->first();
        $etatSoumis = EtatRealisationQcm::where('reference', 'SOUMIS')->first();
        
        $isValideOrSoumis = (($etatValide && $item->etat_realisation_qcm_id == $etatValide->id) || 
                             ($etatSoumis && $item->etat_realisation_qcm_id == $etatSoumis->id));

        if ($jobManager) {
            $jobManager->initProgress(count($realisationUaPrototypes) + 1);
        }

        foreach($realisationUaPrototypes as $rup) {
            $uniteApprentissage = $rup->realisationUa->uniteApprentissage ?? null;
            $uniteApprentissageId = $uniteApprentissage->id ?? null;
            $uniteApprentissageNom = $uniteApprentissage->nom ?? "#" . $uniteApprentissageId;
            
            if ($uniteApprentissageId) {
                if ($jobManager) {
                    $jobManager->setLabel("Évaluation de l'UA : " . $uniteApprentissageNom);
                }
                
                $resultat = $this->calculerNoteUa($item, $uniteApprentissageId);
                
                // On met à jour la note uniquement si l'UA est évaluée dans ce QCM (barème > 0)
                if ($resultat['bareme'] > 0) {
                    if ($isValideOrSoumis) {
                        $dataToUpdate = [
                            'note_qcm' => $resultat['note'],
                            'barem_qcm' => $resultat['bareme']
                        ];
                        
                        if ($affectationQcmProjet->saise_automatique_note_qcm) {
                            $baremePrototype = $rup->bareme ?? 20;
                            $noteAdaptee = ($resultat['note'] / $resultat['bareme']) * $baremePrototype;
                            $dataToUpdate['note'] = $noteAdaptee;
                        }
                    } else {
                        // Le QCM est réinitialisé ou non validé, on efface les notes
                        $dataToUpdate = [
                            'note_qcm' => null,
                            'barem_qcm' => null
                        ];
                        
                        if ($affectationQcmProjet->saise_automatique_note_qcm) {
                            $dataToUpdate['note'] = null;
                        }
                    }
                    
                    $realisationUaPrototypeService = app(\Modules\PkgApprentissage\Services\RealisationUaPrototypeService::class);
                    $realisationUaPrototypeService->update($rup->id, $dataToUpdate);
                    
                    // Déclencher le recalcul en cascade pour mettre à jour note_cache de RealisationUA
                    if ($rup->realisationUa) {
                        $realisationUaService = app(\Modules\PkgApprentissage\Services\RealisationUaService::class);
                        $realisationUaService->calculerProgression($rup->realisationUa);
                    }
                }
                
                if ($jobManager) {
                    $jobManager->tick();
                }
            }
        } // Fermeture du foreach($realisationUaPrototypes as $rup)
        
        if ($jobManager) {
            $jobManager->setLabel("Finalisation de l'évaluation");
            $jobManager->tick();
        }

        // La sauvegarde de note_obtenu a été retirée du Job ! 
        // Elle est maintenant calculée de manière synchrone dans la méthode update().
    }

    public function beforeUpdateRules(array &$data, $id)
    {
        // Vérifier si l'état passe à "VALIDE"
        $etatValide = EtatRealisationQcm::where('reference', 'VALIDE')->first();
        
        if ($etatValide && isset($data['etat_realisation_qcm_id']) && $data['etat_realisation_qcm_id'] == $etatValide->id) {
            $item = $this->find($id);
            if ($item && empty($item->date_validation)) {
                $data['date_validation'] = \Carbon\Carbon::now();
            }
        }
    }

    /**
     * Surcharge de la méthode update pour calculer la note globale du QCM 
     * de manière synchrone avant la sauvegarde en base.
     */
    public function update($id, array $data)
    {
        $item = $this->find($id);
        
        if ($item) {
            $etatValide = EtatRealisationQcm::where('reference', 'VALIDE')->first();
            $etatSoumis = EtatRealisationQcm::where('reference', 'SOUMIS')->first();
            
            // Si on passe à l'état SOUMIS ou VALIDE (ou si on l'est déjà et qu'on recalcule)
            $isValideOrSoumis = (($etatValide && isset($data['etat_realisation_qcm_id']) && $data['etat_realisation_qcm_id'] == $etatValide->id) || 
                                 ($etatSoumis && isset($data['etat_realisation_qcm_id']) && $data['etat_realisation_qcm_id'] == $etatSoumis->id) ||
                                 (!isset($data['etat_realisation_qcm_id']) && $etatValide && $item->etat_realisation_qcm_id == $etatValide->id) ||
                                 (!isset($data['etat_realisation_qcm_id']) && $etatSoumis && $item->etat_realisation_qcm_id == $etatSoumis->id));
            
            if ($isValideOrSoumis) {
                $noteTotaleQcm = 0;
                $qcm = $item->qcm;
                if ($qcm) {
                    foreach($qcm->questions as $question) {
                        $reponse = $item->reponseQcms()->where('question_id', $question->id)->first();
                        if ($reponse) {
                            $propositionsCorrectes = $question->propositionReponses()->where('is_correcte', true)->pluck('id')->toArray();
                            $propositionsChoisies = $reponse->propositionReponses()->pluck('id')->toArray();
                            
                            sort($propositionsCorrectes);
                            sort($propositionsChoisies);
                            
                            if (!empty($propositionsCorrectes) && $propositionsCorrectes == $propositionsChoisies) {
                                $noteTotaleQcm += $question->bareme;
                            }
                        }
                    }
                }
                $data['note_obtenu'] = $noteTotaleQcm;
            } else if (isset($data['etat_realisation_qcm_id'])) {
                // Si on réinitialise (A_FAIRE) ou on démarre (EN_COURS), la note est nulle
                $data['note_obtenu'] = null;
            }
        }

        return parent::update($id, $data);
    }


    /**
     * Job asynchrone déclenché automatiquement via RealisationQcmObserver.
     * Permet d'effectuer les calculs lourds en arrière-plan et de renvoyer la progression.
     */
    public function updatedObserverJob(int $id, string $token): void
    {
        $jobManager = new \Modules\Core\App\Manager\JobManager($token);
        $item = $this->find($id);

        if (!$item) {
            $jobManager->initProgress(1);
            $jobManager->finish();
            return;
        }

        $etatValide = EtatRealisationQcm::where('reference', 'VALIDE')->first();
        $etatSoumis = EtatRealisationQcm::where('reference', 'SOUMIS')->first();

        $isValideOrSoumis = (($etatValide && $item->etat_realisation_qcm_id == $etatValide->id) || 
                             ($etatSoumis && $item->etat_realisation_qcm_id == $etatSoumis->id));

        if ($isValideOrSoumis) {
            $this->evaluerUaPrototypes($item, $jobManager);
        } else {
            // Pas d'évaluation nécessaire (ex: QCM en cours ou initialisé)
            $jobManager->initProgress(1);
        }
        
        $jobManager->finish();
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

    /**
     * Vérifie si le QCM a déjà été soumis (Règle métier).
     * Lève une exception métier si c'est le cas.
     */
    public function verifierEtatSoumission($realisationQcm)
    {
        $etatSoumis = EtatRealisationQcm::where('reference', 'SOUMIS')->first();
        if (($etatSoumis && $realisationQcm->etat_realisation_qcm_id == $etatSoumis->id) || $realisationQcm->date_soumission) {
            throw new \Modules\Core\App\Exceptions\BlException("Ce QCM a déjà été soumis. Vous ne pouvez pas le modifier ou le repasser.");
        }
    }

    /**
     * Calcule le temps restant pour le QCM.
     */
    public function calculerTempsRestant($realisationQcm)
    {
        if (!$realisationQcm->qcm || !$realisationQcm->qcm->is_duree_limitee) {
            return null; // Temps illimité
        }

        if (empty($realisationQcm->date_debut)) {
            return ($realisationQcm->qcm->duree_minutes ?? 60) * 60;
        }
        $dureeMax = ($realisationQcm->qcm->duree_minutes ?? 60) * 60;
        $dateDebut = \Carbon\Carbon::parse($realisationQcm->date_debut);
        
        // now()->timestamp - $dateDebut->timestamp garantit un temps écoulé positif
        $tempsEcoule = max(0, now()->timestamp - $dateDebut->timestamp);
        
        return max(0, $dureeMax - $tempsEcoule);
    }

    /**
     * Démarre la réalisation du QCM.
     */
    public function demarrerQcm($realisationQcm)
    {
        if (empty($realisationQcm->date_debut)) {
            $dataToUpdate = ['date_debut' => now()];
            
            $etatEnCours = EtatRealisationQcm::where('reference', 'EN_COURS')->first();
            if ($etatEnCours) {
                $dataToUpdate['etat_realisation_qcm_id'] = $etatEnCours->id;
            }
            
            // Utilisation de withoutEvents pour forcer l'update SANS déclencher le RealisationQcmObserver
            // car le démarrage est appelé depuis une requête standard (sans AJAX) et on ne veut pas de Job ici.
            \Modules\PkgQcm\Models\RealisationQcm::withoutEvents(function () use ($realisationQcm, $dataToUpdate) {
                $realisationQcm->update($dataToUpdate);
            });
        }
    }

    public function soumettreQcm($realisationQcm)
    {
        $dataToUpdate = ['date_soumission' => now()];
        
        $etatSoumis = EtatRealisationQcm::where('reference', 'SOUMIS')->first();
        if ($etatSoumis) {
            $dataToUpdate['etat_realisation_qcm_id'] = $etatSoumis->id;
        }
        
        // Cet appel passe par la méthode update() surchargée ci-dessus
        // qui calculera `note_obtenu` de manière synchrone,
        // puis l'Observer déclenchera le job asynchrone pour les RealisationUaPrototype.
        $this->update($realisationQcm->id, $dataToUpdate);
    }

    /**
     * Sauvegarde les réponses de l'apprenant pour un QCM
     */
    public function sauvegarderReponses($realisationQcm, array $reponses)
    {
        $reponseQcmService = app(\Modules\PkgQcm\Services\ReponseQcmService::class);
        
        foreach ($reponses as $questionId => $propositionIds) {
            $reponseExistante = $realisationQcm->reponseQcms()->where('question_id', $questionId)->first();
            $propositionIdsArray = is_array($propositionIds) ? $propositionIds : [$propositionIds];
            
            if ($reponseExistante) {
                // update (Le service se chargera de synchroniser la relation via son Trait)
                $reponseQcmService->update($reponseExistante->id, [
                    'date_reponse' => now(),
                    'propositionReponses' => $propositionIdsArray
                ]);
            } else {
                // create (Le service se chargera d'attacher la relation)
                $reponseQcmService->create([
                    'realisation_qcm_id' => $realisationQcm->id,
                    'question_id' => $questionId,
                    'date_reponse' => now(),
                    'propositionReponses' => $propositionIdsArray
                ]);
            }
        }
    }
}
