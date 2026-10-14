<?php

namespace Modules\PkgTableauBord\Services\Traits;

use Modules\PkgApprenants\Models\Groupe;
use Modules\PkgRealisationProjets\Models\AffectationProjet;
use Modules\PkgRealisationProjets\Models\RealisationProjet;
use Modules\PkgRealisationProjets\Services\RealisationProjetService;

trait DashboardIndicateur02Trait
{
    /**
     * Récupère les données de l'Indicateur 02 pour le tableau de bord d'un Apprenant.
     *
     * @param int|RealisationProjet $realisationProjet
     * @return array
     */
    public function getIndicateurGatingApprenant($realisationProjet): array
    {
        if (is_numeric($realisationProjet)) {
            $realisationProjet = RealisationProjet::with(['affectationProjet', 'apprenant'])->find($realisationProjet);
        }

        if (!$realisationProjet) {
            return $this->getDefaultIndicateurGatingApprenant();
        }

        $premierEssai = is_null($realisationProjet->cache_est_valide_prototype_premier_essai)
            ? null
            : (bool) $realisationProjet->cache_est_valide_prototype_premier_essai;
        $tentatives = (int) ($realisationProjet->cache_nombre_tentatives_prototype ?? 0);
        $dateValidation = $realisationProjet->cache_date_validation_prototype;

        // Détermination du statut
        if (is_null($premierEssai) && $tentatives === 0) {
            $statut = 'Non évalué';
            $statutBadge = 'bg-slate-50 text-slate-700 border border-slate-200';
            $accesProjet = 'En attente de soutenance';
            $accesBadge = 'bg-slate-100 text-slate-600';
            $isDebloque = false;
        } elseif ($premierEssai === true) {
            $statut = 'Validé (1er essai)';
            $statutBadge = 'bg-emerald-50 text-emerald-700 border border-emerald-200';
            $accesProjet = 'Débloqué (Niveau 3)';
            $accesBadge = 'text-emerald-700 font-bold';
            $isDebloque = true;
        } elseif ($premierEssai === false && !is_null($dateValidation)) {
            $statut = 'Validé (2ème essai)';
            $statutBadge = 'bg-sky-50 text-sky-700 border border-sky-200';
            $accesProjet = 'Débloqué après remédiation';
            $accesBadge = 'text-sky-700 font-bold';
            $isDebloque = true;
        } else {
            $statut = 'En remédiation';
            $statutBadge = 'bg-amber-50 text-amber-700 border border-amber-200';
            $accesProjet = 'Verrouillé (Atelier requis)';
            $accesBadge = 'text-amber-700 font-bold';
            $isDebloque = false;
        }

        return [
            'statut' => $statut,
            'statut_badge' => $statutBadge,
            'acces_projet' => $accesProjet,
            'acces_badge' => $accesBadge,
            'is_debloque' => $isDebloque,
            'tentatives' => $tentatives,
            'est_valide_premier_essai' => $premierEssai,
            'date_validation' => $dateValidation ? \Carbon\Carbon::parse($dateValidation)->format('d/m/Y H:i') : null,
            'description' => 'Validation autonome des fondamentaux lors de l\'épreuve de Live Coding Niveau 2.',
        ];
    }

