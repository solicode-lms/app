@extends('layouts.admin-v2')

@section('title', 'Détail Indicateur 01 — Temps d\'Amorçage & Mise au Travail')
@section('page_title', 'Indicateur 01 : Temps d\'Amorçage & Mise au Travail')
@section('page_subtitle', 'Analyse détaillée du démarrage des sprints, dispersion temporelle et détection précoce du décrochage')

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

    <!-- En-tête contextuel & Fiche Pédagogique de l'Indicateur -->
    <section class="bg-white rounded-lg border border-slate-200 p-5 sm:p-6 shadow-xs space-y-4">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div class="space-y-1.5">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200 flex items-center gap-1.5">
                        <i class="fas fa-stopwatch text-indigo-500"></i>
                        <span>Indicateur Clé #01</span>
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                        Objectif SLA : &le; {{ $detail['seuil_optimal_formate'] }}
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
                    Temps d'Amorçage & Mise au Travail (T<sub>amorçage</sub>)
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 max-w-4xl leading-relaxed">
                    Mesure le délai exact entre le lancement officiel de la session et la première soumission d'une tâche pour chaque réalisation de projet. Un délai court (&le; 2h) témoigne d'un cadrage pédagogique assimilé, d'un environnement technique opérationnel et d'une entrée immédiate en posture de production.
                </p>
            </div>

            <!-- Jauge synthétique globale -->
            <div class="shrink-0 flex items-center gap-4 bg-slate-50 p-3.5 rounded-lg border border-slate-200">
                <div class="text-center">
                    <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">Taux d'Amorçage</span>
                    <span class="text-2xl sm:text-3xl font-black {{ $detail['taux_presence'] >= 85 ? 'text-emerald-700' : ($detail['taux_presence'] >= 65 ? 'text-indigo-700' : 'text-rose-700') }} font-mono">
                        {{ $detail['taux_presence'] }}%
                    </span>
                    <span class="text-[10px] text-slate-500 block font-medium">
                        {{ $detail['nb_amorces'] }}/{{ $detail['total_apprenants'] }} en séance
                    </span>
                </div>
                <div class="h-10 w-px bg-slate-200"></div>
                <div class="text-center">
                    <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">Moyenne (Présents)</span>
                    <span class="text-2xl sm:text-3xl font-black text-slate-900 font-mono">
                        {{ $detail['delai_moyen_formate'] }}
                    </span>
                    <span class="text-[10px] text-slate-500 block font-medium">
                        Médiane : {{ $detail['delai_median_formate'] }}
                    </span>
                </div>
            </div>
        </div>
    </section>

    <!-- 5 Cartes Métriques Clés -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">

        {{-- 1. Temps Moyen & Médian (Présents) --}}
        <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-2xs hover:shadow-sm transition">
            <div class="flex items-start justify-between gap-2 mb-2">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Délai Moyen (Présents)</span>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $detail['delai_moyen_minutes'] === null ? 'bg-slate-50 text-slate-700 border border-slate-200' : ($detail['delai_moyen_minutes'] <= $detail['seuil_optimal_minutes'] ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200') }}">
                    {{ $detail['delai_moyen_minutes'] === null ? 'En attente' : ($detail['delai_moyen_minutes'] <= $detail['seuil_optimal_minutes'] ? 'Dans le SLA' : 'Au-delà du SLA') }}
                </span>
            </div>
            <div class="my-2 flex items-baseline gap-2">
                <span class="text-3xl font-black text-slate-900 font-mono tracking-tight">{{ $detail['delai_moyen_formate'] }}</span>
                <span class="text-xs text-slate-400 font-medium">moyenne</span>
            </div>
            <div class="pt-2.5 mt-2 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <span>Médiane globale :</span>
                <strong class="text-slate-800 font-mono">{{ $detail['delai_median_formate'] }}</strong>
            </div>
        </div>

        {{-- 2. Taux d'Amorçage Présents en Séance --}}
        <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-2xs hover:shadow-sm transition">
            <div class="flex items-start justify-between gap-2 mb-2">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Amorcés en Séance</span>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    {{ $detail['nb_amorces'] }}/{{ $detail['total_apprenants'] }}
                </span>
            </div>
            <div class="my-2 flex items-baseline gap-2">
                <span class="text-3xl font-black text-slate-900 font-mono tracking-tight">{{ $detail['taux_presence'] }}%</span>
                <span class="text-xs text-slate-400 font-medium">présents</span>
            </div>
            <div class="w-full bg-slate-100 rounded-full h-1.5 my-1.5 overflow-hidden">
                <div class="h-1.5 rounded-full transition-all duration-500 {{ $detail['taux_presence'] >= 85 ? 'bg-emerald-500' : ($detail['taux_presence'] >= 65 ? 'bg-indigo-500' : 'bg-rose-500') }}" 
                     style="width: {{ min(100, max(0, $detail['taux_presence'])) }}%"></div>
            </div>
            <div class="pt-1 mt-1 text-[11px] text-slate-400 truncate">
                Tâche démarrée avant fin de séance
            </div>
        </div>

        {{-- 3. Démarrages avec Absence (Exclus de la moyenne) --}}
        <div class="bg-white p-4 rounded-lg border border-purple-200 bg-purple-50/20 shadow-2xs hover:shadow-sm transition">
            <div class="flex items-start justify-between gap-2 mb-2">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Démarrages avec Absence</span>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-purple-100 text-purple-700 border border-purple-200">
                    Exclus SLA
                </span>
            </div>
            <div class="my-2 flex items-baseline gap-2">
                <span class="text-3xl font-black text-purple-700 font-mono tracking-tight">{{ $detail['nb_demarrages_avec_absence'] }}</span>
                <span class="text-xs text-slate-400 font-medium">réalisations</span>
            </div>
            <div class="pt-2.5 mt-2 border-t border-purple-100 flex items-center justify-between text-xs text-slate-500">
                <span>Post-séance :</span>
                <strong class="text-purple-900 font-medium text-[11px]">Non comptés en T<sub>amorçage</sub></strong>
            </div>
        </div>

        {{-- 4. Record / Démarrage Éclair --}}
        <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-2xs hover:shadow-sm transition">
            <div class="flex items-start justify-between gap-2 mb-2">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Démarrage Record</span>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <i class="fas fa-bolt text-emerald-500 me-0.5"></i> Min
                </span>
            </div>
            <div class="my-2 flex items-baseline gap-2">
                <span class="text-3xl font-black text-slate-900 font-mono tracking-tight">{{ $detail['delai_min_formate'] }}</span>
                <span class="text-xs text-slate-400 font-medium">plus rapide</span>
            </div>
            <div class="pt-2.5 mt-2 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500 truncate">
                <span class="truncate">Par :</span>
                <strong class="text-slate-800 truncate ms-1" title="{{ $detail['apprenant_rapide']['nom_complet'] ?? 'Aucun' }}">
                    {{ $detail['apprenant_rapide']['nom_complet'] ?? 'Non disponible' }}
                </strong>
            </div>
        </div>

        {{-- 5. Réalisations Non Amorcées (Alerte Risque) --}}
        <div class="bg-white p-4 rounded-lg border {{ $detail['nb_non_amorces'] > 0 ? 'border-rose-200 bg-rose-50/20' : 'border-slate-200' }} shadow-2xs hover:shadow-sm transition">
            <div class="flex items-start justify-between gap-2 mb-2">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Non Amorcées</span>
                @if($detail['nb_non_amorces'] > 0)
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-800 border border-rose-300 animate-pulse">
                        En attente
                    </span>
                @else
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        100% Amorcé
                    </span>
                @endif
            </div>
            <div class="my-2 flex items-baseline gap-2">
                <span class="text-3xl font-black {{ $detail['nb_non_amorces'] > 0 ? 'text-rose-700' : 'text-emerald-700' }} font-mono tracking-tight">
                    {{ $detail['nb_non_amorces'] }}
                </span>
                <span class="text-xs text-slate-400 font-medium">réalisations</span>
            </div>
            <div class="pt-2.5 mt-2 border-t {{ $detail['nb_non_amorces'] > 0 ? 'border-rose-200' : 'border-slate-100' }} flex items-center justify-between text-xs text-slate-500">
                <span>Délai max observé :</span>
                <strong class="text-slate-800 font-mono">{{ $detail['delai_max_formate'] }}</strong>
            </div>
        </div>

    </div>

    <!-- Dispersion Temporelle & Distribution par Tranches -->
    @php
        $totalTranches = max(1, $detail['total_apprenants']);
        $pctEclair = round(($detail['tranches']['eclair'] / $totalTranches) * 100);
        $pctOptimal = round(($detail['tranches']['optimal'] / $totalTranches) * 100);
        $pctTardif = round(($detail['tranches']['tardif'] / $totalTranches) * 100);
        $pctAbsence = round(($detail['tranches']['absence'] / $totalTranches) * 100);
        $pctCritique = round(($detail['tranches']['critique'] / $totalTranches) * 100);
    @endphp
    <section class="bg-white rounded-lg border border-slate-200 p-5 sm:p-6 shadow-xs space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-100 pb-3">
            <div>
                <h2 class="text-sm sm:text-base font-bold text-slate-900 flex items-center gap-2">
                    <i class="fas fa-chart-column text-indigo-600"></i>
                    <span>Courbe de Dispersion Temporelle du Démarrage</span>
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    Distribution des {{ $detail['total_apprenants'] }} réalisations selon le créneau de mise au travail
                </p>
            </div>
            <span class="text-xs font-semibold text-slate-500 bg-slate-50 px-2.5 py-1 rounded border border-slate-200">
                SLA Référence : &le; 2h00
            </span>
        </div>

        <!-- Jauge segmentée proportionnelle -->
        <div class="space-y-2">
            <div class="flex w-full h-3 rounded-full overflow-hidden bg-slate-100 shadow-inner">
                @if($pctEclair > 0)
                    <div class="bg-emerald-500 transition-all duration-500" style="width: {{ $pctEclair }}%" title="Démarrage Éclair (< 1h) : {{ $detail['tranches']['eclair'] }} réalisations ({{ $pctEclair }}%)"></div>
                @endif
                @if($pctOptimal > 0)
                    <div class="bg-indigo-500 transition-all duration-500" style="width: {{ $pctOptimal }}%" title="Dans les temps (1h - 2h) : {{ $detail['tranches']['optimal'] }} réalisations ({{ $pctOptimal }}%)"></div>
                @endif
                @if($pctTardif > 0)
                    <div class="bg-amber-400 transition-all duration-500" style="width: {{ $pctTardif }}%" title="Démarrage Tardif en séance (2h - séance) : {{ $detail['tranches']['tardif'] }} réalisations ({{ $pctTardif }}%)"></div>
                @endif
                @if($pctAbsence > 0)
                    <div class="bg-purple-500 transition-all duration-500" style="width: {{ $pctAbsence }}%" title="Démarrage avec absence (post-séance) : {{ $detail['tranches']['absence'] }} réalisations ({{ $pctAbsence }}%)"></div>
                @endif
                @if($pctCritique > 0)
                    <div class="bg-rose-500 transition-all duration-500" style="width: {{ $pctCritique }}%" title="Non amorcée / en attente : {{ $detail['tranches']['critique'] }} réalisations ({{ $pctCritique }}%)"></div>
                @endif
            </div>

            <!-- Légende des 5 Tranches avec effectifs -->
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 pt-2">
                <div class="flex items-center gap-2.5 p-2 rounded-lg bg-emerald-50/50 border border-emerald-100">
                    <span class="w-3 h-3 rounded-full bg-emerald-500 shrink-0"></span>
                    <div class="min-w-0">
                        <span class="text-[11px] font-bold text-slate-700 block truncate">Éclair (&lt; 1h)</span>
                        <span class="text-xs font-mono font-black text-emerald-800">{{ $detail['tranches']['eclair'] }}</span>
                        <span class="text-[10px] text-slate-400">({{ $pctEclair }}%)</span>
                    </div>
                </div>

                <div class="flex items-center gap-2.5 p-2 rounded-lg bg-indigo-50/50 border border-indigo-100">
                    <span class="w-3 h-3 rounded-full bg-indigo-500 shrink-0"></span>
                    <div class="min-w-0">
                        <span class="text-[11px] font-bold text-slate-700 block truncate">Dans les temps</span>
                        <span class="text-xs font-mono font-black text-indigo-800">{{ $detail['tranches']['optimal'] }}</span>
                        <span class="text-[10px] text-slate-400">({{ $pctOptimal }}%)</span>
                    </div>
                </div>

                <div class="flex items-center gap-2.5 p-2 rounded-lg bg-amber-50/50 border border-amber-100">
                    <span class="w-3 h-3 rounded-full bg-amber-400 shrink-0"></span>
                    <div class="min-w-0">
                        <span class="text-[11px] font-bold text-slate-700 block truncate">Tardif en séance</span>
                        <span class="text-xs font-mono font-black text-amber-800">{{ $detail['tranches']['tardif'] }}</span>
                        <span class="text-[10px] text-slate-400">({{ $pctTardif }}%)</span>
                    </div>
                </div>

                <div class="flex items-center gap-2.5 p-2 rounded-lg bg-purple-50/50 border border-purple-100">
                    <span class="w-3 h-3 rounded-full bg-purple-500 shrink-0"></span>
                    <div class="min-w-0">
                        <span class="text-[11px] font-bold text-slate-700 block truncate">Avec absence</span>
                        <span class="text-xs font-mono font-black text-purple-800">{{ $detail['tranches']['absence'] }}</span>
                        <span class="text-[10px] text-slate-400">({{ $pctAbsence }}%)</span>
                    </div>
                </div>

                <div class="flex items-center gap-2.5 p-2 rounded-lg bg-rose-50/50 border border-rose-100">
                    <span class="w-3 h-3 rounded-full bg-rose-500 shrink-0"></span>
                    <div class="min-w-0">
                        <span class="text-[11px] font-bold text-slate-700 block truncate">Non amorcée</span>
                        <span class="text-xs font-mono font-black text-rose-800">{{ $detail['tranches']['critique'] }}</span>
                        <span class="text-[10px] text-slate-400">({{ $pctCritique }}%)</span>
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
                        || (this.filter === 'optimal' && (appr.statut_code === 'eclair' || appr.statut_code === 'optimal'))
                        || (this.filter === 'tardif' && appr.statut_code === 'tardif')
                        || (this.filter === 'absence' && appr.statut_code === 'demarrage_avec_absence')
                        || (this.filter === 'non_amorce' && appr.statut_code === 'non_amorce');

                    const q = this.search.toLowerCase().trim();
                    const matchesSearch = !q 
                        || appr.nom_complet.toLowerCase().includes(q) 
                        || appr.groupe_code.toLowerCase().includes(q)
                        || (appr.matricule && appr.matricule.toLowerCase().includes(q));

                    return matchesFilter && matchesSearch;
                }
             }">

        <!-- Barre d'outils du tableau : Onglets statuts & Recherche textuelle -->
        <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-3">
            <div class="flex items-center gap-1.5 flex-wrap">
                <button type="button" 
                        @click="filter = 'all'" 
                        :class="filter === 'all' ? 'bg-indigo-600 text-white shadow-2xs font-bold' : 'bg-slate-100 text-slate-700 hover:bg-slate-200 font-medium'"
                        class="px-3 py-1.5 rounded-lg text-xs transition">
                    Toutes ({{ count($detail['apprenants']) }} réalisations)
                </button>
                <button type="button" 
                        @click="filter = 'optimal'" 
                        :class="filter === 'optimal' ? 'bg-emerald-600 text-white shadow-2xs font-bold' : 'bg-slate-100 text-slate-700 hover:bg-slate-200 font-medium'"
                        class="px-3 py-1.5 rounded-lg text-xs transition">
                    Dans les temps (&le; 2h) ({{ $detail['tranches']['eclair'] + $detail['tranches']['optimal'] }} réalisations)
                </button>
                <button type="button" 
                        @click="filter = 'tardif'" 
                        :class="filter === 'tardif' ? 'bg-amber-600 text-white shadow-2xs font-bold' : 'bg-slate-100 text-slate-700 hover:bg-slate-200 font-medium'"
                        class="px-3 py-1.5 rounded-lg text-xs transition">
                    Tardives en séance ({{ $detail['tranches']['tardif'] }} réalisations)
                </button>
                <button type="button" 
                        @click="filter = 'absence'" 
                        :class="filter === 'absence' ? 'bg-purple-600 text-white shadow-2xs font-bold' : 'bg-slate-100 text-slate-700 hover:bg-slate-200 font-medium'"
                        class="px-3 py-1.5 rounded-lg text-xs transition">
                    Démarrage avec absence ({{ $detail['tranches']['absence'] }} réalisations)
                </button>
                <button type="button" 
                        @click="filter = 'non_amorce'" 
                        :class="filter === 'non_amorce' ? 'bg-rose-600 text-white shadow-2xs font-bold' : 'bg-slate-100 text-slate-700 hover:bg-slate-200 font-medium'"
                        class="px-3 py-1.5 rounded-lg text-xs transition">
                    Non amorcées ({{ $detail['nb_non_amorces'] }} réalisations)
                </button>
            </div>

            <!-- Champ de recherche réactif -->
            <div class="relative w-full md:w-72">
                <input type="text" 
                       x-model="search"
                       placeholder="Rechercher un apprenant, groupe..." 
                       class="w-full pl-9 pr-3 py-1.5 text-xs bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
            </div>
        </div>

        <!-- Table responsive -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/80 text-[11px] uppercase tracking-wider text-slate-500 font-bold">
                        <th class="py-3 px-4">Apprenant (Réalisation)</th>
                        <th class="py-3 px-4">Groupe</th>
                        <th class="py-3 px-4">Lancement & Fin Séance</th>
                        <th class="py-3 px-4">1<sup>re</sup> Tâche En Cours</th>
                        <th class="py-3 px-4">1<sup>re</sup> Soumission</th>
                        <th class="py-3 px-4">Délai d'Amorçage</th>
                        <th class="py-3 px-4">Statut & Évaluation</th>
                        <th class="py-3 px-4">1<sup>re</sup> Tâche Traitée</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($detail['apprenants'] as $appr)
                        <tr x-show="matches({{ json_encode($appr) }})" class="hover:bg-indigo-50/30 transition">
                            <!-- Apprenant -->
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-700 font-bold flex items-center justify-center text-xs shrink-0 border border-indigo-200">
                                        {{ strtoupper(substr($appr['prenom'], 0, 1) . substr($appr['nom'], 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <span class="font-bold text-slate-900 block truncate">{{ $appr['nom_complet'] }}</span>
                                        <span class="text-[10px] text-slate-400 font-mono">{{ $appr['matricule'] ?? '-' }}</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Groupe -->
                            <td class="py-3 px-4 font-semibold text-slate-700">
                                <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-800 border border-slate-200 font-mono text-[11px]">
                                    {{ $appr['groupe_code'] }}
                                </span>
                            </td>

                            <!-- Lancement Projet & Fin Séance -->
                            <td class="py-3 px-4 text-slate-500 font-mono text-[11px]">
                                <div>{{ $appr['date_lancement'] ?? '--' }}</div>
                                @if(!empty($appr['date_fin_seance']))
                                    <span class="text-[10px] text-slate-400 block" title="Heure de fin du créneau officiel de lancement">
                                        Séance &le; {{ substr($appr['date_fin_seance'], 11, 5) }}
                                    </span>
                                @endif
                            </td>

                            <!-- 1re Tâche En Cours -->
                            <td class="py-3 px-4 font-mono text-[11px]">
                                @if(!empty($appr['date_en_cours']))
                                    <span class="text-slate-800 font-semibold">{{ $appr['date_en_cours'] }}</span>
                                    @if($appr['statut_code'] === 'demarrage_avec_absence')
                                        <span class="text-[10px] text-purple-600 block font-medium" title="Démarrage {{ $appr['retard_post_seance_formate'] ?? '' }} après la séance">
                                            Post-séance {{ !empty($appr['retard_post_seance_formate']) ? '(+' . $appr['retard_post_seance_formate'] . ')' : '' }}
                                        </span>
                                    @endif
                                @else
                                    <span class="text-slate-400 italic">Non activée</span>
                                @endif
                            </td>

                            <!-- 1re Soumission -->
                            <td class="py-3 px-4 font-mono text-[11px]">
                                @if($appr['date_premiere_soumission'])
                                    <span class="text-slate-700 font-semibold">{{ $appr['date_premiere_soumission'] }}</span>
                                @else
                                    <span class="text-rose-500 font-bold italic">Aucune</span>
                                @endif
                            </td>

                            <!-- Délai d'Amorçage -->
                            <td class="py-3 px-4">
                                <span class="font-mono font-black text-sm {{ $appr['delai_minutes'] !== null ? ($appr['statut_code'] === 'demarrage_avec_absence' ? 'text-purple-700' : ($appr['delai_minutes'] <= 120 ? 'text-emerald-700' : ($appr['delai_minutes'] <= 240 ? 'text-amber-700' : 'text-rose-700'))) : 'text-slate-400' }}">
                                    {{ $appr['delai_formate'] }}
                                </span>
                                @if($appr['statut_code'] === 'demarrage_avec_absence')
                                    <span class="text-[10px] text-purple-600 block font-medium">Hors moyenne</span>
                                @endif
                            </td>

                            <!-- Statut Badge -->
                            <td class="py-3 px-4">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $appr['statut_badge'] }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current opacity-75"></span>
                                    <span>{{ $appr['statut_label'] }}</span>
                                </span>
                            </td>

                            <!-- 1re Tâche -->
                            <td class="py-3 px-4 text-slate-600 truncate max-w-[200px]" title="{{ $appr['premiere_tache'] }}">
                                <span class="text-slate-500">{{ $appr['premiere_tache'] }}</span>
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
                        <span>Benchmark Comparatif des Sessions de Formation</span>
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Performance comparée du démarrage des projets par session de formation (affectation N2)
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
                            <th class="py-2.5 px-3 text-center">Réalisations</th>
                            <th class="py-2.5 px-3 text-center" title="Apprenants ayant mis en cours leur 1re tâche pendant la séance">En Séance</th>
                            <th class="py-2.5 px-3 text-center" title="Apprenants ayant démarré après la séance (exclus de la moyenne)">Avec Absence</th>
                            <th class="py-2.5 px-3">Taux d'Amorçage</th>
                            <th class="py-2.5 px-3 text-end" title="Délai moyen d'amorçage calculé sur les apprenants présents">Délai Moyen (Présents)</th>
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
                                <td class="py-3 px-3 text-center font-mono font-bold text-emerald-700">
                                    {{ $bg['nb_amorces'] }}
                                </td>
                                <td class="py-3 px-3 text-center font-mono font-bold text-purple-700">
                                    {{ $bg['nb_demarrage_avec_absence'] ?? 0 }}
                                </td>
                                <td class="py-3 px-3">
                                    <div class="flex items-center gap-2">
                                        <div class="flex-1 bg-slate-100 rounded-full h-1.5 max-w-[120px] overflow-hidden">
                                            <div class="h-1.5 rounded-full {{ $bg['taux_amorce'] >= 85 ? 'bg-emerald-500' : ($bg['taux_amorce'] >= 65 ? 'bg-indigo-500' : 'bg-rose-500') }}" 
                                                 style="width: {{ $bg['taux_amorce'] }}%"></div>
                                        </div>
                                        <span class="font-mono font-bold text-xs text-slate-800">{{ $bg['taux_amorce'] }}%</span>
                                    </div>
                                </td>
                                <td class="py-3 px-3 text-end font-mono font-black text-slate-900">
                                    {{ $bg['delai_moyen_formate'] }}
                                </td>
                                <td class="py-3 px-3 text-center">
                                    <a href="{{ route('admin.PkgTableauBord.indicateurs.amorcage', array_merge(request()->query(), ['groupe_id' => $bg['groupe_id']])) }}" 
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

    <!-- Recommandations Pédagogiques Opérationnelles -->
    <section class="bg-white rounded-lg border border-slate-200 p-5 sm:p-6 shadow-xs space-y-4">
        <div class="border-b border-slate-100 pb-3">
            <h2 class="text-sm sm:text-base font-bold text-slate-900 flex items-center gap-2">
                <i class="fas fa-lightbulb text-amber-500"></i>
                <span>Recommandations Pédagogiques & Actions Préconisées</span>
            </h2>
            <p class="text-xs text-slate-500 mt-0.5">
                Guidage algorithmique Solicode LMS basé sur les données d'amorçage
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($detail['recommandations'] as $rec)
                <div class="p-4 rounded-lg border {{ $rec['classe'] }} flex items-start gap-3">
                    <div class="w-8 h-8 rounded-full bg-white/80 border border-current/20 flex items-center justify-center shrink-0">
                        <i class="fas {{ $rec['icone'] }} text-sm"></i>
                    </div>
                    <div class="space-y-1">
                        <h3 class="text-xs font-bold uppercase tracking-wider">{{ $rec['titre'] }}</h3>
                        <p class="text-xs leading-relaxed opacity-90">{{ $rec['message'] }}</p>
                    </div>
                </div>
            @endforeach

            <div class="p-4 rounded-lg border border-slate-200 bg-slate-50 flex items-start gap-3">
                <div class="w-8 h-8 rounded-full bg-white border border-slate-200 flex items-center justify-center shrink-0 text-indigo-600">
                    <i class="fas fa-chalkboard-user text-sm"></i>
                </div>
                <div class="space-y-1">
                    <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Bonne Pratique Solicode</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        L'épreuve Live Coding ou la première tâche de N2 doit être entamée dans les 120 minutes suivant l'attribution. Au-delà de ce cap, planifier un rituel de synchronisation pour s'assurer que les accès Git et la compréhension du cahier des charges sont validés.
                    </p>
                </div>
            </div>
        </div>
    </section>

</div>
@endsection
