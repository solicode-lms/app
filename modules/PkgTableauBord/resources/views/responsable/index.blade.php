@extends('layouts.admin-v2')

@section('title', 'Tableau de Bord Responsable')
@section('page_title', 'Tableau de Bord de Gouvernance Pédagogique')
@section('page_subtitle', 'Indicateurs macroscopiques et benchmark comparatif des groupes')

@section('actions')
<button onclick="window.print()" class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition shadow-2xs">
    <i class="fas fa-print text-slate-400"></i>
    <span>Imprimer</span>
</button>
@endsection

@section('filters')
@include('PkgTableauBord::components.responsable-filter-bar', [
    'formateurs' => $formateurs ?? collect(),
    'selectedFormateurId' => $selectedFormateurId ?? null,
    'filieres' => $filieres ?? collect(),
    'selectedFiliereId' => $selectedFiliereId ?? null,
    'groupes' => $groupes ?? collect(),
    'selectedGroupeId' => $selectedGroupeId ?? null,
    'projets' => $projets ?? collect(),
    'selectedProjetId' => $selectedProjetId ?? null,
    'filterType' => $filterType ?? 'sessions'
])
@endsection

@section('content')
<!-- En-tête contextuel Responsable -->
<section class="bg-white rounded-lg border border-slate-200 p-5 sm:p-6 shadow-sm space-y-3">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 flex-wrap">
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                    Direction Pédagogique
                </span>

                @if(!empty($isVisionGlobale))
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200 flex items-center gap-1.5">
                        <i class="fas fa-globe text-[11px] text-emerald-600"></i>
                        Vision Globale (Toutes promotions — Sessions N2)
                    </span>
                @else
                    @if(!empty($selectedFiliereId) && $filieres->firstWhere('id', $selectedFiliereId))
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-800 border border-indigo-200">
                            Filière : {{ $filieres->firstWhere('id', $selectedFiliereId)->code ?? $filieres->firstWhere('id', $selectedFiliereId)->nom }}
                        </span>
                    @endif
                    @if(!empty($selectedFormateurId) && $formateurs->firstWhere('id', $selectedFormateurId))
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-800 border border-indigo-200">
                            Formateur : {{ $formateurs->firstWhere('id', $selectedFormateurId)->nom }}
                        </span>
                    @endif
                    @if(!empty($selectedGroupeId) && $groupes->firstWhere('id', $selectedGroupeId))
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-800 border border-indigo-200">
                            Groupe : {{ $groupes->firstWhere('id', $selectedGroupeId)->code ?? $groupes->firstWhere('id', $selectedGroupeId)->nom }}
                        </span>
                    @endif
                    @if($projetActif)
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold {{ $isProjetSessionFormation ? 'bg-indigo-100/80 text-indigo-900 border-indigo-300' : 'bg-amber-100 text-amber-900 border-amber-300' }} border">
                            Projet : {{ $projetActif->titre }} {{ $isProjetSessionFormation ? '(Session N2)' : '(Standard)' }}
                        </span>
                    @endif
                @endif
            </div>
            <h2 class="text-xl font-bold text-slate-900 mt-1.5">
                Gouvernance & Cohérence des Formations
            </h2>
            <p class="text-xs text-slate-500 mt-0.5">
                Suivi macroscopique du rythme d'amorce des projets par filière et harmonisation des promotions.
            </p>
        </div>

        <div class="flex items-center gap-2 shrink-0">
            @if(!empty($isVisionGlobale))
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <i class="fas fa-graduation-cap"></i>
                    Toutes promotions actives
                </span>
            @else
                <a href="{{ url()->current() }}" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200 hover:bg-rose-100 transition shadow-2xs">
                    <i class="fas fa-rotate-left"></i>
                    Revenir à la Vision Globale
                </a>
            @endif
        </div>
    </div>

    @if($projetActif && !($isProjetSessionFormation ?? true))
    <!-- Bannière Explicative : Projet Hors Session de Formation -->
    <div class="p-3.5 bg-amber-50/90 border border-amber-200 rounded-lg text-amber-900 flex items-start gap-3 shadow-2xs mt-2">
        <div class="w-7 h-7 rounded-lg bg-amber-100 border border-amber-300 text-amber-700 flex items-center justify-center shrink-0 text-xs">
            <i class="fas fa-info-circle"></i>
        </div>
        <div class="text-xs leading-relaxed">
            <strong class="font-bold text-amber-950">Projet Standard Hors Session de Formation</strong> &bull; 
            Ce projet ne comporte pas d'épreuve de Live Coding (Phase N2) et ne mobilise pas d'Unités d'Apprentissage certificatives. 
            Ses résultats d'activité continue (amorçage, retouches, logistique, intervention) sont affichés ci-dessous, mais il est <strong>exclu des agrégations macroscopiques globales</strong>. Les indicateurs liés au Live Coding sont désactivés.
        </div>
    </div>
    @endif