    /**
     * Récupère les données de l'Indicateur 02 pour le tableau de bord du Formateur.
     *
     * @param int|AffectationProjet $affectationProjet
     * @return array
     */
    public function getIndicateurGatingFormateur($affectationProjet): array
    {
        if (is_numeric($affectationProjet)) {
            $affectationProjet = AffectationProjet::with([
                'realisationProjets.apprenant.user',
                'groupe'
            ])->find($affectationProjet);
        }

        if (!$affectationProjet) {
            return $this->getDefaultIndicateurGatingFormateur();
        }

        $realisations = $affectationProjet->realisationProjets;
        $totalApprenants = $realisations->count();

        $valides1erEssai = 0;
        $evalues = 0;
        $apprenantsEnRemediation = [];

        foreach ($realisations as $rp) {
            $premierEssai = is_null($rp->cache_est_valide_prototype_premier_essai)
                ? null
                : (bool) $rp->cache_est_valide_prototype_premier_essai;
            $tentatives = (int) ($rp->cache_nombre_tentatives_prototype ?? 0);

            if (!is_null($premierEssai) || $tentatives > 0) {
                $evalues++;
                if ($premierEssai === true) {
                    $valides1erEssai++;
                } elseif ($premierEssai === false && is_null($rp->cache_date_validation_prototype)) {
                    $apprenantsEnRemediation[] = [
                        'id' => $rp->apprenant_id,
                        'nom_complet' => ($rp->apprenant->prenom ?? '') . ' ' . ($rp->apprenant->nom ?? 'Stagiaire'),
                        'avatar' => $rp->apprenant->profile_image ?? null,
                        'tentatives' => $tentatives,
                    ];
                }
            }
        }

        $tauxGating = $evalues > 0 ? round(($valides1erEssai / $evalues) * 100, 1) : 0;
        $enRemediation = count($apprenantsEnRemediation);

        // Seuils didactiques
        if ($evalues === 0) {
            $statut = 'En attente de soutenances';
            $statutBadge = 'bg-slate-50 text-slate-700 border border-slate-200';
        } elseif ($tauxGating >= 80) {
            $statut = 'Excellence (>= 80%)';
            $statutBadge = 'bg-emerald-50 text-emerald-700 border border-emerald-200';
        } elseif ($tauxGating >= 65) {
            $statut = 'Vigilance (65-80%)';
            $statutBadge = 'bg-amber-50 text-amber-700 border border-amber-200';
        } else {
            $statut = 'Alerte (< 65%)';
            $statutBadge = 'bg-rose-50 text-rose-700 border border-rose-200';
        }

        return [
            'taux_gating' => $tauxGating,
            'taux_gating_formate' => "{$tauxGating}%",
            'total_apprenants' => $totalApprenants,
            'total_evalues' => $evalues,
            'valides_premier_essai' => $valides1erEssai,
            'en_remediation' => $enRemediation,
            'ratio_texte' => "{$valides1erEssai} / {$evalues} validés au 1er passage ({$enRemediation} en remédiation)",
            'statut' => $statut,
            'statut_badge' => $statutBadge,
            'apprenants_en_remediation' => $apprenantsEnRemediation,
            'description' => 'Taux d\'assimilation autonome des prérequis techniques dès la première tentative.',
        ];
    }

