<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Catalogue Global des Indicateurs Didactiques (PkgTableauBord)
    |--------------------------------------------------------------------------
    | Déclaration des 9 indicateurs officiels de la console de régulation :
    | - id               : Identifiant numérique
    | - code             : Notation didactique canonique
    | - titre            : Libellé descriptif complet
    | - icone            : Classe FontAwesome / Tabler
    | - ordre            : Ordre d'affichage par défaut (1..9)
    | - card_component   : Composant Blade de la tuile KPI synthétique (grille)
    | - panel_component  : Composant Blade du volet opérationnel (chaîne ou tableau par rôle)
    | - detail_route     : Route de la vue d'analyse détaillée granulaire
    | - requires_session : Si true, inactif sur les projets standards (hors N2)
    */
    'indicateurs' => [
        'amorcage' => [
            'id' => 1,
            'code' => 'T_amorcage',
            'titre' => 'Délai d\'amorçage',
            'icone' => 'fas fa-stopwatch',
            'ordre' => 1,
            'card_component' => 'PkgTableauBord::components.kpi-amorcage',
            'panel_component' => 'PkgTableauBord::components.apprenants-non-amorces-panel',
            'detail_route' => 'admin.PkgTableauBord.indicateurs.amorcage',
            'requires_session' => false,
        ],
        'gating' => [
            'id' => 2,
            'code' => 'R_gating',
            'titre' => 'Validation Prototype (N2)',
            'icone' => 'fas fa-shield-alt',
            'ordre' => 2,
            'card_component' => 'PkgTableauBord::components.kpi-gating',
            'panel_component' => null,
            'detail_route' => 'admin.PkgTableauBord.indicateurs.gating',
            'requires_session' => true,
        ],
        'revision' => [
            'id' => 3,
            'code' => 'F_revision',
            'titre' => 'Fréquence des Révisions',
            'icone' => 'fas fa-redo-alt',
            'ordre' => 3,
            'card_component' => 'PkgTableauBord::components.kpi-revision',
            'panel_component' => 'PkgTableauBord::components.revisions-top-taches-panel',
            'detail_route' => 'admin.PkgTableauBord.indicateurs.revisions',
            'requires_session' => false,
        ],
        'charge_logistique' => [
            'id' => 4,
            'code' => 'C_logistique',
            'titre' => 'Charge Logistique Formateur',
            'icone' => 'fas fa-clipboard-check',
            'ordre' => 4,
            'card_component' => 'PkgTableauBord::components.kpi-charge-logistique',
            'panel_component' => null,
            'detail_route' => 'admin.PkgTableauBord.indicateurs.charge-logistique',
            'requires_session' => false,
        ],
        'file_attente' => [
            'id' => 5,
            'code' => 'T_file',
            'titre' => 'Latence File d\'Attente FIFO',
            'icone' => 'fas fa-users-line',
            'ordre' => 5,
            'card_component' => 'PkgTableauBord::components.kpi-file-attente',
            'panel_component' => [
                'formateur' => 'PkgTableauBord::components.file-attente-console',
                'apprenant' => 'PkgTableauBord::components.file-attente-apprenant-panel',
                'admin'     => 'PkgTableauBord::components.file-attente-console',
                'root'      => 'PkgTableauBord::components.file-attente-console',
            ],
            'detail_route' => 'admin.PkgTableauBord.indicateurs.file-attente',
            'requires_session' => true,
        ],
        'intervention' => [
            'id' => 6,
            'code' => 'T_intervention',
            'titre' => 'Prise en charge des Ruptures',
            'icone' => 'fas fa-bolt',
            'ordre' => 6,
            'card_component' => 'PkgTableauBord::components.kpi-intervention',
            'panel_component' => [
                'formateur' => 'PkgTableauBord::components.flashbox-alerte-blocages',
                'apprenant' => 'PkgTableauBord::components.intervention-apprenant-panel',
                'admin'     => 'PkgTableauBord::components.flashbox-alerte-blocages',
                'root'      => 'PkgTableauBord::components.flashbox-alerte-blocages',
            ],
            'detail_route' => 'admin.PkgTableauBord.indicateurs.intervention',
            'requires_session' => false,
        ],
        'trvs' => [
            'id' => 7,
            'code' => 'TRVS',
            'titre' => 'Taux de Réalisation Validée du Sprint',
            'icone' => 'fas fa-chart-line',
            'ordre' => 7,
            'card_component' => 'PkgTableauBord::components.kpi-integrite-ia',
            'panel_component' => 'PkgTableauBord::components.trvs-apprenants-panel',
            'detail_route' => 'admin.PkgTableauBord.indicateurs.trvs',
            'requires_session' => true,
        ],
        'couverture' => [
            'id' => 8,
            'code' => 'ICP',
            'titre' => 'Couverture Référentiel National',
            'icone' => 'fas fa-graduation-cap',
            'ordre' => 8,
            'card_component' => 'PkgTableauBord::components.kpi-couverture-pedagogique',
            'panel_component' => 'PkgTableauBord::components.impasses-pedagogiques-panel',
            'detail_route' => 'admin.PkgTableauBord.indicateurs.couverture',
            'requires_session' => true,
        ],
        'planification_ua' => [
            'id' => 9,
            'code' => 'TCP-UA',
            'titre' => 'Couverture Planification des UA',
            'icone' => 'fas fa-calendar-check',
            'ordre' => 9,
            'card_component' => 'PkgTableauBord::components.kpi-docimologie',
            'panel_component' => 'PkgTableauBord::components.bordereau-officiel-panel',
            'detail_route' => 'admin.PkgTableauBord.indicateurs.tcp-ua',
            'requires_session' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Matrice de Configuration par Acteur / Rôle
    |--------------------------------------------------------------------------
    | Paramètres disponibles pour chaque indicateur :
    | - enabled     (bool) : Master switch. Si false, l'indicateur est totalement
    |                        désactivé (ni Card, ni Panel, ni Page de détail).
    | - ordre       (int)  : Ordre d'affichage spécifique au rôle (1, 2, 3...).
    | - show_card   (bool) : Affichage de la carte dans la grille KPI supérieure.
    | - show_panel  (bool) : Affichage du volet opérationnel/métier associé.
    |                        (RÈGLE DE COUPLAGE : si l'indicateur est inactif,
    |                        ses panels ne sont JAMAIS affichés de façon orpheline).
    | - show_detail (bool) : Visibilité du bouton d'accès et autorisation HTTP
    |                        vers la page d'analyse détaillée.
    */
    'roles' => [

        /*
        |----------------------------------------------------------------------
        | Espace Apprenant (Autorégulation & Transparence)
        |----------------------------------------------------------------------
        */
        'apprenant' => [
            'indicateurs' => [
                'amorcage' => [
                    'enabled'     => false,
                    'ordre'       => 1,
                    'show_card'   => true,
                    'show_panel'  => false,
                    'show_detail' => false,
                ],
                'gating' => [
                    'enabled'     => false,
                    'ordre'       => 2,
                    'show_card'   => true,
                    'show_panel'  => false,
                    'show_detail' => false,
                ],
                'revision' => [
                    'enabled'     => false,
                    'ordre'       => 3,
                    'show_card'   => true,
                    'show_panel'  => false,
                    'show_detail' => false,
                ],
                'charge_logistique' => [
                    'enabled'     => false,
                    'ordre'       => 4,
                    'show_card'   => false,
                    'show_panel'  => false,
                    'show_detail' => false,
                ],
                'file_attente' => [
                    'enabled'     => false,
                    'ordre'       => 5,
                    'show_card'   => false,
                    'show_panel'  => true,  // Volet direct : rang de passage et heure estimée
                    'show_detail' => false,
                ],
                'intervention' => [
                    'enabled'     => false,
                    'ordre'       => 6,
                    'show_card'   => false,
                    'show_panel'  => true,  // Volet direct : message d'accompagnement bienveillant
                    'show_detail' => false,
                ],
                'trvs' => [
                    'enabled'     => false,
                    'ordre'       => 7,
                    'show_card'   => false,
                    'show_panel'  => false,
                    'show_detail' => false,
                ],
                'couverture' => [
                    'enabled'     => false,
                    'ordre'       => 8,
                    'show_card'   => true,
                    'show_panel'  => false,
                    'show_detail' => false,
                ],
                'planification_ua' => [
                    'enabled'     => false,
                    'ordre'       => 9,
                    'show_card'   => true,
                    'show_panel'  => false,
                    'show_detail' => false,
                ],
            ],
        ],

        /*
        |----------------------------------------------------------------------
        | Espace Formateur (Pilotage Synchrone de Classe)
        |----------------------------------------------------------------------
        */
        'formateur' => [
            'indicateurs' => [
                'amorcage' => [
                    'enabled'     => false,
                    'ordre'       => 1,
                    'show_card'   => true,
                    'show_panel'  => true,  // Diagnostic des retards d'amorçage
                    'show_detail' => true,
                ],
                'gating' => [
                    'enabled'     => false,
                    'ordre'       => 2,
                    'show_card'   => true,
                    'show_panel'  => false,
                    'show_detail' => true,
                ],
                'revision' => [
                    'enabled'     => false,
                    'ordre'       => 3,
                    'show_card'   => true,
                    'show_panel'  => true,  // Top 3 des tâches à réaligner
                    'show_detail' => true,
                ],
                'charge_logistique' => [
                    'enabled'     => false,
                    'ordre'       => 4,
                    'show_card'   => true,
                    'show_panel'  => false,
                    'show_detail' => true,
                ],
                'file_attente' => [
                    'enabled'     => false,
                    'ordre'       => 5,
                    'show_card'   => true,
                    'show_panel'  => true,  // Console interactive FIFO
                    'show_detail' => true,
                ],
                'intervention' => [
                    'enabled'     => false,
                    'ordre'       => 6,
                    'show_card'   => true,
                    'show_panel'  => true,  // Flashbox alerte ruptures (+10 min)
                    'show_detail' => true,
                ],
                'trvs' => [
                    'enabled'     => false,
                    'ordre'       => 7,
                    'show_card'   => true,
                    'show_panel'  => true,  // Suivi détaillé des réalisations validées
                    'show_detail' => true,
                ],
                'couverture' => [
                    'enabled'     => false,
                    'ordre'       => 8,
                    'show_card'   => true,
                    'show_panel'  => false,
                    'show_detail' => true,
                ],
                'planification_ua' => [
                    'enabled'     => false,
                    'ordre'       => 9,
                    'show_card'   => true,
                    'show_panel'  => false,
                    'show_detail' => true,
                ],
            ],
        ],

        /*
        |----------------------------------------------------------------------
        | Espace Admin (Gouvernance, Équité & Supervision Globale)
        |----------------------------------------------------------------------
        */
        'admin' => [
            'indicateurs' => [
                'amorcage' => [
                    'enabled'     => false,
                    'ordre'       => 1,
                    'show_card'   => true,
                    'show_panel'  => false,
                    'show_detail' => true,
                ],
                'gating' => [
                    'enabled'     => false,
                    'ordre'       => 2,
                    'show_card'   => true,
                    'show_panel'  => false,
                    'show_detail' => true,
                ],
                'revision' => [
                    'enabled'     => false,
                    'ordre'       => 3,
                    'show_card'   => true,
                    'show_panel'  => false,
                    'show_detail' => true,
                ],
                'charge_logistique' => [
                    'enabled'     => false,
                    'ordre'       => 4,
                    'show_card'   => true,
                    'show_panel'  => false,
                    'show_detail' => true,
                ],
                'file_attente' => [
                    'enabled'     => false,
                    'ordre'       => 5,
                    'show_card'   => true,
                    'show_panel'  => false,
                    'show_detail' => true,
                ],
                'intervention' => [
                    'enabled'     => false,
                    'ordre'       => 6,
                    'show_card'   => true,
                    'show_panel'  => false,
                    'show_detail' => true,
                ],
                'trvs' => [
                    'enabled'     => false,
                    'ordre'       => 7,
                    'show_card'   => true,
                    'show_panel'  => false,
                    'show_detail' => true,
                ],
                'couverture' => [
                    'enabled'     => false,
                    'ordre'       => 8,
                    'show_card'   => true,
                    'show_panel'  => true,  // Impasses pédagogiques nationales
                    'show_detail' => true,
                ],
                'planification_ua' => [
                    'enabled'     => false,
                    'ordre'       => 9,
                    'show_card'   => true,
                    'show_panel'  => true,  // Bordereau officiel de conformité UA
                    'show_detail' => true,
                ],
            ],
        ],

        /*
        |----------------------------------------------------------------------
        | Rôle Root (Super-Administrateur Système)
        |----------------------------------------------------------------------
        | 'display_all' => true : Visibilité absolue et inconditionnelle
        | (100% Cards, 100% Panels, 100% Pages de détail).
        */
        'root' => [
            'display_all' => true,
            'indicateurs' => [
                'amorcage' => [
                    'enabled'     => false,
                    'ordre'       => 1,
                    'show_card'   => true,
                    'show_panel'  => true,
                    'show_detail' => true,
                ],
                'gating' => [
                    'enabled'     => false,
                    'ordre'       => 2,
                    'show_card'   => true,
                    'show_panel'  => true,
                    'show_detail' => true,
                ],
                'revision' => [
                    'enabled'     => false,
                    'ordre'       => 3,
                    'show_card'   => true,
                    'show_panel'  => true,
                    'show_detail' => true,
                ],
                'charge_logistique' => [
                    'enabled'     => false,
                    'ordre'       => 4,
                    'show_card'   => true,
                    'show_panel'  => true,
                    'show_detail' => true,
                ],
                'file_attente' => [
                    'enabled'     => false,
                    'ordre'       => 5,
                    'show_card'   => true,
                    'show_panel'  => true,
                    'show_detail' => true,
                ],
                'intervention' => [
                    'enabled'     => false,
                    'ordre'       => 6,
                    'show_card'   => true,
                    'show_panel'  => true,
                    'show_detail' => true,
                ],
                'trvs' => [
                    'enabled'     => false,
                    'ordre'       => 7,
                    'show_card'   => true,
                    'show_panel'  => true,
                    'show_detail' => true,
                ],
                'couverture' => [
                    'enabled'     => false,
                    'ordre'       => 8,
                    'show_card'   => true,
                    'show_panel'  => true,
                    'show_detail' => true,
                ],
                'planification_ua' => [
                    'enabled'     => false,
                    'ordre'       => 9,
                    'show_card'   => true,
                    'show_panel'  => true,
                    'show_detail' => true,
                ],
            ],
        ],

    ],

];
