<?php

namespace Modules\PkgTableauBord\Services;

/**
 * Service centralisant l'ensemble des seuils, pondérations et paramètres didactiques de Solicode LMS.
 * 
 * Ce service constitue l'unique point d'entrée pour les paramètres pédagogiques.
 * À l'issue du refactoring, il sera directement branché sur la table BDD de paramétrage pédagogique.
 */
class ParametrePedagogiqueService
{
    /**
     * Cache ou surcharges dynamiques en mémoire pour les paramètres.
     *
     * @var array<string, mixed>
     */
    protected array $overrides = [];

    /**
     * Définitions des valeurs didactiques par défaut.
     *
     * @var array<string, mixed>
     */
    protected array $defaults = [
        // Indicateur 01 : Démarrage du sprint (T_amorçage) & Séances de lancement
        'amorcage_seuil_optimal_groupe_minutes' => 180, // 3h
        'amorcage_seuil_apprenant_minutes' => 90,        // 1h30
        'amorcage_delai_moyen_fallback_minutes' => 105,  // 1h45
        'amorcage_duree_seance_fallback_minutes' => 150, // 2h30
        'creneaux_seances_formation' => [
            ['nom' => 'Séance 1', 'debut' => '09:00', 'fin' => '11:30', 'duree_minutes' => 150],
            ['nom' => 'Séance 2', 'debut' => '11:30', 'fin' => '14:00', 'duree_minutes' => 150],
            ['nom' => 'Séance 3', 'debut' => '14:30', 'fin' => '17:00', 'duree_minutes' => 150],
        ],

        // Indicateur 02 : Gating Prototype 1er essai (R_gating)
        'gating_seuil_excellence_pourcentage' => 80.0,
        'gating_seuil_vigilance_pourcentage' => 65.0,

        // Indicateur 03 : Boucles de révision (F_révision)
        'revision_seuil_optimal' => 0.5,
        'revision_seuil_alerte' => 1.0,

        // Indicateur 04 : Charge logistique formateur (C_logistique)
        'charge_plafond_hebdo_heures' => 3.0,
        'charge_timeout_inactivite_minutes' => 5,

        // Indicateur 05 : Délai moyen en file FIFO (T_file)
        'file_seuil_optimal_minutes' => 15,
        'file_seuil_alerte_minutes' => 30,

        // Indicateur 06 : Alerte post-Live Coding & Remédiation (T_intervention)
        'remediation_seuil_blocage_minutes' => 10,
        'remediation_seuil_prise_en_charge_excellence' => 85.0,

        // Indicateur 07 : Intégrité face à l'IA & Pearson (r)
        'ia_seuil_ecart_suspect_points' => 5.0,
        'ia_seuil_correlation_forte' => 0.70,

        // Indicateur 08 : Couverture Pédagogique (ICP)
        'couverture_cible_nationale_pourcentage' => 90.0,
        'couverture_seuil_impasse_pourcentage' => 50.0,

        // Indicateur 09 : Règle docimologique 65/35 & Bordereaux
        'docimologie_poids_prototype' => 0.65,
        'docimologie_poids_projet' => 0.35,
        'docimologie_note_max_cc' => 20.0,
        'docimologie_note_max_efm' => 40.0,
        'docimologie_seuil_validation_efm' => 20.0,
        'docimologie_seuil_excellence_efm' => 28.0,
    ];

    /**
     * Récupère un paramètre pédagogique par sa clé avec prise en charge d'un projet optionnel.
     *
     * @param string $cle
     * @param mixed $default
     * @param int|null $projetId
     * @return mixed
     */
    public function get(string $cle, mixed $default = null, ?int $projetId = null): mixed
    {
        // 1. Surcharge dynamique en mémoire
        if (array_key_exists($cle, $this->overrides)) {
            return $this->overrides[$cle];
        }

        // 2. TODO: Lecture en base de données depuis la table 'parametre_pedagogiques'
        // ex: ParametrePedagogique::where('cle', $cle)->where(fn($q) => $q->whereNull('projet_id')->orWhere('projet_id', $projetId))->value('valeur');

        // 3. Valeur par défaut déclarée
        if (array_key_exists($cle, $this->defaults)) {
            return $this->defaults[$cle];
        }

        return $default;
    }

    /**
     * Permet de surcharger un paramètre au runtime (ex: tests unitaires, simulation).
     *
     * @param string $cle
     * @param mixed $valeur
     * @return self
     */
    public function set(string $cle, mixed $valeur): self
    {
        $this->overrides[$cle] = $valeur;
        return $this;
    }

    // =========================================================================
    // ACCESSEURS SPÉCIFIQUES - INDICATEUR 01 : T_AMORÇAGE
    // =========================================================================

    /**
     * Seuil didactique d'amorçage optimal pour un groupe (en minutes).
     *
     * @param int|null $projetId
     * @return int
     */
    public function getSeuilAmorcageOptimalGroupeMinutes(?int $projetId = null): int
    {
        return (int) $this->get('amorcage_seuil_optimal_groupe_minutes', 120, $projetId);
    }

    /**
     * Seuil didactique d'amorçage individuel pour un apprenant (en minutes).
     *
     * @param int|null $projetId
     * @return int
     */
    public function getSeuilAmorcageApprenantMinutes(?int $projetId = null): int
    {
        return (int) $this->get('amorcage_seuil_apprenant_minutes', 90, $projetId);
    }

    /**
     * Délai moyen d'amorçage par défaut (en minutes) utilisé en cas d'absence de données.
     *
     * @param int|null $projetId
     * @return int
     */
    public function getDelaiMoyenAmorcageFallbackMinutes(?int $projetId = null): int
    {
        return (int) $this->get('amorcage_delai_moyen_fallback_minutes', 105, $projetId);
    }