    /**
     * Récupère les données de l'Indicateur 02 pour le tableau de bord du Responsable.
     *
     * @param array $groupeIds
     * @param int|null $projetId
     * @return array
     */
    public function getIndicateurGatingResponsable(array $groupeIds = [], ?int $projetId = null): array
    {
        $rpQuery = RealisationProjet::whereHas('affectationProjet.projet', function ($q) {
            $q->sessionsFormation();
        });
        if (!empty($groupeIds)) {
            $rpQuery->whereHas('affectationProjet', function ($q) use ($groupeIds) {
                $q->whereIn('groupe_id', $groupeIds);
            });
        }
        if (!empty($projetId)) {
            $rpQuery->whereHas('affectationProjet', function ($q) use ($projetId) {
                $q->where('projet_id', $projetId);
            });
        }
        $totalGlobalEvalues = (clone $rpQuery)->whereNotNull('cache_est_valide_prototype_premier_essai')->count();
        $totalGlobalValides1er = (clone $rpQuery)->where('cache_est_valide_prototype_premier_essai', true)->count();
        $tauxGlobal = $totalGlobalEvalues > 0 ? round(($totalGlobalValides1er / $totalGlobalEvalues) * 100, 1) : null;
        $tauxGlobalFormate = !is_null($tauxGlobal) ? "{$tauxGlobal}%" : '--';

        $query = AffectationProjet::with(['groupe', 'realisationProjets', 'projet'])
            ->whereHas('projet', function ($q) {
                $q->sessionsFormation();
            });
        if (!empty($groupeIds)) {
            $query->whereIn('groupe_id', $groupeIds);
        }
        if (!empty($projetId)) {
            $query->where('projet_id', $projetId);
        }
        $affectations = $query->latest('date_debut')->take(10)->get();

        $benchmark = [];

        foreach ($affectations as $aff) {
            $evaluesGroupe = 0;
            $valides1erGroupe = 0;

            foreach ($aff->realisationProjets as $rp) {
                $premierEssai = is_null($rp->cache_est_valide_prototype_premier_essai)
                    ? null
                    : (bool) $rp->cache_est_valide_prototype_premier_essai;
                $tentatives = (int) ($rp->cache_nombre_tentatives_prototype ?? 0);

                if (!is_null($premierEssai) || $tentatives > 0) {
                    $evaluesGroupe++;
                    if ($premierEssai === true) {
                        $valides1erGroupe++;
                    }
                }
            }

            $tauxGroupe = $evaluesGroupe > 0 ? round(($valides1erGroupe / $evaluesGroupe) * 100, 1) : null;

            $benchmark[] = [
                'affectation_id' => $aff->id,
                'groupe_code' => $aff->groupe->code ?? 'Groupe',
                'groupe_nom' => $aff->groupe->nom ?? ($aff->groupe->code ?? 'Promotion'),
                'taux_gating' => $tauxGroupe,
                'taux_gating_formate' => !is_null($tauxGroupe) ? "{$tauxGroupe}%" : '--',
                'valides_premier_essai' => $valides1erGroupe,
                'total_evalues' => $evaluesGroupe,
                'statut' => (!is_null($tauxGroupe) && $tauxGroupe >= 75) ? 'Conforme' : 'Vigilance',
            ];
        }

        return [
            'taux_gating_global' => $tauxGlobal,
            'taux_gating_global_formate' => $tauxGlobalFormate,
            'total_evalues_global' => $totalGlobalEvalues,
            'valides_premier_essai_global' => $totalGlobalValides1er,
            'benchmark' => $benchmark,
        ];
    }

    /**
     * Données par défaut apprenant.
     */
    protected function getDefaultIndicateurGatingApprenant(): array
    {
        return [
            'statut' => 'Non évalué',
            'statut_badge' => 'bg-slate-50 text-slate-700 border border-slate-200',
            'acces_projet' => 'En attente de soutenance',
            'acces_badge' => 'bg-slate-100 text-slate-600',
            'is_debloque' => false,
            'tentatives' => 0,
            'est_valide_premier_essai' => null,
            'date_validation' => null,
            'description' => 'Validation autonome des fondamentaux lors de l\'épreuve de Live Coding Niveau 2.',
        ];
    }

    /**
     * Données par défaut formateur.
     */
    protected function getDefaultIndicateurGatingFormateur(): array
    {
        return [
            'taux_gating' => 0,
            'taux_gating_formate' => '0%',
            'total_apprenants' => 0,
            'total_evalues' => 0,
            'valides_premier_essai' => 0,
            'en_remediation' => 0,
            'ratio_texte' => '0 / 0 évalués',
            'statut' => 'En attente',
            'statut_badge' => 'bg-slate-50 text-slate-700 border border-slate-200',
            'apprenants_en_remediation' => [],
            'description' => 'Taux d\'assimilation autonome des prérequis techniques dès la première tentative.',
        ];
    }