</section>

<!-- Indicateurs de Gouvernance & Benchmark Macro -->
<section class="space-y-4">
    <div>
        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">
            {{ ($isProjetSessionFormation ?? true) ? 'Indicateurs Stratégiques & Benchmark Inter-Promotions (Sessions N2)' : 'Indicateurs d\'Activité Continue (Projet Standard)' }}
        </h3>
        <p class="text-xs text-slate-400">
            {{ ($isProjetSessionFormation ?? true) ? 'Suivi global du rythme d\'amorce des projets (Tamorçage) et du taux de validation directe du prototype (Rgating).' : 'Suivi du démarrage du sprint et de la charge logistique sur ce projet standard.' }}
        </p>
    </div>

    @if($isProjetSessionFormation ?? true)
    {{-- Grille Complète des 9 Indicateurs (Sessions de formation N2) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-9 gap-4">
        <!-- Carte Macroscopique Globale Amorçage (Indicateur 01) -->
        @include('PkgTableauBord::components.kpi-amorcage', ['type' => 'responsable', 'kpi' => $kpiAmorcage])

        <!-- Carte Macroscopique Globale Gating (Indicateur 02) -->
        @include('PkgTableauBord::components.kpi-gating', ['type' => 'responsable', 'kpi' => $kpiGating])

        <!-- Carte Macroscopique Globale Révision (Indicateur 03) -->
        @include('PkgTableauBord::components.kpi-revision', ['type' => 'responsable', 'kpi' => $kpiRevision])

        <!-- Carte Macroscopique Globale Charge Administrative (Indicateur 04) -->
        @include('PkgTableauBord::components.kpi-charge-logistique', ['type' => 'responsable', 'kpi' => $kpiChargeLogistique])

        <!-- Carte Macroscopique Globale Latence File FIFO (Indicateur 05) -->
        @include('PkgTableauBord::components.kpi-file-attente', ['type' => 'responsable', 'kpi' => $kpiFileAttente])

        <!-- Carte Macroscopique Globale Remédiation (Indicateur 06) -->
        @include('PkgTableauBord::components.kpi-intervention', ['type' => 'responsable', 'kpi' => $kpiIntervention])

        <!-- Carte Macroscopique Globale Intégrité IA (Indicateur 07) -->
        @include('PkgTableauBord::components.kpi-integrite-ia', ['type' => 'responsable', 'kpi' => $kpiIntegriteIA])

        <!-- Carte Macroscopique Globale Couverture Pédagogique (Indicateur 08) -->
        @include('PkgTableauBord::components.kpi-couverture-pedagogique', ['type' => 'responsable', 'kpi' => $kpiCouverture])

        <!-- Carte Macroscopique Globale Docimologie 65/35 (Indicateur 09) -->
        @include('PkgTableauBord::components.kpi-docimologie', ['type' => 'responsable', 'kpi' => $kpiDocimologie])
    </div>
    @else
    {{-- Grille Adaptée (Projet standard hors session : Indicateurs continus 01, 03, 04, 06) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Carte Macroscopique Globale Amorçage (Indicateur 01) -->
        @include('PkgTableauBord::components.kpi-amorcage', ['type' => 'responsable', 'kpi' => $kpiAmorcage])

        <!-- Carte Macroscopique Globale Révision (Indicateur 03) -->
        @include('PkgTableauBord::components.kpi-revision', ['type' => 'responsable', 'kpi' => $kpiRevision])

        <!-- Carte Macroscopique Globale Charge Administrative (Indicateur 04) -->
        @include('PkgTableauBord::components.kpi-charge-logistique', ['type' => 'responsable', 'kpi' => $kpiChargeLogistique])

        <!-- Carte Macroscopique Globale Remédiation (Indicateur 06) -->
        @include('PkgTableauBord::components.kpi-intervention', ['type' => 'responsable', 'kpi' => $kpiIntervention])
    </div>
    @endif

    <!-- Diagnostic d'Amorçage : Apprenants en Attente de Démarrage (Indicateur 01 — T_amorçage) -->
    @include('PkgTableauBord::components.apprenants-non-amorces-panel', ['kpi' => $kpiAmorcage])

    <!-- Panneau Explicatif : Détail du Calcul & Paramètres de l'Indicateur 01 (T_amorçage) -->
    <section class="bg-white rounded-lg border border-slate-200 p-5 sm:p-6 shadow-xs space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-3">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-lg bg-indigo-50 border border-indigo-200 text-indigo-700 flex items-center justify-center shrink-0 shadow-2xs">
                    <i class="fas fa-stopwatch text-base"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <h4 class="text-sm font-bold text-slate-900">Détail du Calcul : Indicateur 01 — Temps d'Amorçage (T<sub>amorçage</sub>)</h4>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200 font-mono">
                            SLA cible : &le; {{ $kpiAmorcage['seuil_optimal_formate'] ?? '2h00' }}
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Formule d'agrégation macroscopique et modalités de détection de la mise au travail sur le périmètre sélectionné.
                    </p>
                </div>
            </div>

            <a href="{{ route('admin.PkgTableauBord.indicateurs.amorcage', request()->query()) }}" 
               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 text-xs font-bold transition shadow-2xs shrink-0"
               title="Consulter l'analyse complète, les courbes de dispersion et le roster apprenants">
                <span>Analyse Détaillée Indicateur 01</span>
                <i class="fas fa-arrow-right text-[10px]"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Formule de calcul -->
            <div class="p-3.5 bg-slate-50 rounded-lg border border-slate-200 space-y-1.5">
                <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider block">Formule Mathématique</span>
                <div class="font-mono text-xs font-black text-indigo-900 bg-white p-2 rounded border border-slate-200">
                    T<sub>amorçage</sub> = Date 1<sup>re</sup> soumission - Date lancement
                </div>
                <p class="text-[11px] text-slate-500 leading-relaxed">
                    <strong>Condition de présence :</strong> 1<sup>re</sup> tâche mise en cours avant la fin de la séance de lancement (&le; {{ $kpiAmorcage['seuil_optimal_formate'] ?? '2h30' }}). Les retards liés à une absence sont isolés et exclus de la moyenne.
                </p>
            </div>

            <!-- Périmètre & Filtres -->
            <div class="p-3.5 bg-slate-50 rounded-lg border border-slate-200 space-y-1.5">
                <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider block">Périmètre de Gouvernance</span>
                <div class="text-xs font-semibold text-slate-800">
                    {{ !empty($isVisionGlobale) ? '10 sessions de formation actives (Sessions N2)' : 'Sessions de formation filtrées' }}
                </div>
                <p class="text-[11px] text-slate-500 leading-relaxed">
                    Le calcul est effectué sur les affectations de projet de type session de formation mobilisant une épreuve Live Coding (N2).
                </p>
            </div>

            <!-- Synthèse Chiffrée -->
            <div class="p-3.5 bg-slate-50 rounded-lg border border-slate-200 space-y-1.5">
                <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider block">Synthèse du Périmètre</span>
                <div class="flex items-baseline justify-between">
                    <span class="text-xs text-slate-600">Délai moyen (présents) :</span>
                    <strong class="font-mono text-sm text-indigo-700">{{ $kpiAmorcage['delai_moyen_global_formate'] ?? '--' }}</strong>
                </div>
                <div class="flex items-baseline justify-between text-[11px] text-slate-500">
                    <span>Amorcés en séance :</span>
                    <strong class="font-mono text-slate-800">{{ $kpiAmorcage['nb_amorces'] ?? 0 }}/{{ $kpiAmorcage['total_apprenants'] ?? 0 }} réalisations</strong>
                </div>
                @if(!empty($kpiAmorcage['nb_demarrage_avec_absence']))
                <div class="flex items-baseline justify-between text-[11px] text-purple-700 font-medium">
                    <span>Démarrages avec absence :</span>
                    <strong class="font-mono">{{ $kpiAmorcage['nb_demarrage_avec_absence'] }} (hors moyenne)</strong>
                </div>
                @endif
                <div class="flex items-baseline justify-between text-[11px] text-slate-500">
                    <span>Sessions analysées :</span>
                    <strong class="font-mono text-slate-800">{{ count($kpiAmorcage['benchmark'] ?? []) }} sessions de formation</strong>
                </div>
            </div>
        </div>
    </section>

    @if($isProjetSessionFormation ?? true)
    <!-- Section Scatter Plot SVG & Diagnostic d'Intégrité IA (Indicateur 07 — Pearson r) -->
    @include('PkgTableauBord::components.scatter-plot-ia', ['kpi' => $kpiIntegriteIA])
    @endif

    <!-- Matrice Comparative / Benchmark Réel des Promotions -->
    <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-200 bg-slate-50/70">
            <h4 class="text-sm font-bold text-slate-900">Matrice Comparative Inter-Promotions</h4>
            <p class="text-xs text-slate-500">Benchmark des groupes pour l'harmonisation du rythme pédagogique (T<sub>amorçage</sub>, R<sub>gating</sub>, F<sub>révision</sub>, T<sub>file</sub>, T<sub>intervention</sub> & r)</p>
        </div>

        @php
        $gatingByAff = collect($kpiGating['benchmark'] ?? [])->keyBy('affectation_id');
        $revisionByAff = collect($kpiRevision['benchmark'] ?? [])->keyBy('affectation_id');
        $fileByAff = collect($kpiFileAttente['benchmark'] ?? [])->keyBy('affectation_id');
        $interventionByAff = collect($kpiIntervention['benchmark'] ?? [])->keyBy('affectation_id');
        $integriteByAff = collect($kpiIntegriteIA['benchmark'] ?? [])->keyBy('affectation_id');
        $couvertureByGroupe = collect($kpiCouverture['groupes_benchmark'] ?? [])->keyBy('groupe_nom');
        $docimologieByGroupe = collect($kpiDocimologie['groupes_benchmark'] ?? [])->keyBy('groupe_nom');
        @endphp

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500 font-semibold bg-slate-50/50">
                        <th class="py-3 px-5">Session de Formation / Groupe</th>
                        <th class="py-3 px-4">Délai Moyen (T<sub>amorçage</sub>)</th>
                        <th class="py-3 px-4">Taux d'Amorçage</th>
                        <th class="py-3 px-4">Validation Prototype (R<sub>gating</sub>)</th>
                        <th class="py-3 px-4">Stabilité & Reprises (F<sub>révision</sub>)</th>
                        <th class="py-3 px-4">Attente File (T<sub>file</sub>)</th>
                        <th class="py-3 px-4">Remédiation (T<sub>intervention</sub>)</th>
                        <th class="py-3 px-4">Cohérence IA (r)</th>
                        <th class="py-3 px-4">Couverture Référentiel (ICP)</th>
                        <th class="py-3 px-4">Docimologie 65/35 (&tau;)</th>
                        <th class="py-3 px-5 text-right">Statut</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($kpiAmorcage['benchmark'] ?? [] as $item)
                    @php
                    $gatingItem = $gatingByAff->get($item['affectation_id']);
                    $tauxGating = $gatingItem['taux_gating'] ?? null;
                    $revItem = $revisionByAff->get($item['affectation_id']);
                    $fileItem = $fileByAff->get($item['affectation_id']);
                    $intervItem = $interventionByAff->get($item['affectation_id']);
                    $integItem = $integriteByAff->get($item['affectation_id']);
                    $couvItem = $couvertureByGroupe->get($item['groupe_code']);
                    $dociItem = $docimologieByGroupe->get($item['groupe_code']);
                    @endphp
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="py-3 px-5 font-bold text-slate-900">
                            <div class="truncate max-w-[200px]" title="{{ $item['session_titre'] ?? 'Session N2' }}">{{ $item['session_titre'] ?? 'Session N2' }}</div>
                            <span class="text-[11px] text-slate-500 font-normal">{{ $item['groupe_code'] }} ({{ $item['groupe_nom'] }})</span>
                        </td>
                        <td class="py-3 px-4 font-mono font-bold text-indigo-700">{{ $item['temps_moyen_formate'] }}</td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-1.5 flex-wrap">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $item['taux_amorce'] >= 75 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                                    {{ $item['taux_amorce'] }}% amorcés ({{ $item['nb_amorces'] }}/{{ $item['total_apprenants'] }})
                                </span>
                                @if(!empty($item['nb_demarrage_avec_absence']) && $item['nb_demarrage_avec_absence'] > 0)
                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-purple-50 text-purple-700 border border-purple-200" title="{{ $item['nb_demarrage_avec_absence'] }} réalisation(s) avec absence">
                                        {{ $item['nb_demarrage_avec_absence'] }} abs.
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            @if(!is_null($tauxGating))
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $tauxGating >= 75 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                                {{ $gatingItem['taux_gating_formate'] }} ({{ $gatingItem['valides_premier_essai'] }}/{{ $gatingItem['total_evalues'] }})
                            </span>
                            @else
                            <span class="text-slate-400 italic">Non évalué</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 font-mono">
                            @if($revItem)
                            <span class="font-bold text-slate-800">{{ $revItem['frequence_formatee'] }}</span>
                            <span class="text-[10px] text-slate-400 block">{{ $revItem['taux_premier_jet_formate'] }} 1er jet</span>
                            @else
                            <span class="text-slate-400">--</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 font-mono">
                            @if($fileItem)
                            <span class="font-bold text-slate-800">{{ $fileItem['delai_moyen_formate'] }}</span>
                            <span class="text-[10px] text-slate-400 block">{{ $fileItem['total_en_attente'] }} en file</span>
                            @else
                            <span class="text-slate-400">--</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 font-mono">
                            @if($intervItem)
                            <span class="font-bold text-slate-800">{{ $intervItem['taux_intervention_formate'] }}</span>
                            <span class="text-[10px] text-slate-400 block">{{ $intervItem['en_attente_soutien'] }} en attente</span>
                            @else
                            <span class="text-slate-400">--</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 font-mono">
                            @if($integItem)
                            <span class="font-bold text-slate-800">{{ $integItem['r_formate'] }}</span>
                            <span class="text-[10px] {{ $integItem['nb_anomalies'] > 0 ? 'text-rose-600 font-bold' : 'text-slate-400' }} block">
                                {{ $integItem['nb_anomalies'] > 0 ? $integItem['nb_anomalies'] . ' alerte(s)' : 'Optimal' }}
                            </span>
                            @else
                            <span class="text-slate-400">--</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 font-mono">
                            @if($couvItem)
                            <span class="font-bold text-emerald-700">{{ $couvItem['icp_formate'] }}</span>
                            <span class="text-[10px] {{ $couvItem['badge_class'] ?? 'text-slate-400' }} px-1.5 py-0.5 rounded block w-fit">
                                {{ $couvItem['statut_badge'] ?? 'Optimal' }}
                            </span>
                            @else
                            <span class="font-bold text-emerald-700">{{ $kpiCouverture['taux_macro_formate'] ?? '85%' }}</span>
                            <span class="text-[10px] text-emerald-600 block">Conforme</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 font-mono">
                            @if($dociItem)
                            <span class="font-bold text-indigo-700">{{ $dociItem['taux_completude_formate'] }}</span>
                            <span class="text-[10px] {{ $dociItem['badge_class'] ?? 'text-slate-400' }} px-1.5 py-0.5 rounded block w-fit">
                                {{ $dociItem['statut_badge'] ?? 'Certifié' }}
                            </span>
                            @else
                            <span class="font-bold text-indigo-700">100%</span>
                            <span class="text-[10px] text-emerald-600 block">Certifié</span>
                            @endif
                        </td>
                        <td class="py-3 px-5 text-right">
                            @php
                            $estConforme = $item['taux_amorce'] >= 75
                            && (is_null($tauxGating) || $tauxGating >= 75)
                            && (!$revItem || $revItem['frequence_revision'] <= 1.2);
                                @endphp
                                @if($estConforme)
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">Conforme</span>
                                @else
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-300">À réguler</span>
                                @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-6 text-center text-slate-400">Aucune donnée de promotion disponible.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Matrice Benchmark de la Charge Administrative des Formateurs (C_logistique) -->
    <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-200 bg-slate-50/70 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2">
            <div>
                <h4 class="text-sm font-bold text-slate-900">Charge Administrative par Formateur (C<sub>logistique</sub>)</h4>
                <p class="text-xs text-slate-500">Volume horaire hebdomadaire effectif consacré au micro-suivi (Semaine : {{ $kpiChargeLogistique['semaine'] ?? '--' }})</p>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $kpiChargeLogistique['badge_class'] ?? 'bg-emerald-50 text-emerald-700' }}">
                Moyenne : {{ $kpiChargeLogistique['charge_moyenne_formatee'] ?? '0 h/sem' }}
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500 font-semibold bg-slate-50/50">
                        <th class="py-3 px-5">Formateur</th>
                        <th class="py-3 px-4">Temps Total (C<sub>logistique</sub>)</th>
                        <th class="py-3 px-4">Pilotage Kanban</th>
                        <th class="py-3 px-4">File FIFO</th>
                        <th class="py-3 px-4">Bordereaux Notes</th>
                        <th class="py-3 px-5 text-right">Diagnostic</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($kpiChargeLogistique['benchmark'] ?? [] as $fItem)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="py-3 px-5 font-bold text-slate-900">
                            {{ $fItem['formateur_nom'] }}
                            <span class="text-[10px] text-slate-400 block font-normal">{{ $fItem['matricule'] }}</span>
                        </td>
                        <td class="py-3 px-4 font-mono font-bold text-slate-900">
                            {{ $fItem['total_heures_formate'] }}
                            <span class="text-[10px] text-slate-400 font-normal">({{ $fItem['total_heures'] }}h)</span>
                        </td>
                        <td class="py-3 px-4 font-mono text-slate-600">{{ $fItem['details']['pilotage_formate'] ?? '0 min' }}</td>
                        <td class="py-3 px-4 font-mono text-slate-600">{{ $fItem['details']['file_formate'] ?? '0 min' }}</td>
                        <td class="py-3 px-4 font-mono text-slate-600">{{ $fItem['details']['bordereaux_formate'] ?? '0 min' }}</td>
                        <td class="py-3 px-5 text-right">
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $fItem['badge_class'] }}">
                                {{ $fItem['badge_label'] }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-6 text-center text-slate-400">Aucune donnée d'activité enregistrée pour cette semaine.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection