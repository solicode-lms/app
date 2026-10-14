<?php

namespace Modules\PkgTableauBord\Services\Traits;

use Modules\PkgRealisationProjets\Models\AffectationProjet;
use Modules\PkgRealisationProjets\Models\RealisationProjet;
use Modules\PkgRealisationTache\Models\RealisationTache;

trait DashboardIndicateur03Trait
{
    /**
     * Récupère les données de l'Indicateur 03 pour le tableau de bord d'un Apprenant.
     *
     * @param int|RealisationProjet $realisationProjet
     * @return array
     */
    public function getIndicateurRevisionApprenant($realisationProjet): array
    {
        if (is_numeric($realisationProjet)) {
            $realisationProjet = RealisationProjet::with(['realisationTaches.tache'])->find($realisationProjet);
        }

        if (!$realisationProjet) {
            return $this->getDefaultIndicateurRevisionApprenant();
        }

        $realisations = $realisationProjet->realisationTaches;
        $totalTaches = $realisations->count();

        if ($totalTaches === 0) {
            return $this->getDefaultIndicateurRevisionApprenant();
        }

        $totalRejets = 0;
        $sansRevision = 0;

        foreach ($realisations as $rt) {
            $nb = (int) ($rt->cache_nombre_revisions ?? 0);
            $totalRejets += $nb;
            if ($nb === 0) {
                $sansRevision++;
            }
        }

        $frequence = round($totalRejets / $totalTaches, 1);
        $tauxConforme = round(($sansRevision / $totalTaches) * 100, 1);

        // Seuils didactiques
        if ($frequence < 0.5) {
            $statut = 'Qualité initiale élevée';
            $statutBadge = 'bg-emerald-50 text-emerald-700 border border-emerald-200';
        } elseif ($frequence <= 1.2) {
            $statut = 'Ajustements en cours';
            $statutBadge = 'bg-amber-50 text-amber-700 border border-amber-200';
        } else {
            $statut = 'Réalignement requis';
            $statutBadge = 'bg-rose-50 text-rose-700 border border-rose-200';
        }

        return [
            'frequence' => $frequence,
            'frequence_formatee' => "{$frequence} reprise / tâche",
            'total_rejets' => $totalRejets,
            'total_taches' => $totalTaches,
            'taches_sans_reprise' => $sansRevision,
            'taux_premier_jet' => $tauxConforme,
            'taux_premier_jet_formate' => "{$tauxConforme}% de réussite directe",
            'statut' => $statut,
            'statut_badge' => $statutBadge,
            'description' => 'Mesure de la stabilité du code et de l\'autonomie dès le premier rendu soumis.',
        ];
    }