    /**
     * Analyse granulaire détaillée pour l'Indicateur 02 : Gating Prototype & Validation 1er essai.
     *
     * @param array $filters
     * @return array
     */
    public function getIndicateurGatingDetail(array $filters = []): array
    {
        $parametreService = app(\Modules\PkgTableauBord\Services\ParametrePedagogiqueService::class);
        $seuilExcellence = $parametreService->getSeuilGatingExcellencePourcentage($filters['projet_id'] ?? null);
        $seuilVigilance = $parametreService->getSeuilGatingVigilancePourcentage($filters['projet_id'] ?? null);

        $query = AffectationProjet::with([
            'groupe.filiere',
            'projet.formateur',
            'realisationProjets.apprenant.user',
            'realisationProjets.realisationTaches.tache.phaseEvaluation',
            'realisationProjets.realisationTaches.etatRealisationTache.workflowTache',
            'realisationProjets.realisationTaches.liveCodings'
        ]);

        if (!empty($filters['projet_id'])) {
            $query->where('projet_id', $filters['projet_id']);
        } elseif (($filters['type_projet'] ?? 'sessions') === 'sessions') {
            $query->whereHas('projet', fn($q) => $q->sessionsFormation());
        }

        if (!empty($filters['groupe_id'])) {
            $query->where('groupe_id', $filters['groupe_id']);
        } elseif (!empty($filters['groupe_ids'])) {
            $query->whereIn('groupe_id', $filters['groupe_ids']);
        } elseif (!empty($filters['filiere_id'])) {
            $query->whereHas('groupe', fn($q) => $q->where('filiere_id', $filters['filiere_id']));
        }

        if (!empty($filters['formateur_id'])) {
            $query->whereHas('projet', fn($p) => $p->where('formateur_id', $filters['formateur_id']));
        }

        $isVisionGlobale = empty($filters['groupe_id']) 
            && empty($filters['groupe_ids']) 
            && empty($filters['projet_id']) 
            && empty($filters['formateur_id']) 
            && empty($filters['filiere_id']);

        if ($isVisionGlobale) {
            $query->take(10);
        }

        $affectations = $query->latest('date_debut')->get();

        $apprenants = [];
        $totalApprenants = 0;
        $totalEvalues = 0;
        $valides1erEssai = 0;
        $validesApresRemediation = 0;
        $enRemediation = 0;
        $nonEvalues = 0;

        $benchmarkSessions = [];

        foreach ($affectations as $aff) {
            $totalSession = 0;
            $evaluesSession = 0;
            $valides1erSession = 0;
            $validesRemediationSession = 0;
            $remediationSession = 0;

            foreach ($aff->realisationProjets as $rp) {
                $totalApprenants++;
                $totalSession++;

                $apprenant = $rp->apprenant;
                $premierEssai = is_null($rp->cache_est_valide_prototype_premier_essai)
                    ? null
                    : (bool) $rp->cache_est_valide_prototype_premier_essai;
                $tentatives = (int) ($rp->cache_nombre_tentatives_prototype ?? 0);
                $dateValidation = $rp->cache_date_validation_prototype;

                // Tâche Prototype N2
                $prototypeTache = $rp->realisationTaches->first(function ($rt) {
                    return optional(optional($rt->tache)->phaseEvaluation)->code === 'N2';
                });
                $nomPrototype = $prototypeTache?->tache?->titre ?? 'Prototype Live Coding N2';

                // Classification didactique
                if ($premierEssai === true) {
                    $valides1erEssai++;
                    $valides1erSession++;
                    $evaluesSession++;
                    $totalEvalues++;
                    $statutCode = 'valide_1er';
                    $statutLabel = 'Validé (1er essai)';
                    $statutBadge = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                    $accesProjet = 'Débloqué (Niveau 3)';
                    $accesBadge = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                    $isDebloque = true;
                } elseif ($premierEssai === false && !is_null($dateValidation)) {
                    $validesApresRemediation++;
                    $validesRemediationSession++;
                    $evaluesSession++;
                    $totalEvalues++;
                    $statutCode = 'valide_remediation';
                    $statutLabel = 'Validé (2e essai)';
                    $statutBadge = 'bg-sky-50 text-sky-700 border-sky-200';
                    $accesProjet = 'Débloqué (Rattrapage)';
                    $accesBadge = 'bg-sky-50 text-sky-700 border-sky-200';
                    $isDebloque = true;
                } elseif ($premierEssai === false && is_null($dateValidation)) {
                    $enRemediation++;
                    $remediationSession++;
                    $evaluesSession++;
                    $totalEvalues++;
                    $statutCode = 'en_remediation';
                    $statutLabel = 'En remédiation';
                    $statutBadge = 'bg-amber-50 text-amber-700 border-amber-200';
                    $accesProjet = 'Verrouillé (Atelier requis)';
                    $accesBadge = 'bg-amber-50 text-amber-700 border-amber-200';
                    $isDebloque = false;
                } else {
                    $nonEvalues++;
                    $statutCode = 'non_evalue';
                    $statutLabel = 'Non évalué';
                    $statutBadge = 'bg-slate-50 text-slate-700 border-slate-200';
                    $accesProjet = 'En attente soutenance';
                    $accesBadge = 'bg-slate-50 text-slate-600 border-slate-200';
                    $isDebloque = false;
                }

                $liveCodingsList = $prototypeTache ? $prototypeTache->liveCodings->map(function ($lc) {
                    return [
                        'id' => $lc->id,
                        'tentative' => $lc->tentative,
                        'est_valide' => (bool) $lc->est_valide,
                        'date_passage' => $lc->date_passage ? \Carbon\Carbon::parse($lc->date_passage)->format('d/m/Y H:i') : '--',
                        'commentaire' => $lc->commentaire,
                    ];
                })->values()->all() : [];

                $apprenants[] = [
                    'id' => $apprenant?->id,
                    'nom_complet' => ($apprenant?->prenom ?? '') . ' ' . ($apprenant?->nom ?? 'Stagiaire'),
                    'prenom' => $apprenant?->prenom ?? '',
                    'nom' => $apprenant?->nom ?? 'Stagiaire',
                    'matricule' => $apprenant?->matricule ?? null,
                    'avatar' => $apprenant?->profile_image ?? null,
                    'groupe_code' => $aff->groupe?->code ?? ($aff->groupe?->nom ?? 'Promo'),
                    'prototype_titre' => $nomPrototype,
                    'tentatives' => $tentatives,
                    'premier_essai' => $premierEssai,
                    'date_validation' => $dateValidation ? \Carbon\Carbon::parse($dateValidation)->format('d/m/Y H:i') : null,
                    'statut_code' => $statutCode,
                    'statut_label' => $statutLabel,
                    'statut_badge' => $statutBadge,
                    'acces_projet' => $accesProjet,
                    'acces_badge' => $accesBadge,
                    'is_debloque' => $isDebloque,
                    'realisation_projet_id' => $rp->id,
                    'realisation_tache_id' => $prototypeTache?->id,
                    'live_codings' => $liveCodingsList,
                ];
            }

            $tauxSession = $evaluesSession > 0 ? round(($valides1erSession / $evaluesSession) * 100, 1) : null;
            if (is_null($tauxSession)) {
                $statutSession = 'En attente';
                $statutSessionBadge = 'bg-slate-50 text-slate-700 border-slate-200';
            } elseif ($tauxSession >= $seuilExcellence) {
                $statutSession = 'Excellence';
                $statutSessionBadge = 'bg-emerald-50 text-emerald-700 border-emerald-200';
            } elseif ($tauxSession >= $seuilVigilance) {
                $statutSession = 'Vigilance';
                $statutSessionBadge = 'bg-amber-50 text-amber-700 border-amber-200';
            } else {
                $statutSession = 'Alerte';
                $statutSessionBadge = 'bg-rose-50 text-rose-700 border-rose-200';
            }

            $benchmarkSessions[] = [
                'affectation_id' => $aff->id,
                'session_titre' => $aff->projet?->titre ?? 'Session',
                'date_debut' => $aff->date_debut ? \Carbon\Carbon::parse($aff->date_debut)->format('d/m/Y') : null,
                'groupe_id' => $aff->groupe_id,
                'groupe_code' => $aff->groupe?->code ?? 'Groupe',
                'filiere_code' => $aff->groupe?->filiere?->code ?? ($aff->groupe?->filiere?->nom ?? '--'),
                'total_apprenants' => $totalSession,
                'total_evalues' => $evaluesSession,
                'valides_premier_essai' => $valides1erSession,
                'valides_apres_remediation' => $validesRemediationSession,
                'en_remediation' => $remediationSession,
                'taux_gating' => $tauxSession,
                'taux_gating_formate' => !is_null($tauxSession) ? "{$tauxSession}%" : '--',
                'statut' => $statutSession,
                'statut_badge' => $statutSessionBadge,
            ];
        }

        $tauxGatingGlobal = $totalEvalues > 0 ? round(($valides1erEssai / $totalEvalues) * 100, 1) : null;
        $tauxGatingFormate = !is_null($tauxGatingGlobal) ? "{$tauxGatingGlobal}%" : '--';

        if (is_null($tauxGatingGlobal)) {
            $statutGlobal = 'En attente';
            $statutGlobalBadge = 'bg-slate-50 text-slate-700 border-slate-200';
        } elseif ($tauxGatingGlobal >= $seuilExcellence) {
            $statutGlobal = "Excellence (>= {$seuilExcellence}%)";
            $statutGlobalBadge = 'bg-emerald-50 text-emerald-700 border-emerald-200';
        } elseif ($tauxGatingGlobal >= $seuilVigilance) {
            $statutGlobal = "Vigilance ({$seuilVigilance}-{$seuilExcellence}%)";
            $statutGlobalBadge = 'bg-amber-50 text-amber-700 border-amber-200';
        } else {
            $statutGlobal = "Alerte (< {$seuilVigilance}%)";
            $statutGlobalBadge = 'bg-rose-50 text-rose-700 border-rose-200';
        }

        $tauxEvaluation = $totalApprenants > 0 ? round(($totalEvalues / $totalApprenants) * 100, 1) : 0;
        $tauxSuccesFinal = $totalEvalues > 0 ? round((($valides1erEssai + $validesApresRemediation) / $totalEvalues) * 100, 1) : 0;

        return [
            'total_apprenants' => $totalApprenants,
            'total_evalues' => $totalEvalues,
            'valides_premier_essai' => $valides1erEssai,
            'valides_apres_remediation' => $validesApresRemediation,
            'en_remediation' => $enRemediation,
            'non_evalues' => $nonEvalues,
            'taux_gating' => $tauxGatingGlobal,
            'taux_gating_formate' => $tauxGatingFormate,
            'taux_evaluation' => $tauxEvaluation,
            'taux_succes_final' => $tauxSuccesFinal,
            'seuil_excellence' => $seuilExcellence,
            'seuil_vigilance' => $seuilVigilance,
            'statut' => $statutGlobal,
            'statut_badge' => $statutGlobalBadge,
            'tranches' => [
                'valide_1er' => $valides1erEssai,
                'valide_remediation' => $validesApresRemediation,
                'en_remediation' => $enRemediation,
                'non_evalue' => $nonEvalues,
            ],
            'benchmark' => $benchmarkSessions,
            'apprenants' => $apprenants,
            'formule_calcul' => [
                'formule' => 'R_gating = (N_validés_1er_essai / N_évalués) * 100',
                'seuil' => ">= {$seuilExcellence}%",
                'interpretation' => 'Mesure la capacité des apprenants à assimiler et démontrer les prérequis techniques dès la première soutenance surveillée (Live Coding Niveau 2).'
            ]
        ];
    }
}
