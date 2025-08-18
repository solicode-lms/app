<?php

namespace Modules\PkgApprentissage\Services;

use Modules\PkgApprentissage\Models\EtatRealisationMicroCompetence;
use Modules\PkgApprentissage\Models\EtatRealisationUa;
use Modules\PkgApprentissage\Models\RealisationMicroCompetence;
use Modules\PkgApprentissage\Services\Base\BaseRealisationMicroCompetenceService;
use Modules\PkgCompetences\Services\UniteApprentissageService;

/**
 * Classe RealisationMicroCompetenceService pour gérer la persistance de l'entité RealisationMicroCompetence.
 */
class RealisationMicroCompetenceService extends BaseRealisationMicroCompetenceService
{
    

    public function create(array|object $data)
    {
        // Convertir en tableau si $data est un objet
        $data = (array) $data;

        // Vérifier si l'état est fourni, sinon assigner l'état par défaut
        if (empty($data['etat_realisation_micro_competence_id'])) {
            $data['etat_realisation_micro_competence_id'] = EtatRealisationMicroCompetence::where('code', 'TODO')->first()->id;
        }

        return parent::create($data);
    }


    public function afterCreateRules(RealisationMicroCompetence $realisationMicroCompetence): void
    {
        $realisationUAService = new RealisationUAService();
        $etat_realisation_ua_id = EtatRealisationUa::where('code', "TODO")->value('id');
        $uas = $realisationMicroCompetence->microCompetence->uniteApprentissages;

        foreach ($uas as $ua) {
            // Vérifier si la réalisation UA existe déjà
            $exists = $realisationUAService->model
                ->where('realisation_micro_competence_id', $realisationMicroCompetence->id)
                ->where('unite_apprentissage_id', $ua->id)
                ->exists();

            if (! $exists) {
                $realisationUAService->create([
                    'realisation_micro_competence_id' => $realisationMicroCompetence->id,
                    'unite_apprentissage_id' => $ua->id,
                    'etat_realisation_ua_id' => $etat_realisation_ua_id,
                ]);
            }
        }
    }

  
    /**
     * Récupère ou crée une réalisation de micro-compétence pour un apprenant.
     *
     * @param  int $apprenantId
     * @param  int $microCompetenceId
     * @return RealisationMicroCompetence
     */
    public function getOrCreateByApprenant(int $apprenantId, int $microCompetenceId): RealisationMicroCompetence
    {
        // 🔍 Recherche si la réalisation existe déjà
        $realisation = $this->model
            ->where('apprenant_id', $apprenantId)
            ->where('micro_competence_id', $microCompetenceId)
            ->first();

        if ($realisation) {
            return $realisation;
        }

        // 📌 Récupérer la micro-compétence et sa compétence parente
        $microCompetence = \Modules\PkgCompetences\Models\MicroCompetence::with('competence')
            ->findOrFail($microCompetenceId);

        // 🆕 Récupérer ou créer la réalisation de compétence associée
        $realisationCompetenceService = new RealisationCompetenceService();
        $realisationCompetence = $realisationCompetenceService->getOrCreateByApprenant(
            $apprenantId,
            $microCompetence->competence_id
        );

        // 🎯 État initial
        $etatRealisationId = EtatRealisationMicroCompetence::where('code', 'TODO')->first()->id;

        // 🏗️ Création avec lien vers realisation_competence_id
        return $this->create([
            'apprenant_id' => $apprenantId,
            'micro_competence_id' => $microCompetenceId,
            'realisation_competence_id' => $realisationCompetence->id, // ✅ Non nullable
            'etat_realisation_micro_competence_id' => $etatRealisationId,
            'date_debut' => now(),
        ]);
    }

    public function afterUpdateRules(RealisationMicroCompetence $rmc): void
    {
        $this->calculerProgression($rmc);
    }
    public function calculerProgression(RealisationMicroCompetence $rmc): void
    {
        $rmc->load('realisationUas');

        $uas = $rmc->realisationUas;
        $totalUa = $uas->count();

        if ($totalUa === 0) {
            $rmc->progression_cache = 0;
            $rmc->note_cache = 0;
            $rmc->bareme_cache = 0;
            $rmc->save();
            return;
        }

        $totalNote = $uas->sum(fn($ua) => $ua->note_cache ?? 0);
        $totalBareme = $uas->sum(fn($ua) => $ua->bareme_cache ?? 0);
        $totalProgression = $uas->sum(fn($ua) => $ua->progression_cache ?? 0);

        $rmc->progression_cache = round($totalProgression / $totalUa, 1);
        $rmc->note_cache = round($totalNote, 2);
        $rmc->bareme_cache = round($totalBareme, 2);


        // Calcul de l’état global de la micro-compétence
        $nouvelEtatCode = $this->calculerEtatDepuisUas($rmc);
        if ($nouvelEtatCode) {
            $nouvelEtat = EtatRealisationMicroCompetence::where('code', $nouvelEtatCode)->first();
            if ($nouvelEtat && $rmc->etat_realisation_micro_competence_id !== $nouvelEtat->id) {
                $rmc->etat_realisation_micro_competence_id = $nouvelEtat->id;
            }
        }

        $rmc->saveQuietly();

        // 🔹 Calcul progression RealisationCompetence
        if ($rmc->microCompetence && $rmc->microCompetence->competence) {
            $realisationCompetenceService = new RealisationCompetenceService();
            $realisationCompetence = $realisationCompetenceService->getOrCreateByApprenant(
                $rmc->apprenant_id,
                $rmc->microCompetence->competence_id
            );
            $realisationCompetenceService->calculerProgression($realisationCompetence);
        }



    }

    /**
     * Calcule l’état global d’une micro-compétence selon l’avancement de ses UAs.
     *
     * Progression stricte :
     * - Si au moins une UA a un chapitre non terminé → IN_PROGRESS_CHAPITRE
     * - Sinon, si au moins une UA a un prototype non terminé → IN_PROGRESS_PROTOTYPE
     * - Sinon, si au moins une UA a un projet non terminé → IN_PROGRESS_PROJET
     * - Sinon (tout est terminé) → DONE
     *
     * @param RealisationMicroCompetence $rmc
     * @return string|null
     */
    public function calculerEtatDepuisUas(RealisationMicroCompetence $rmc): ?string
    {
        $uas = $rmc->realisationUas()->with('etatRealisationUa')->get();

        if ($uas->isEmpty()) {
            return 'TODO';
        }

        $codes = $uas->pluck('etatRealisationUa.code')->filter()->values();

        // Cas 1 : toutes en TODO → TODO
        if ($codes->every(fn($c) => $c === 'TODO')) {
            return 'TODO';
        }

        // Cas 2 : toutes en DONE → DONE
        if ($codes->every(fn($c) => $c === 'DONE')) {
            return 'DONE';
        }

        // Cas 3 : priorité des états "en cours"
        $priorites = [
            'IN_PROGRESS_CHAPITRE',
            'IN_PROGRESS_PROTOTYPE',
            'IN_PROGRESS_PROJET',
        ];

        foreach ($priorites as $etat) {
            if ($codes->contains($etat)) {
                return $etat;
            }
        }

        // Cas 4 : fallback si aucun état trouvé
        return 'IN_PROGRESS_CHAPITRE';
    }



}