    /**
     * Récupère les données de l'Indicateur 03 pour le tableau de bord du Formateur.
     *
     * @param int|AffectationProjet $affectationProjet
     * @return array
     */
    public function getIndicateurRevisionFormateur($affectationProjet): array
    {
        if (is_numeric($affectationProjet)) {
            $affectationProjet = AffectationProjet::with([
                'realisationProjets.realisationTaches.tache',
                'groupe'
            ])->find($affectationProjet);
        }

        if (!$affectationProjet) {
            return $this->getDefaultIndicateurRevisionFormateur();
        }

        $toutesLesRealisations = [];
        $tachesGrouped = [];

        foreach ($affectationProjet->realisationProjets as $rp) {
            foreach ($rp->realisationTaches as $rt) {
                $toutesLesRealisations[] = $rt;

                $tacheId = $rt->tache_id;
                if (!isset($tachesGrouped[$tacheId])) {
                    $tachesGrouped[$tacheId] = [
                        'tache_id' => $tacheId,
                        'titre' => $rt->tache->titre ?? "Tâche #{$tacheId}",
                        'revisions_totales' => 0,
                        'apprenants_avec_reprise' => 0,
                        'total_evaluations' => 0,
                    ];
                }

                $revisions = (int) ($rt->cache_nombre_revisions ?? 0);
                $tachesGrouped[$tacheId]['revisions_totales'] += $revisions;
                $tachesGrouped[$tacheId]['total_evaluations']++;
                if ($revisions > 0) {
                    $tachesGrouped[$tacheId]['apprenants_avec_reprise']++;
                }
            }
        }

        $totalTaches = count($toutesLesRealisations);
        if ($totalTaches === 0) {
            return $this->getDefaultIndicateurRevisionFormateur();
        }

        $totalRejets = 0;
        $sansRevision = 0;

        foreach ($toutesLesRealisations as $rt) {
            $nb = (int) ($rt->cache_nombre_revisions ?? 0);
            $totalRejets += $nb;
            if ($nb === 0) {
                $sansRevision++;
            }
        }

        $frequence = round($totalRejets / $totalTaches, 1);
        $tauxConforme = round(($sansRevision / $totalTaches) * 100, 1);

        // Tri du Top 3 des tâches nécessitant un réalignement
        usort($tachesGrouped, function ($a, $b) {
            return $b['revisions_totales'] <=> $a['revisions_totales'];
        });

        $top3Taches = [];
        foreach (array_slice($tachesGrouped, 0, 3) as $item) {
            $moyen = $item['total_evaluations'] > 0 
                ? round($item['revisions_totales'] / $item['total_evaluations'], 1) 
                : 0.0;
            
            $top3Taches[] = [
                'tache_id' => $item['tache_id'],
                'titre' => $item['titre'],
                'frequence_moyenne' => $moyen,
                'frequence_formatee' => "{$moyen} itér.",
                'apprenants_touches' => $item['apprenants_avec_reprise'],
                'explication' => $item['apprenants_avec_reprise'] > 0
                    ? "{$item['apprenants_avec_reprise']} apprenants ont nécessité au moins 1 reprise."
                    : "Bonne appropriation dès la première soumission.",
            ];
        }

        // Statut didactique
        if ($frequence < 0.5) {
            $statut = 'Excellence didactique';
            $statutBadge = 'bg-emerald-50 text-emerald-700 border border-emerald-200';
        } elseif ($frequence <= 1.2) {
            $statut = 'Vigilance';
            $statutBadge = 'bg-amber-50 text-amber-700 border border-amber-200';
        } else {
            $statut = 'Alerte Tâtonnement';
            $statutBadge = 'bg-rose-50 text-rose-700 border border-rose-200';
        }

        return [
            'frequence' => $frequence,
            'frequence_formatee' => "{$frequence} reprise / tâche",
            'total_rejets' => $totalRejets,
            'total_taches' => $totalTaches,
            'taux_premier_jet' => $tauxConforme,
            'taux_premier_jet_formate' => "{$tauxConforme}% des tâches validées sans reprise",
            'statut' => $statut,
            'statut_badge' => $statutBadge,
            'top_taches' => $top3Taches,
            'description' => 'Suivi de la stabilité du code et détection des consignes pédagogiques ambiguës.',
        ];
    }

    /**
     * Récupère les données de l'Indicateur 03 pour le tableau de bord du Responsable.
     *
     * @param array $groupeIds
     * @param int|null $projetId
     * @return array
     */
    public function getIndicateurRevisionResponsable(array $groupeIds = [], ?int $projetId = null): array
    {
        $query = AffectationProjet::with(['groupe', 'realisationProjets.realisationTaches', 'projet'])
            ->whereHas('projet', function ($q) {
                $q->sessionsFormation();
            });

        if (!empty($groupeIds)) {
            $query->whereIn('groupe_id', $groupeIds);
        }
        if (!empty($projetId)) {
            $query->where('projet_id', $projetId);
        }

        $affectations = $query->latest('date_debut')->get();

        $toutesLesRevisions = [];
        $benchmark = [];

        foreach ($affectations as $aff) {
            $rejetsGroupe = 0;
            $tachesGroupe = 0;
            $sansRevisionGroupe = 0;

            foreach ($aff->realisationProjets as $rp) {
                foreach ($rp->realisationTaches as $rt) {
                    $nb = (int) ($rt->cache_nombre_revisions ?? 0);
                    $rejetsGroupe += $nb;
                    $toutesLesRevisions[] = $nb;
                    $tachesGroupe++;
                    if ($nb === 0) {
                        $sansRevisionGroupe++;
                    }
                }
            }

            $freqGroupe = $tachesGroupe > 0 ? round($rejetsGroupe / $tachesGroupe, 1) : 0.0;
            $tauxDirect = $tachesGroupe > 0 ? round(($sansRevisionGroupe / $tachesGroupe) * 100, 1) : 100.0;

            $benchmark[] = [
                'affectation_id' => $aff->id,
                'groupe_code' => $aff->groupe->code ?? 'Groupe',
                'groupe_nom' => $aff->groupe->nom ?? ($aff->groupe->code ?? 'Promotion'),
                'frequence_revision' => $freqGroupe,
                'frequence_formatee' => "{$freqGroupe} itér.",
                'taux_premier_jet' => $tauxDirect,
                'taux_premier_jet_formate' => "{$tauxDirect}%",
                'statut_stabilite' => $freqGroupe <= 1.2 ? 'Conforme' : 'Vigilance',
                'statut_badge' => $freqGroupe <= 1.2 
                    ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' 
                    : 'bg-amber-50 text-amber-700 border border-amber-200',
            ];
        }

        $totalTachesGlobal = count($toutesLesRevisions);
        $globalFreq = $totalTachesGlobal > 0 ? round(array_sum($toutesLesRevisions) / $totalTachesGlobal, 1) : 0.5;

        return [
            'frequence_globale' => $globalFreq,
            'frequence_globale_formatee' => "{$globalFreq} itér. / tâche",
            'benchmark' => $benchmark,
        ];
    }

