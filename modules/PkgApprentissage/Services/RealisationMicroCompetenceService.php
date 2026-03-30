<?php

namespace Modules\PkgApprentissage\Services;

use Modules\PkgApprentissage\Models\EtatRealisationMicroCompetence;
use Modules\PkgApprentissage\Models\EtatRealisationUa;
use Modules\PkgApprentissage\Models\RealisationMicroCompetence;
use Modules\PkgApprentissage\Services\Base\BaseRealisationMicroCompetenceService;
use Modules\PkgCompetences\Services\UniteApprentissageService;
use Modules\PkgApprentissage\Services\RealisationCompetenceService;
use Modules\PkgCompetences\Models\MicroCompetence;

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
            $rmc->bareme_non_evalue_cache = 0;
            $rmc->progression_ideal_cache = 0;
            $rmc->pourcentage_non_valide_cache = 0;
            $rmc->taux_rythme_cache = null;
            $rmc->save();
            return;
        }

        // ✅ Agrégats sur les UAs
        $totalNote = $uas->sum(fn($ua) => $ua->note_cache ?? 0);
        $totalBareme = $uas->sum(fn($ua) => $ua->bareme_cache ?? 0);
        $totalBaremeNonEvalue = $uas->sum(fn($ua) => $ua->bareme_non_evalue_cache ?? 0);
        $totalProgression = $uas->sum(fn($ua) => $ua->progression_cache ?? 0);
        $totalProgressionIdeal = $uas->sum(fn($ua) => $ua->progression_ideal_cache ?? 0);
        $totalPourcentageNonValide = $uas->sum(fn($ua) => $ua->pourcentage_non_valide_cache ?? 0);

        // ✅ Progressions
        $rmc->progression_cache = round($totalProgression / $totalUa, 1);
        $rmc->progression_ideal_cache = round($totalProgressionIdeal / $totalUa, 1);
        $rmc->pourcentage_non_valide_cache = round($totalPourcentageNonValide / $totalUa, 1);

        // ✅ Notes & barèmes
        $rmc->note_cache = round($totalNote, 2);
        $rmc->bareme_cache = round($totalBareme, 2);
        $rmc->bareme_non_evalue_cache = round($totalBaremeNonEvalue, 2);

        // ✅ Taux de rythme
        $rmc->taux_rythme_cache = $rmc->progression_ideal_cache > 0
            ? round(($rmc->progression_cache / $rmc->progression_ideal_cache) * 100, 1)
            : null;

        // ✅ Calcul de l’état global de la micro-compétence
        $nouvelEtatCode = $this->calculerEtatDepuisUas($rmc);
        if ($nouvelEtatCode) {
            $nouvelEtat = EtatRealisationMicroCompetence::where('code', $nouvelEtatCode)->first();
            if ($nouvelEtat && $rmc->etat_realisation_micro_competence_id !== $nouvelEtat->id) {
                $rmc->etat_realisation_micro_competence_id = $nouvelEtat->id;
            }
        }

        $rmc->dernier_update = now();
        $rmc->saveQuietly();

        // 🔹 Calcul progression RealisationCompetence
        $realisationCompetenceService = new RealisationCompetenceService();
        $realisationCompetenceService->calculerProgression($rmc->realisationCompetence);
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




    /**
     * Récupère ou crée la réalisation d'une micro-compétence pour un apprenant donné.
     *
     * @param  int $apprenantId
     * @param  int $microCompetenceId
     * @return RealisationMicroCompetence
     */
    public function getOrCreateApprenant(int $apprenantId, int $microCompetenceId): RealisationMicroCompetence
    {
        // 1️⃣ Chercher une RMC existante
        $rmc = $this->model
            ->where('micro_competence_id', $microCompetenceId)
            ->where('apprenant_id', $apprenantId)
            ->first();

        if ($rmc) {
            return $rmc;
        }

        // 2️⃣ Charger la micro-compétence et sa compétence parente
        $microCompetence = MicroCompetence::with('competence')
            ->findOrFail($microCompetenceId);

        $competence = $microCompetence->competence;

        if (! $competence?->id) {
            throw new \RuntimeException("Impossible de déterminer la compétence liée à la micro-compétence #$microCompetenceId");
        }

        // 3️⃣ S'assurer que la RealisationCompetence existe
        $realisationCompetenceService = new RealisationCompetenceService();
        $realisationCompetence = $realisationCompetenceService
            ->getOrCreateApprenant($apprenantId, $competence->id);

        // 4️⃣ Créer la RealisationMicroCompetence
        //     (l'état par défaut TODO est géré dans create())
        return $this->create([
            'realisation_competence_id' => $realisationCompetence->id,
            'micro_competence_id'       => $microCompetenceId,
            'apprenant_id'              => $apprenantId,
        ]);
    }

}