    /**
     * Récupère la liste des créneaux de séances journalières.
     *
     * @param int|null $projetId
     * @return array
     */
    public function getCreneauxSeancesFormation(?int $projetId = null): array
    {
        return (array) $this->get('creneaux_seances_formation', [
            ['nom' => 'Séance 1', 'debut' => '09:00', 'fin' => '11:30', 'duree_minutes' => 150],
            ['nom' => 'Séance 2', 'debut' => '11:30', 'fin' => '14:00', 'duree_minutes' => 150],
            ['nom' => 'Séance 3', 'debut' => '14:30', 'fin' => '17:00', 'duree_minutes' => 150],
        ], $projetId);
    }

    /**
     * Détermine la date et heure de fin de la séance de lancement pour un horodatage donné.
     * Si l'horodatage se situe dans un créneau de séance (ex: 09:30 dans 09:00-11:30), la fin
     * correspond à la fin de ce créneau (ex: 11:30).
     * Sinon, un fallback de 150 minutes (2h30) est appliqué.
     *
     * @param \Carbon\Carbon|string|null $dateLancement
     * @param int|null $projetId
     * @return \Carbon\Carbon
     */
    public function determinerFinSeanceLancement(mixed $dateLancement = null, ?int $projetId = null): \Carbon\Carbon
    {
        $carbonLancement = $dateLancement ? \Carbon\Carbon::parse($dateLancement) : now();
        $heureLancement = $carbonLancement->format('H:i');
        $creneaux = $this->getCreneauxSeancesFormation($projetId);

        foreach ($creneaux as $creneau) {
            $debut = $creneau['debut'] ?? '00:00';
            $fin = $creneau['fin'] ?? '23:59';

            if ($heureLancement >= $debut && $heureLancement < $fin) {
                list($finH, $finM) = explode(':', $fin);
                return $carbonLancement->copy()->setTime((int) $finH, (int) $finM, 0);
            }
        }

        // Cas de secours : ajouter la durée standard (150 minutes = 2h30)
        $dureeFallback = (int) $this->get('amorcage_duree_seance_fallback_minutes', 150, $projetId);
        return $carbonLancement->copy()->addMinutes($dureeFallback);
    }

    // =========================================================================
    // ACCESSEURS SPÉCIFIQUES - INDICATEUR 02 : R_GATING
    // =========================================================================

    public function getSeuilGatingExcellencePourcentage(?int $projetId = null): float
    {
        return (float) $this->get('gating_seuil_excellence_pourcentage', 80.0, $projetId);
    }

    public function getSeuilGatingVigilancePourcentage(?int $projetId = null): float
    {
        return (float) $this->get('gating_seuil_vigilance_pourcentage', 65.0, $projetId);
    }

    // =========================================================================
    // ACCESSEURS SPÉCIFIQUES - INDICATEUR 03 : F_RÉVISION
    // =========================================================================

    public function getSeuilRevisionOptimal(?int $projetId = null): float
    {
        return (float) $this->get('revision_seuil_optimal', 0.5, $projetId);
    }

    public function getSeuilRevisionAlerte(?int $projetId = null): float
    {
        return (float) $this->get('revision_seuil_alerte', 1.2, $projetId);
    }

    // =========================================================================
    // ACCESSEURS SPÉCIFIQUES - INDICATEUR 04 : C_LOGISTIQUE
    // =========================================================================

    public function getPlafondChargeLogistiqueHeures(?int $projetId = null): float
    {
        return (float) $this->get('charge_logistique_plafond_heures', 3.0, $projetId);
    }

    public function getSeuilChargeOptimalHeures(?int $projetId = null): float
    {
        return (float) $this->get('charge_logistique_seuil_optimal_heures', 2.5, $projetId);
    }

    public function getSeuilChargeAlerteHeures(?int $projetId = null): float
    {
        return (float) $this->get('charge_logistique_seuil_alerte_heures', 5.0, $projetId);
    }

    public function getTimeoutInactiviteMinutes(?int $projetId = null): int
    {
        return (int) $this->get('charge_logistique_timeout_inactivite_minutes', 5, $projetId);
    }

    // =========================================================================
    // ACCESSEURS SPÉCIFIQUES - INDICATEUR 06 : REMÉDIATION & LIVE CODING
    // =========================================================================

    public function getSeuilBlocagePostLiveCodingMinutes(?int $projetId = null): int
    {
        return (int) $this->get('remediation_seuil_blocage_minutes', 10, $projetId);
    }

    // =========================================================================
    // ACCESSEURS SPÉCIFIQUES - INDICATEUR 07 : INTÉGRITÉ IA
    // =========================================================================

    public function getSeuilEcartSuspectIAPoints(?int $projetId = null): float
    {
        return (float) $this->get('ia_seuil_ecart_suspect_points', 5.0, $projetId);
    }

    // =========================================================================
    // ACCESSEURS SPÉCIFIQUES - INDICATEUR 09 : DOCIMOLOGIE 65/35
    // =========================================================================

    public function getPoidsDocimologiePrototype(?int $projetId = null): float
    {
        return (float) $this->get('docimologie_poids_prototype', 0.65, $projetId);
    }

    public function getPoidsDocimologieProjet(?int $projetId = null): float
    {
        return (float) $this->get('docimologie_poids_projet', 0.35, $projetId);
    }
}

// Alias pour autoriser à la fois ParametrePedagogiqueService et ParametrePedagogique
class_alias(ParametrePedagogiqueService::class, 'Modules\PkgTableauBord\Services\ParametrePedagogique');
