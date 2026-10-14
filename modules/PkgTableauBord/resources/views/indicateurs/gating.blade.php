@extends('layouts.admin-v2')

@section('title', 'Détail Indicateur 02 — Validation du Prototype & Gating')
@section('page_title', 'Indicateur 02 : Validation du Prototype & Gating (R_gating)')
@section('page_subtitle', 'Analyse détaillée du taux de passage direct au Live Coding Niveau 2, suivi des remédiations et déblocage du Projet Fil Rouge')

@section('actions')
<div class="flex items-center gap-2">
    <a href="{{ route('admin.PkgTableauBord.responsable', request()->query()) }}" 
       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition shadow-2xs">
        <i class="fas fa-arrow-left text-slate-400"></i>
        <span>Retour Tableau de Bord</span>
    </a>
    <button onclick="window.print()" class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition shadow-2xs">
        <i class="fas fa-print text-slate-400"></i>
        <span>Imprimer</span>
    </button>
</div>
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
<div class="space-y-6">

    <!-- En-tête contextuel & Fiche Didactique de l'Indicateur -->
    <section class="bg-white rounded-lg border border-slate-200 p-5 sm:p-6 shadow-xs space-y-4">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div class="space-y-1.5">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200 flex items-center gap-1.5">
                        <i class="fas fa-shield-halved text-indigo-500"></i>
                        <span>Indicateur Clé #02</span>
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                        Objectif SLA : &ge; {{ $detail['seuil_excellence'] }}%
                    </span>
                    @if(!empty($isVisionGlobale))
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200 flex items-center gap-1">
                            <i class="fas fa-globe text-[11px] text-emerald-600"></i>
                            <span>Vision Globale (10 sessions de formation N2)</span>
                        </span>
                    @elseif($selectedGroupeId && $groupes->firstWhere('id', $selectedGroupeId))
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-100 text-indigo-800 border border-indigo-200">
                            Groupe : {{ $groupes->firstWhere('id', $selectedGroupeId)->code ?? $groupes->firstWhere('id', $selectedGroupeId)->nom }}
                        </span>
                    @endif
                    @if($projetActif)
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-50 text-indigo-900 border border-indigo-200 truncate max-w-[280px]">
                            Projet : {{ $projetActif->titre }}
                        </span>
                    @endif
                </div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                    Taux de Validation du Prototype au 1<sup>er</sup> Essai (R<sub>gating</sub>)
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 max-w-4xl leading-relaxed">
                    Mesure la proportion d'apprenants validant leur prototype dès la première tentative lors du Live Coding surveillé (Niveau 2). Un taux élevé (&ge; 80%) atteste d'une réelle assimilation des fondamentaux, prévient l'illusion de maîtrise et autorise l'accès immédiat au Projet Fil Rouge en autonomie (Niveau 3).
                </p>
            </div>

            <!-- Jauge synthétique globale -->
            <div class="shrink-0 flex items-center gap-4 bg-slate-50 p-3.5 rounded-lg border border-slate-200">
                <div class="text-center">
                    <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">Taux de Gating (R<sub>gating</sub>)</span>
                    <span class="text-2xl sm:text-3xl font-black {{ $detail['taux_gating'] === null ? 'text-slate-500' : ($detail['taux_gating'] >= $detail['seuil_excellence'] ? 'text-emerald-700' : ($detail['taux_gating'] >= $detail['seuil_vigilance'] ? 'text-amber-600' : 'text-rose-700')) }} font-mono">
                        {{ $detail['taux_gating_formate'] }}
                    </span>
                    <span class="text-[10px] text-slate-500 block font-medium">
                        {{ $detail['valides_premier_essai'] }}/{{ $detail['total_evalues'] }} validés au 1<sup>er</sup> essai
                    </span>
                </div>
                <div class="h-10 w-px bg-slate-200"></div>
                <div class="text-center">
                    <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">En Remédiation</span>
                    <span class="text-2xl sm:text-3xl font-black text-amber-600 font-mono">
                        {{ $detail['en_remediation'] }}
                    </span>
                    <span class="text-[10px] text-slate-500 block font-medium">
                        Atelier d'étayage requis
                    </span>
                </div>
            </div>
        </div>
    </section>

    <!-- 5 Cartes Métriques Clés -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">

        {{-- 1. Taux de Gating Direct --}}
        <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-2xs hover:shadow-sm transition">
            <div class="flex items-start justify-between gap-2 mb-2">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Gating Direct (1<sup>er</sup> Essai)</span>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $detail['statut_badge'] }}">
                    {{ $detail['statut'] }}
                </span>
            </div>
            <div class="my-2 flex items-baseline gap-2">
                <span class="text-3xl font-black text-slate-900 font-mono tracking-tight">{{ $detail['taux_gating_formate'] }}</span>
                <span class="text-xs text-slate-400 font-medium">validés 1er jet</span>
            </div>
            <div class="pt-2.5 mt-2 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <span>Objectif cible :</span>
                <strong class="text-slate-800 font-mono">&ge; {{ $detail['seuil_excellence'] }}%</strong>
            </div>
        </div>

        {{-- 2. Validés au 1er Essai --}}
        <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-2xs hover:shadow-sm transition">
            <div class="flex items-start justify-between gap-2 mb-2">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Validés Direct</span>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    {{ $detail['valides_premier_essai'] }}/{{ $detail['total_evalues'] }}
                </span>
            </div>
            <div class="my-2 flex items-baseline gap-2">
                <span class="text-3xl font-black text-emerald-700 font-mono tracking-tight">
                    {{ $detail['valides_premier_essai'] }}
                </span>
                <span class="text-xs text-slate-400 font-medium">apprenants</span>
            </div>
            <div class="w-full bg-slate-100 rounded-full h-1.5 my-1.5 overflow-hidden">
                <div class="h-1.5 rounded-full transition-all duration-500 bg-emerald-500" 
                     style="width: {{ $detail['total_evalues'] > 0 ? min(100, max(0, round(($detail['valides_premier_essai'] / $detail['total_evalues']) * 100))) : 0 }}%"></div>
            </div>
            <div class="pt-1 mt-1 text-[11px] text-slate-400 truncate">
                Assimilation autonome immédiate
            </div>
        </div>

        {{-- 3. En Remédiation (Atelier Requis) --}}
        <div class="bg-white p-4 rounded-lg border {{ $detail['en_remediation'] > 0 ? 'border-amber-200 bg-amber-50/20' : 'border-slate-200' }} shadow-2xs hover:shadow-sm transition">
            <div class="flex items-start justify-between gap-2 mb-2">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">En Remédiation</span>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $detail['en_remediation'] > 0 ? 'bg-amber-100 text-amber-700 border border-amber-200' : 'bg-slate-100 text-slate-600' }}">
                    {{ $detail['en_remediation'] > 0 ? 'Atelier Requis' : 'Aucun' }}
                </span>
            </div>
            <div class="my-2 flex items-baseline gap-2">
                <span class="text-3xl font-black text-amber-600 font-mono tracking-tight">{{ $detail['en_remediation'] }}</span>
                <span class="text-xs text-slate-400 font-medium">en rattrapage</span>
            </div>
            <div class="pt-2.5 mt-2 border-t border-amber-100 flex items-center justify-between text-xs text-slate-500">
                <span>Accès Niveau 3 :</span>
                <strong class="text-amber-800 font-medium text-[11px]">Verrouillé temporairement</strong>
            </div>
        </div>

        {{-- 4. Validés Après Remédiation --}}
        <div class="bg-white p-4 rounded-lg border border-sky-200 bg-sky-50/20 shadow-2xs hover:shadow-sm transition">
            <div class="flex items-start justify-between gap-2 mb-2">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Validés Après Étayage</span>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-sky-100 text-sky-700 border border-sky-200">
                    2<sup>e</sup> Essai
                </span>
            </div>
            <div class="my-2 flex items-baseline gap-2">
                <span class="text-3xl font-black text-sky-700 font-mono tracking-tight">{{ $detail['valides_apres_remediation'] }}</span>
                <span class="text-xs text-slate-400 font-medium">rattrapés</span>
            </div>
            <div class="pt-2.5 mt-2 border-t border-sky-100 flex items-center justify-between text-xs text-slate-500">
                <span>Succès final cumulé :</span>
                <strong class="text-sky-900 font-mono text-xs">{{ $detail['taux_succes_final'] }}%</strong>
            </div>
        </div>

        {{-- 5. En Attente d'Évaluation --}}
        <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-2xs hover:shadow-sm transition">
            <div class="flex items-start justify-between gap-2 mb-2">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">En Attente Soutenance</span>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                    Non Évalués
                </span>
            </div>
            <div class="my-2 flex items-baseline gap-2">
                <span class="text-3xl font-black text-slate-700 font-mono tracking-tight">{{ $detail['non_evalues'] }}</span>
                <span class="text-xs text-slate-400 font-medium">sur {{ $detail['total_apprenants'] }}</span>
            </div>
            <div class="pt-2.5 mt-2 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500 truncate">
                <span>Complétude :</span>
                <strong class="text-slate-800 font-mono">{{ $detail['taux_evaluation'] }}% évalués</strong>
            </div>
        </div>

    </div>

    <!-- Histogramme de Répartition Didactique (4 Tranches) -->
    @php
        $totalAppr = max(1, $detail['total_apprenants']);
        $pctValide1er = round(($detail['tranches']['valide_1er'] / $totalAppr) * 100, 1);
        $pctValideRemediation = round(($detail['tranches']['valide_remediation'] / $totalAppr) * 100, 1);
        $pctEnRemediation = round(($detail['tranches']['en_remediation'] / $totalAppr) * 100, 1);
        $pctNonEvalue = round(($detail['tranches']['non_evalue'] / $totalAppr) * 100, 1);
    @endphp
    <section class="bg-white rounded-lg border border-slate-200 p-5 sm:p-6 shadow-xs">
        <div class="space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div>
                    <h2 class="text-sm sm:text-base font-bold text-slate-900 flex items-center gap-2">
                        <i class="fas fa-chart-column text-indigo-600"></i>
                        <span>Répartition des Réalisations par Tranche Didactique</span>
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Ventilation des {{ $detail['total_apprenants'] }} réalisations entre passage direct, étayage réussi, remédiation active et attente.
                    </p>
                </div>
                <div class="flex items-center gap-3 text-xs font-semibold text-slate-500 font-mono">
                    <span>Total Réalisations : <strong class="text-slate-800">{{ $detail['total_apprenants'] }}</strong></span>
                </div>
            </div>

            <!-- Barre Empilée Interactive -->
            <div class="w-full bg-slate-100 rounded-lg h-7 p-1 flex gap-1 overflow-hidden">
                @if($pctValide1er > 0)
                    <div class="bg-emerald-500 rounded h-full flex items-center justify-center text-white text-[11px] font-bold font-mono transition-all duration-300 hover:opacity-90"
                         style="width: {{ $pctValide1er }}%" 
                         title="Validés 1er essai : {{ $detail['tranches']['valide_1er'] }} ({{ $pctValide1er }}%)">
                        @if($pctValide1er >= 10) {{ $pctValide1er }}% @endif
                    </div>
                @endif
                @if($pctValideRemediation > 0)
                    <div class="bg-sky-500 rounded h-full flex items-center justify-center text-white text-[11px] font-bold font-mono transition-all duration-300 hover:opacity-90"
                         style="width: {{ $pctValideRemediation }}%" 
                         title="Validés après étayage : {{ $detail['tranches']['valide_remediation'] }} ({{ $pctValideRemediation }}%)">
                        @if($pctValideRemediation >= 10) {{ $pctValideRemediation }}% @endif
                    </div>
                @endif
                @if($pctEnRemediation > 0)
                    <div class="bg-amber-400 rounded h-full flex items-center justify-center text-slate-900 text-[11px] font-bold font-mono transition-all duration-300 hover:opacity-90"
                         style="width: {{ $pctEnRemediation }}%" 
                         title="En remédiation active : {{ $detail['tranches']['en_remediation'] }} ({{ $pctEnRemediation }}%)">
                        @if($pctEnRemediation >= 10) {{ $pctEnRemediation }}% @endif
                    </div>
                @endif
                @if($pctNonEvalue > 0)
                    <div class="bg-slate-300 rounded h-full flex items-center justify-center text-slate-700 text-[11px] font-bold font-mono transition-all duration-300 hover:opacity-90"
                         style="width: {{ $pctNonEvalue }}%" 
                         title="En attente de soutenance : {{ $detail['tranches']['non_evalue'] }} ({{ $pctNonEvalue }}%)">
                        @if($pctNonEvalue >= 10) {{ $pctNonEvalue }}% @endif
                    </div>
                @endif
            </div>

            <!-- Légende des 4 Tranches avec effectifs -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-2">
                <div class="flex items-center gap-2.5 p-2 rounded-lg bg-emerald-50/50 border border-emerald-100">
                    <span class="w-3 h-3 rounded-full bg-emerald-500 shrink-0"></span>
                    <div class="min-w-0">
                        <span class="text-[11px] font-bold text-slate-700 block truncate">Validé 1<sup>er</sup> Essai</span>
                        <span class="text-xs font-mono font-black text-emerald-800">{{ $detail['tranches']['valide_1er'] }}</span>
                        <span class="text-[10px] text-slate-400">({{ $pctValide1er }}%)</span>
                    </div>
                </div>

                <div class="flex items-center gap-2.5 p-2 rounded-lg bg-sky-50/50 border border-sky-100">
                    <span class="w-3 h-3 rounded-full bg-sky-500 shrink-0"></span>
                    <div class="min-w-0">
                        <span class="text-[11px] font-bold text-slate-700 block truncate">Validé 2<sup>e</sup> Essai</span>
                        <span class="text-xs font-mono font-black text-sky-800">{{ $detail['tranches']['valide_remediation'] }}</span>
                        <span class="text-[10px] text-slate-400">({{ $pctValideRemediation }}%)</span>
                    </div>
                </div>

                <div class="flex items-center gap-2.5 p-2 rounded-lg bg-amber-50/50 border border-amber-100">
                    <span class="w-3 h-3 rounded-full bg-amber-400 shrink-0"></span>
                    <div class="min-w-0">
                        <span class="text-[11px] font-bold text-slate-700 block truncate">En Remédiation</span>
                        <span class="text-xs font-mono font-black text-amber-800">{{ $detail['tranches']['en_remediation'] }}</span>
                        <span class="text-[10px] text-slate-400">({{ $pctEnRemediation }}%)</span>
                    </div>
                </div>

                <div class="flex items-center gap-2.5 p-2 rounded-lg bg-slate-50 border border-slate-200">
                    <span class="w-3 h-3 rounded-full bg-slate-400 shrink-0"></span>
                    <div class="min-w-0">
                        <span class="text-[11px] font-bold text-slate-700 block truncate">Non Évalué</span>
                        <span class="text-xs font-mono font-black text-slate-700">{{ $detail['tranches']['non_evalue'] }}</span>
                        <span class="text-[10px] text-slate-400">({{ $pctNonEvalue }}%)</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Tableau Roster Interactif des Apprenants -->
    <section class="bg-white rounded-lg border border-slate-200 shadow-xs overflow-hidden" 
             x-data="{
                filter: 'all',
                search: '',
                matches(appr) {
                    const matchesFilter = (this.filter === 'all') 
                        || (this.filter === 'valide_1er' && appr.statut_code === 'valide_1er')
                        || (this.filter === 'valide_remediation' && appr.statut_code === 'valide_remediation')
                        || (this.filter === 'en_remediation' && appr.statut_code === 'en_remediation')
                        || (this.filter === 'non_evalue' && appr.statut_code === 'non_evalue');

                    const q = this.search.toLowerCase().trim();
                    const matchesSearch = !q 
                        || appr.nom_complet.toLowerCase().includes(q) 
                        || appr.groupe_code.toLowerCase().includes(q)
                        || (appr.matricule && appr.matricule.toLowerCase().includes(q))
                        || appr.prototype_titre.toLowerCase().includes(q);

                    return matchesFilter && matchesSearch;
                }
             }">
        <!-- Barre de filtres et recherche du tableau -->
        <div class="p-4 sm:p-5 border-b border-slate-200 bg-slate-50/50 flex flex-col md:flex-row md:items-center justify-between gap-3">
            <div>
                <h2 class="text-sm sm:text-base font-bold text-slate-900 flex items-center gap-2">
                    <i class="fas fa-users-gear text-indigo-600"></i>
                    <span>Roster des Apprenants & Suivi du Gating</span>
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    Statut individuel de chaque apprenant face à l'épreuve de validation Live Coding Niveau 2.
                </p>
            </div>

            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5">
                <!-- Recherche instantanée -->
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" 
                           x-model="search" 
                           placeholder="Rechercher apprenant, matricule..." 
                           class="w-full sm:w-64 pl-8 pr-3 py-1.5 text-xs rounded-lg border border-slate-200 bg-white placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition shadow-2xs">
                </div>

                <!-- Filtres par Statut -->
                <div class="inline-flex rounded-lg border border-slate-200 bg-white p-0.5 shadow-2xs text-xs font-semibold text-slate-600">
                    <button @click="filter = 'all'" 
                            :class="filter === 'all' ? 'bg-indigo-600 text-white shadow-xs' : 'hover:text-slate-900'"
                            class="px-2.5 py-1 rounded-md transition">
                        Tous ({{ count($detail['apprenants']) }})
                    </button>
                    <button @click="filter = 'valide_1er'" 
                            :class="filter === 'valide_1er' ? 'bg-emerald-600 text-white shadow-xs' : 'hover:text-slate-900'"
                            class="px-2.5 py-1 rounded-md transition">
                        1<sup>er</sup> Essai ({{ $detail['tranches']['valide_1er'] }})
                    </button>
                    <button @click="filter = 'valide_remediation'" 
                            :class="filter === 'valide_remediation' ? 'bg-sky-600 text-white shadow-xs' : 'hover:text-slate-900'"
                            class="px-2.5 py-1 rounded-md transition">
                        2<sup>e</sup> Essai ({{ $detail['tranches']['valide_remediation'] }})
                    </button>
                    <button @click="filter = 'en_remediation'" 
                            :class="filter === 'en_remediation' ? 'bg-amber-500 text-slate-900 shadow-xs' : 'hover:text-slate-900'"
                            class="px-2.5 py-1 rounded-md transition">
                        Remédiation ({{ $detail['tranches']['en_remediation'] }})
                    </button>
                    <button @click="filter = 'non_evalue'" 
                            :class="filter === 'non_evalue' ? 'bg-slate-700 text-white shadow-xs' : 'hover:text-slate-900'"
                            class="px-2.5 py-1 rounded-md transition">
                        En Attente ({{ $detail['tranches']['non_evalue'] }})
                    </button>
                </div>
            </div>
        </div>

        <!-- Table des Apprenants -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[11px] uppercase tracking-wider text-slate-500 font-bold">
                        <th class="py-3 px-4">Apprenant</th>
                        <th class="py-3 px-4">Groupe</th>
                        <th class="py-3 px-4">Prototype Live Coding (N2)</th>
                        <th class="py-3 px-4 text-center">Tentatives</th>
                        <th class="py-3 px-4 text-center">1<sup>er</sup> Essai</th>
                        <th class="py-3 px-4">Date Validation</th>
                        <th class="py-3 px-4">Droit d'Accès Niveau 3</th>
                        <th class="py-3 px-4">Statut Gating</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($detail['apprenants'] as $appr)
                        <tr x-show="matches({{ json_encode($appr) }})" 
                            class="hover:bg-slate-50/80 transition {{ $appr['statut_code'] === 'en_remediation' ? 'bg-amber-50/20' : '' }}">
                            <!-- Apprenant -->
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-2.5 min-w-[200px]">
                                    <div class="w-7 h-7 rounded-full bg-slate-100 flex items-center justify-center font-bold text-slate-600 text-xs shrink-0 overflow-hidden border border-slate-200">
                                        @if($appr['avatar'])
                                            <img src="{{ asset($appr['avatar']) }}" alt="{{ $appr['nom_complet'] }}" class="w-full h-full object-cover">
                                        @else
                                            <span>{{ strtoupper(substr($appr['prenom'], 0, 1) . substr($appr['nom'], 0, 1)) }}</span>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-bold text-slate-900 truncate" title="{{ $appr['nom_complet'] }}">
                                            {{ $appr['nom_complet'] }}
                                        </div>
                                        @if($appr['matricule'])
                                            <span class="text-[10px] text-slate-400 font-mono block">
                                                {{ $appr['matricule'] }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <!-- Groupe -->
                            <td class="py-3 px-4 font-semibold text-slate-700">
                                <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-800 border border-slate-200 font-mono text-[11px]">
                                    {{ $appr['groupe_code'] }}
                                </span>
                            </td>

                            <!-- Prototype (N2) -->
                            <td class="py-3 px-4 text-slate-700 font-medium truncate max-w-[220px]" title="{{ $appr['prototype_titre'] }}">
                                <span class="text-slate-800">{{ $appr['prototype_titre'] }}</span>
                            </td>

                            <!-- Tentatives -->
                            <td class="py-3 px-4 text-center font-mono font-bold text-slate-700">
                                <span class="px-2 py-0.5 rounded {{ $appr['tentatives'] > 1 ? 'bg-amber-100 text-amber-800 border border-amber-200' : 'bg-slate-100 text-slate-700' }}">
                                    {{ $appr['tentatives'] }}
                                </span>
                            </td>

                            <!-- Statut 1er Essai -->
                            <td class="py-3 px-4 text-center">
                                @if($appr['premier_essai'] === true)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200" title="Validé dès le 1er essai">
                                        <i class="fas fa-check text-[9px]"></i>
                                        <span>Réussi</span>
                                    </span>
                                @elseif($appr['premier_essai'] === false)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200" title="Ajourné au 1er passage (remédiation)">
                                        <i class="fas fa-rotate-right text-[9px]"></i>
                                        <span>Ajourné</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500 border border-slate-200" title="Non encore évalué">
                                        <span>En attente</span>
                                    </span>
                                @endif
                            </td>

                            <!-- Date Validation -->
                            <td class="py-3 px-4 font-mono text-[11px] text-slate-600">
                                @if($appr['date_validation'])
                                    <span class="text-slate-800 font-semibold">{{ $appr['date_validation'] }}</span>
                                @else
                                    <span class="text-slate-400 italic">--</span>
                                @endif
                            </td>

                            <!-- Accès Niveau 3 -->
                            <td class="py-3 px-4">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold border {{ $appr['acces_badge'] }}">
                                    <i class="fas {{ $appr['is_debloque'] ? 'fa-unlock' : 'fa-lock' }} text-[9px]"></i>
                                    <span>{{ $appr['acces_projet'] }}</span>
                                </span>
                            </td>

                            <!-- Statut Badge -->
                            <td class="py-3 px-4">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $appr['statut_badge'] }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current opacity-75"></span>
                                    <span>{{ $appr['statut_label'] }}</span>
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-slate-400">
                                Aucune réalisation trouvée pour le périmètre sélectionné.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <!-- Benchmark Comparatif des Sessions de Formation (affectations N2) -->
    @if(count($detail['benchmark']) > 0)
        <section class="bg-white rounded-lg border border-slate-200 p-5 sm:p-6 shadow-xs space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-100 pb-3">
                <div>
                    <h2 class="text-sm sm:text-base font-bold text-slate-900 flex items-center gap-2">
                        <i class="fas fa-chalkboard-user text-indigo-600"></i>
                        <span>Benchmark Comparatif du Gating par Session de Formation</span>
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Performance comparée du taux de validation directe du prototype (N2) par session de formation
                    </p>
                </div>
                <span class="text-xs font-semibold text-slate-500 bg-slate-50 px-2.5 py-1 rounded border border-slate-200 font-mono">
                    {{ count($detail['benchmark']) }} sessions analysées
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-200/80 text-[11px] uppercase tracking-wider text-slate-500 font-bold">
                            <th class="py-2.5 px-3">Session de Formation</th>
                            <th class="py-2.5 px-3">Groupe</th>
                            <th class="py-2.5 px-3">Filière</th>
                            <th class="py-2.5 px-3 text-center">Effectif</th>
                            <th class="py-2.5 px-3 text-center" title="Apprenants ayant passé l'épreuve de Live Coding">Évalués</th>
                            <th class="py-2.5 px-3 text-center" title="Validés dès le 1er essai">1<sup>er</sup> Essai</th>
                            <th class="py-2.5 px-3 text-center" title="Apprenants ajournés en attente d'étayage">Remédiation</th>
                            <th class="py-2.5 px-3">Taux de Gating (R<sub>gating</sub>)</th>
                            <th class="py-2.5 px-3 text-center">Statut</th>
                            <th class="py-2.5 px-3 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs">
                        @foreach($detail['benchmark'] as $bg)
                            <tr class="hover:bg-indigo-50/30 transition">
                                <td class="py-3 px-3 font-bold text-slate-900">
                                    <div class="truncate max-w-[220px]" title="{{ $bg['session_titre'] ?? 'Session' }}">
                                        {{ $bg['session_titre'] ?? 'Session' }}
                                    </div>
                                    @if(!empty($bg['date_debut']))
                                        <span class="text-[10px] text-slate-400 font-mono block">Début : {{ $bg['date_debut'] }}</span>
                                    @endif
                                </td>
                                <td class="py-3 px-3 font-semibold text-slate-700">
                                    <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-800 border border-slate-200 font-mono text-[11px]">
                                        {{ $bg['groupe_code'] }}
                                    </span>
                                </td>
                                <td class="py-3 px-3 text-slate-500 font-semibold">
                                    {{ $bg['filiere_code'] }}
                                </td>
                                <td class="py-3 px-3 text-center font-mono text-slate-600">
                                    {{ $bg['total_apprenants'] }}
                                </td>
                                <td class="py-3 px-3 text-center font-mono font-bold text-slate-800">
                                    {{ $bg['total_evalues'] }}
                                </td>
                                <td class="py-3 px-3 text-center font-mono font-bold text-emerald-700">
                                    {{ $bg['valides_premier_essai'] }}
                                </td>
                                <td class="py-3 px-3 text-center font-mono font-bold text-amber-600">
                                    {{ $bg['en_remediation'] }}
                                </td>
                                <td class="py-3 px-3">
                                    <div class="flex items-center gap-2">
                                        <div class="flex-1 bg-slate-100 rounded-full h-1.5 max-w-[120px] overflow-hidden">
                                            <div class="h-1.5 rounded-full {{ ($bg['taux_gating'] ?? 0) >= 80 ? 'bg-emerald-500' : (($bg['taux_gating'] ?? 0) >= 65 ? 'bg-amber-400' : 'bg-rose-500') }}" 
                                                 style="width: {{ $bg['taux_gating'] ?? 0 }}%"></div>
                                        </div>
                                        <span class="font-mono font-bold text-xs text-slate-800">{{ $bg['taux_gating_formate'] }}</span>
                                    </div>
                                </td>
                                <td class="py-3 px-3 text-center">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $bg['statut_badge'] }}">
                                        {{ $bg['statut'] }}
                                    </span>
                                </td>
                                <td class="py-3 px-3 text-center">
                                    <a href="{{ route('admin.PkgTableauBord.indicateurs.gating', array_merge(request()->query(), ['groupe_id' => $bg['groupe_id']])) }}" 
                                       class="inline-flex items-center gap-1 px-2.5 py-1 text-[11px] font-bold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 rounded-lg transition shadow-2xs">
                                        <span>Filtrer</span>
                                        <i class="fas fa-arrow-right text-[10px]"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    @endif

</div>
@endsection