    /**
     * Analyse granulaire détaillée pour l'Indicateur 03 : Fréquence de Révision & Stabilité du Premier Jet.
     *
     * @param array $filters
     * @return array
     */
    public function getIndicateurRevisionDetail(array $filters = []): array
    {
        $parametreService = app(\Modules\PkgTableauBord\Services\ParametrePedagogiqueService::class);
        $seuilOptimal = $parametreService->getSeuilRevisionOptimal($filters['projet_id'] ?? null);
        $seuilAlerte = $parametreService->getSeuilRevisionAlerte($filters['projet_id'] ?? null);

        $query = AffectationProjet::with([
            'groupe.filiere',
            'projet.formateur',
            'realisationProjets.apprenant.user',
            'realisationProjets.realisationTaches.tache',
            'realisationProjets.realisationTaches.etatRealisationTache.workflowTache',
            'realisationProjets.realisationTaches.revisionTaches'
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

        $affectations = $query->latest('date_debut')->get();

        $apprenants = [];
        $totalApprenants = 0;
        $totalTaches = 0;
        $totalRejets = 0;
        $tachesSansReprise = 0;
        $tachesCritiques = 0;

        $tranches = [
            'premier_jet' => 0,
            'une_reprise' => 0,
            'deux_trois_reprises' => 0,
            'quatre_plus' => 0,
        ];

        $tachesPareto = [];
        $benchmarkSessions = [];

        foreach ($affectations as $aff) {
            $totalSession = 0;
            $tachesSession = 0;
            $rejetsSession = 0;
            $sansRepriseSession = 0;

            foreach ($aff->realisationProjets as $rp) {
                $totalApprenants++;
                $totalSession++;
                $apprenant = $rp->apprenant;

                $tachesApprenant = 0;
                $rejetsApprenant = 0;
                $sansRepriseApprenant = 0;
                $tachesDetails = [];
                $tachePlusDifficile = null;
                $maxRejetsTache = 0;

                foreach ($rp->realisationTaches as $rt) {
                    $totalTaches++;
                    $tachesSession++;
                    $tachesApprenant++;

                    $nbRevisions = (int) ($rt->cache_nombre_revisions ?? 0);
                    $totalRejets += $nbRevisions;
                    $rejetsSession += $nbRevisions;
                    $rejetsApprenant += $nbRevisions;

                    if ($nbRevisions === 0) {
                        $tachesSansReprise++;
                        $sansRepriseSession++;
                        $sansRepriseApprenant++;
                        $tranches['premier_jet']++;
                    } elseif ($nbRevisions === 1) {
                        $tranches['une_reprise']++;
                    } elseif ($nbRevisions <= 3) {
                        $tachesCritiques++;
                        $tranches['deux_trois_reprises']++;
                    } else {
                        $tachesCritiques++;
                        $tranches['quatre_plus']++;
                    }

                    if ($nbRevisions > $maxRejetsTache) {
                        $maxRejetsTache = $nbRevisions;
                        $tachePlusDifficile = $rt->tache?->titre ?? "Tâche #{$rt->tache_id}";
                    }

                    $revisionsList = $rt->revisionTaches->map(function ($rev) {
                        return [
                            'id' => $rev->id,
                            'tentative' => $rev->tentative,
                            'date_demande' => $rev->date_demande ? \Carbon\Carbon::parse($rev->date_demande)->format('d/m/Y H:i') : '--',
                            'message_formateur' => $rev->message_formateur,
                            'est_valide' => (bool) $rev->est_valide,
                        ];
                    })->sortBy('tentative')->values()->all();

                    $tachesDetails[] = [
                        'id' => $rt->id,
                        'tache_id' => $rt->tache_id,
                        'titre' => $rt->tache?->titre ?? "Tâche #{$rt->tache_id}",
                        'etat' => optional($rt->etatRealisationTache?->workflowTache)->code ?? 'UNKNOWN',
                        'etat_titre' => $rt->etatRealisationTache?->nom ?? 'Inconnu',
                        'nb_revisions' => $nbRevisions,
                        'revisions' => $revisionsList,
                    ];

                    $tId = $rt->tache_id;
                    if (!isset($tachesPareto[$tId])) {
                        $tachesPareto[$tId] = [
                            'tache_id' => $tId,
                            'titre' => $rt->tache?->titre ?? "Tâche #{$tId}",
                            'total_rejets' => 0,
                            'total_evaluations' => 0,
                            'apprenants_touches' => 0,
                            'messages' => [],
                        ];
                    }
                    $tachesPareto[$tId]['total_rejets'] += $nbRevisions;
                    $tachesPareto[$tId]['total_evaluations']++;
                    if ($nbRevisions > 0) {
                        $tachesPareto[$tId]['apprenants_touches']++;
                        foreach ($rt->revisionTaches as $rev) {
                            if ($rev->message_formateur && !in_array($rev->message_formateur, $tachesPareto[$tId]['messages'])) {
                                $tachesPareto[$tId]['messages'][] = $rev->message_formateur;
                            }
                        }
                    }
                }

                $freqAppr = $tachesApprenant > 0 ? round($rejetsApprenant / $tachesApprenant, 2) : 0.0;
                $taux1erAppr = $tachesApprenant > 0 ? round(($sansRepriseApprenant / $tachesApprenant) * 100, 1) : 100.0;

                if ($tachesApprenant === 0) {
                    $statutCode = 'non_evalue';
                    $statutLabel = 'Non évalué';
                    $statutBadge = 'bg-slate-50 text-slate-700 border-slate-200';
                } elseif ($freqAppr < $seuilOptimal) {
                    $statutCode = 'premier_jet';
                    $statutLabel = 'Excellence';
                    $statutBadge = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                } elseif ($freqAppr <= $seuilAlerte) {
                    $statutCode = 'ajustement';
                    $statutLabel = 'Ajustements';
                    $statutBadge = 'bg-sky-50 text-sky-700 border-sky-200';
                } else {
                    $statutCode = 'tattonnement';
                    $statutLabel = 'Tâtonnement';
                    $statutBadge = 'bg-rose-50 text-rose-700 border-rose-200';
                }

                $apprenants[] = [
                    'id' => $apprenant?->id,
                    'nom_complet' => ($apprenant?->prenom ?? '') . ' ' . ($apprenant?->nom ?? 'Stagiaire'),
                    'prenom' => $apprenant?->prenom ?? '',
                    'nom' => $apprenant?->nom ?? 'Stagiaire',
                    'matricule' => $apprenant?->matricule ?? null,
                    'avatar' => $apprenant?->profile_image ?? null,
                    'groupe_code' => $aff->groupe?->code ?? ($aff->groupe?->nom ?? 'Promo'),
                    'projet_titre' => $aff->projet?->titre ?? 'Session',
                    'total_taches' => $tachesApprenant,
                    'total_rejets' => $rejetsApprenant,
                    'taches_sans_reprise' => $sansRepriseApprenant,
                    'taux_premier_jet' => $taux1erAppr,
                    'frequence_individuelle' => $freqAppr,
                    'statut_code' => $statutCode,
                    'statut_label' => $statutLabel,
                    'statut_badge' => $statutBadge,
                    'tache_critique' => $tachePlusDifficile ?? 'Aucune reprise',
                    'taches_details' => $tachesDetails,
                ];
            }

            $freqSession = $tachesSession > 0 ? round($rejetsSession / $tachesSession, 2) : 0.0;
            $tauxSession = $tachesSession > 0 ? round(($sansRepriseSession / $tachesSession) * 100, 1) : 100.0;

            if ($freqSession < $seuilOptimal) {
                $statutSession = 'Excellence';
                $statutSessionBadge = 'bg-emerald-50 text-emerald-700 border-emerald-200';
            } elseif ($freqSession <= $seuilAlerte) {
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
                'total_taches' => $tachesSession,
                'total_rejets' => $rejetsSession,
                'frequence_revision' => $freqSession,
                'frequence_formatee' => "{$freqSession} itér.",
                'taux_premier_jet' => $tauxSession,
                'taux_premier_jet_formate' => "{$tauxSession}%",
                'statut' => $statutSession,
                'statut_badge' => $statutSessionBadge,
            ];
        }

        usort($tachesPareto, fn($a, $b) => $b['total_rejets'] <=> $a['total_rejets']);
        foreach ($tachesPareto as &$tp) {
            $moyen = $tp['total_evaluations'] > 0 ? round($tp['total_rejets'] / $tp['total_evaluations'], 2) : 0.0;
            $tp['frequence_moyenne'] = $moyen;
            if ($moyen < $seuilOptimal) {
                $tp['diagnostic'] = 'Consigne limpide (Excellence)';
                $tp['badge'] = 'bg-emerald-50 text-emerald-700 border-emerald-200';
            } elseif ($moyen <= $seuilAlerte) {
                $tp['diagnostic'] = 'Ajustements techniques modérés';
                $tp['badge'] = 'bg-amber-50 text-amber-700 border-amber-200';
            } else {
                $tp['diagnostic'] = 'Friction didactique / Consigne à clarifier';
                $tp['badge'] = 'bg-rose-50 text-rose-700 border-rose-200';
            }
        }
        unset($tp);

        $frequenceGlobale = $totalTaches > 0 ? round($totalRejets / $totalTaches, 2) : 0.0;
        $tauxPremierJetGlobal = $totalTaches > 0 ? round(($tachesSansReprise / $totalTaches) * 100, 1) : 100.0;

        if ($frequenceGlobale < $seuilOptimal) {
            $statutGlobal = 'Excellence didactique';
            $statutGlobalBadge = 'bg-emerald-50 text-emerald-700 border-emerald-200';
        } elseif ($frequenceGlobale <= $seuilAlerte) {
            $statutGlobal = 'Vigilance';
            $statutGlobalBadge = 'bg-amber-50 text-amber-700 border-amber-200';
        } else {
            $statutGlobal = 'Alerte Tâtonnement';
            $statutGlobalBadge = 'bg-rose-50 text-rose-700 border-rose-200';
        }

        return [
            'frequence_moyenne' => $frequenceGlobale,
            'frequence_formatee' => "{$frequenceGlobale} reprise / tâche",
            'total_rejets' => $totalRejets,
            'total_taches' => $totalTaches,
            'taches_sans_reprise' => $tachesSansReprise,
            'taches_critiques' => $tachesCritiques,
            'total_apprenants' => $totalApprenants,
            'taux_premier_jet' => $tauxPremierJetGlobal,
            'taux_premier_jet_formate' => "{$tauxPremierJetGlobal}%",
            'seuil_optimal' => $seuilOptimal,
            'seuil_alerte' => $seuilAlerte,
            'statut' => $statutGlobal,
            'statut_badge' => $statutGlobalBadge,
            'tranches' => $tranches,
            'taches_pareto' => array_slice($tachesPareto, 0, 10),
            'apprenants' => $apprenants,
            'benchmark' => $benchmarkSessions,
            'description' => 'Suivi de la stabilité du code, de l\'autonomie et détection des consignes pédagogiques ambiguës.',
        ];
    }

    /**
     * Données par défaut pour l'apprenant.
     */
    protected function getDefaultIndicateurRevisionApprenant(): array
    {
        return [
            'frequence' => 0.0,
            'frequence_formatee' => '0.0 reprise / tâche',
            'total_rejets' => 0,
            'total_taches' => 0,
            'taches_sans_reprise' => 0,
            'taux_premier_jet' => 100.0,
            'taux_premier_jet_formate' => '100% de réussite directe',
            'statut' => 'Qualité initiale élevée',
            'statut_badge' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
            'description' => 'Mesure de la stabilité du code et de l\'autonomie dès le premier rendu soumis.',
        ];
    }

    /**
     * Données par défaut pour le formateur.
     */
    protected function getDefaultIndicateurRevisionFormateur(): array
    {
        return [
            'frequence' => 0.0,
            'frequence_formatee' => '0.0 reprise / tâche',
            'total_rejets' => 0,
            'total_taches' => 0,
            'taux_premier_jet' => 100.0,
            'taux_premier_jet_formate' => '100% des tâches validées sans reprise',
            'statut' => 'Excellence didactique',
            'statut_badge' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
            'top_taches' => [],
            'description' => 'Suivi de la stabilité du code et détection des consignes pédagogiques ambiguës.',
        ];
    }
}
