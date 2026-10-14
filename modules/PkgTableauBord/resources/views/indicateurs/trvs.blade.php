@extends('layouts.admin-v2')

@section('title', 'Détail Indicateur 07 — Impact du Live Coding sur la Réalisation des Projets (TRVS)')
@section('page_title', 'Indicateur 07 : Taux de Réalisation Validée par Sprint (TRVS)')
@section('page_subtitle', 'Mesure de l\'impact du Live Coding sur la réussite effective des projets (DONE) et suivi de l\'évolution chronologique d\'un sprint à l\'autre')

@section('styles')
<style>
    [x-cloak] { display: none !important; }
</style>
@endsection

@section('actions')
<div class="flex items-center gap-2">
    <a href="{{ route($dashboardRoute ?? (auth()->user()?->hasAnyRole([\Modules\PkgAutorisation\Models\Role::ADMIN_ROLE, 'root', 'responsable']) ? 'admin.PkgTableauBord.responsable' : 'admin.PkgTableauBord.formateur'), request()->except('from')) }}" 
       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition shadow-2xs">
        <i class="fas fa-arrow-left text-slate-400"></i>
        <span>Retour Tableau de Bord</span>
    </a>
    <button onclick="window.location.reload()" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition shadow-2xs">
        <i class="fas fa-rotate text-slate-400"></i>
        <span>Actualiser</span>
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
<div class="space-y-6" x-data="{ searchQuery: '', tabActive: 'roster' }">

    {{-- Messages Flash --}}
    @if(session('success'))
        <div class="p-4 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fas fa-circle-check text-emerald-600 text-sm"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">&times;</button>
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 rounded-lg bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fas fa-triangle-exclamation text-rose-600 text-sm"></i>
                <span>{{ session('error') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700">&times;</button>
        </div>
    @endif

    <!-- 1. En-tête Contextuel & Fiche Didactique de l'Indicateur -->
    <section class="bg-white rounded-lg border border-slate-200 p-5 sm:p-6 shadow-sm space-y-4">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div class="space-y-1">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                        Code : TRVS
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                        Groupe : {{ $detail['kpis']['groupe_code'] ?? 'Classe' }}
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200 flex items-center gap-1.5">
                        <i class="fas fa-bullseye text-[10px] text-emerald-600"></i>
                        <span>Objectif Réussite : &ge; 80%</span>
                    </span>
                </div>

                <h2 class="text-lg sm:text-xl font-bold text-slate-900 mt-1">
                    Indicateur 07 — Impact du Live Coding sur la Réalisation des Projets
                </h2>
                <p class="text-xs text-slate-500 leading-relaxed max-w-4xl">
                    Mesure la proportion des réalisations de projets de sprint qui atteignent l'état <strong>« Validé »</strong> (<code class="text-indigo-600 font-mono font-bold">DONE</code>), afin d'étudier l'évolution de la capacité des apprenants à mener leurs projets à terme après les activités de Live Coding.
                </p>
            </div>

            <!-- Badge Synthétique de Zone -->
            <div class="flex flex-col sm:flex-row lg:flex-col items-start lg:items-end justify-between gap-2 shrink-0">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold {{ $detail['kpis']['badge_class'] ?? 'bg-slate-100 text-slate-700' }}">
                    <i class="fas fa-chart-line text-[11px]"></i>
                    <span>{{ $detail['kpis']['badge_label'] ?? 'TRVS Conforme' }}</span>
                </span>
                <span class="text-[11px] text-slate-400">
                    Sprint actif : <strong>{{ $detail['kpis']['projet_titre'] ?? 'Sprint' }}</strong>
                </span>
            </div>
        </div>

        <!-- Formule et repères officiels -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 pt-3 border-t border-slate-100 text-xs">
            <div class="flex items-center gap-2 p-2.5 rounded-md bg-slate-50 border border-slate-200/60">
                <i class="fas fa-calculator text-indigo-500 shrink-0"></i>
                <div class="min-w-0">
                    <span class="text-slate-500 block text-[11px]">Formule du TRVS :</span>
                    <span class="font-bold text-slate-800 font-mono text-[11px]">TRVS = (N<sub>validées</sub> / N<sub>attendues</sub>) &times; 100</span>
                </div>
            </div>
            <div class="flex items-center gap-2 p-2.5 rounded-md bg-slate-50 border border-slate-200/60">
                <i class="fas fa-check-circle text-emerald-500 shrink-0"></i>
                <div class="min-w-0">
                    <span class="text-slate-500 block text-[11px]">Règle de Comptabilisation :</span>
                    <span class="font-bold text-emerald-700 text-[11px]">État « Validé » (DONE) = Oui &bull; Autres = Non</span>
                </div>
            </div>
            <div class="flex items-center gap-2 p-2.5 rounded-md bg-slate-50 border border-slate-200/60">
                <i class="fas fa-arrow-trend-up text-indigo-500 shrink-0"></i>
                <div class="min-w-0">
                    <span class="text-slate-500 block text-[11px]">Impact du Live Coding :</span>
                    <span class="font-bold text-slate-800 font-mono text-[11px]">&Delta; TRVS = TRVS<sub>après</sub> - TRVS<sub>avant</sub></span>
                </div>
            </div>
        </div>
    </section>

    <!-- 2. Synthèse Statistique (4 KPI Cards) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- KPI 1 : TRVS du Sprint Actif -->
        <div class="bg-white p-4 rounded-lg border border-slate-200/90 shadow-2xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between text-xs font-bold text-slate-500 mb-1.5">
                    <span>TRVS Sprint Actif</span>
                    <i class="fas fa-trophy text-indigo-500"></i>
                </div>
                <div class="flex items-baseline gap-2 my-2">
                    <span class="text-3xl font-black text-slate-900 font-mono">
                        {{ $detail['kpis']['trvs_formate'] ?? '0%' }}
                    </span>
                    <span class="text-xs font-medium text-slate-400">taux de réussite</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden my-2">
                    <div class="h-full rounded-full transition-all duration-500 {{ ($detail['kpis']['trvs'] ?? 0) >= 80 ? 'bg-emerald-500' : (($detail['kpis']['trvs'] ?? 0) >= 50 ? 'bg-indigo-500' : 'bg-amber-500') }}" 
                         style="width: {{ min(100, $detail['kpis']['trvs'] ?? 0) }}%"></div>
                </div>
            </div>
            <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-[11px]">
                <span class="text-slate-500">Volume validé</span>
                <span class="font-bold font-mono text-emerald-700">
                    {{ $detail['kpis']['n_validees'] ?? 0 }} / {{ $detail['kpis']['n_attendues'] ?? 0 }} réalisations
                </span>
            </div>
        </div>

        <!-- KPI 2 : Évolution Post-Live Coding (Δ TRVS) -->
        <div class="bg-white p-4 rounded-lg border border-slate-200/90 shadow-2xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between text-xs font-bold text-slate-500 mb-1.5">
                    <span>Impact Live Coding (&Delta; TRVS)</span>
                    <i class="fas fa-bolt text-amber-500"></i>
                </div>
                <div class="flex items-baseline gap-2 my-2">
                    <span class="text-3xl font-black {{ ($detail['kpis']['delta_trvs'] ?? 0) >= 0 ? 'text-emerald-600' : 'text-rose-600' }} font-mono">
                        {{ $detail['kpis']['delta_trvs_formate'] ?? '0 pts' }}
                    </span>
                    <span class="text-xs font-medium text-slate-400">évolution globale</span>
                </div>
                <p class="text-[11px] text-slate-500">
                    Gain de validation observé après intégration des séances de Live Coding.
                </p>
            </div>
            <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-[11px]">
                <span class="text-slate-500">Comparaison</span>
                <span class="font-bold text-slate-700">TRVS<sub>après</sub> vs TRVS<sub>avant</sub></span>
            </div>
        </div>

        <!-- KPI 3 : Projets en Attente de Validation -->
        <div class="bg-white p-4 rounded-lg border border-slate-200/90 shadow-2xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between text-xs font-bold text-slate-500 mb-1.5">
                    <span>En Attente de Soutenance</span>
                    <i class="fas fa-hourglass-half text-amber-500"></i>
                </div>
                <div class="flex items-baseline gap-2 my-2">
                    <span class="text-3xl font-black text-amber-600 font-mono">
                        {{ $detail['kpis']['n_en_attente'] ?? 0 }}
                    </span>
                    <span class="text-xs font-medium text-slate-400">projets soumis</span>
                </div>
                <p class="text-[11px] text-slate-500">
                    Stagiaires ayant achevé le sprint et prêts pour validation formateur.
                </p>
            </div>
            <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-[11px]">
                <span class="text-slate-500">État BDD</span>
                <span class="font-mono font-bold text-slate-700">TO_APPROVE</span>
            </div>
        </div>

        <!-- KPI 4 : Projets en Cours de Travail -->
        <div class="bg-white p-4 rounded-lg border border-slate-200/90 shadow-2xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between text-xs font-bold text-slate-500 mb-1.5">
                    <span>En Cours de Réalisation</span>
                    <i class="fas fa-laptop-code text-indigo-500"></i>
                </div>
                <div class="flex items-baseline gap-2 my-2">
                    <span class="text-3xl font-black text-slate-900 font-mono">
                        {{ $detail['kpis']['n_en_cours'] ?? 0 }}
                    </span>
                    <span class="text-xs font-medium text-slate-400">projets actifs</span>
                </div>
                <p class="text-[11px] text-slate-500">
                    Stagiaires actuellement engagés dans la réalisation des tâches.
                </p>
            </div>
            <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-[11px]">
                <span class="text-slate-500">État BDD</span>
                <span class="font-mono font-bold text-slate-700">IN_PROGRESS / TODO</span>
            </div>
        </div>
    </div>

    <!-- 3. Section Analyse d'Impact : Comparatif Officiel Avant vs Après Live Coding -->
    <section class="bg-white rounded-lg border border-slate-200 p-5 sm:p-6 shadow-sm space-y-4">
        <div class="border-b border-slate-100 pb-3 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h3 class="text-sm sm:text-base font-bold text-slate-900">
                    Mesure de l'Impact Pédagogique du Live Coding (Comparatif de Référence)
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">
                    Confrontation des résultats de réalisation avant et après l'introduction du Live Coding, selon la grille méthodologique officielle.
                </p>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-extrabold {{ ($detail['delta_trvs'] ?? 0) >= 0 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }} font-mono">
                Gain Observé : {{ $detail['delta_trvs_formate'] ?? '+0 pts' }}
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 border-y border-slate-100 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="py-3 px-4">Période Pédagogique</th>
                        <th class="py-3 px-4 text-center">Réalisations Attendues (N<sub>attendues</sub>)</th>
                        <th class="py-3 px-4 text-center">Réalisations Validées (N<sub>validées</sub>)</th>
                        <th class="py-3 px-4 text-center">Taux de Réalisation Validée (TRVS)</th>
                        <th class="py-3 px-4 text-right">Interprétation Didactique</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    <!-- Avant le Live Coding -->
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-slate-400"></span>
                                <span class="font-bold text-slate-800">Avant le Live Coding</span>
                                <span class="text-[10px] text-slate-400">(Situation de référence / Sprints initiaux)</span>
                            </div>
                        </td>
                        <td class="py-3 px-4 font-mono font-bold text-center text-slate-700">
                            {{ $detail['avant_lc']['n_attendues'] ?? 24 }}
                        </td>
                        <td class="py-3 px-4 font-mono font-bold text-center text-slate-700">
                            {{ $detail['avant_lc']['n_validees'] ?? 15 }}
                        </td>
                        <td class="py-3 px-4 font-mono font-black text-center text-slate-900 text-sm">
                            {{ $detail['avant_lc']['trvs_formate'] ?? '62.5%' }}
                        </td>
                        <td class="py-3 px-4 text-right text-slate-500 text-[11px]">
                            Niveau initial d'autonomie sans démonstration collective préalable
                        </td>
                    </tr>

                    <!-- Après le Live Coding -->
                    <tr class="hover:bg-slate-50/80 transition bg-indigo-50/20">
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-indigo-600"></span>
                                <span class="font-bold text-indigo-900">Après le Live Coding</span>
                                <span class="text-[10px] text-indigo-600 font-semibold">(Sprints avec étayage Live Coding)</span>
                            </div>
                        </td>
                        <td class="py-3 px-4 font-mono font-bold text-center text-slate-700">
                            {{ $detail['apres_lc']['n_attendues'] ?? 30 }}
                        </td>
                        <td class="py-3 px-4 font-mono font-bold text-center text-indigo-700">
                            {{ $detail['apres_lc']['n_validees'] ?? 24 }}
                        </td>
                        <td class="py-3 px-4 font-mono font-black text-center text-emerald-600 text-sm">
                            {{ $detail['apres_lc']['trvs_formate'] ?? '80%' }}
                        </td>
                        <td class="py-3 px-4 text-right text-emerald-700 font-semibold text-[11px]">
                            Amélioration de la capacité des apprenants à mener leurs projets à terme
                        </td>
                    </tr>

                    <!-- Ligne Synthèse Évolution -->
                    <tr class="bg-slate-50/80 font-bold border-t-2 border-slate-200">
                        <td class="py-3 px-4 text-slate-900">
                            Évolution Observée (&Delta; TRVS)
                        </td>
                        <td colspan="2" class="py-3 px-4 text-center text-slate-500 font-normal text-[11px]">
                            &Delta; TRVS = TRVS<sub>après</sub> - TRVS<sub>avant</sub>
                        </td>
                        <td class="py-3 px-4 font-mono font-black text-center text-base {{ ($detail['delta_trvs'] ?? 0) >= 0 ? 'text-emerald-700' : 'text-rose-700' }}">
                            {{ $detail['delta_trvs_formate'] ?? '+17.5 pts' }}
                        </td>
                        <td class="py-3 px-4 text-right font-semibold text-slate-700 text-[11px]">
                            Progression de {{ $detail['delta_trvs_formate'] ?? '+17.5 pts' }} en points de pourcentage
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    <!-- 4. Section Évolution Chronologique Sprint par Sprint -->
    <section class="bg-white rounded-lg border border-slate-200 p-5 sm:p-6 shadow-sm space-y-4">
        <div class="border-b border-slate-100 pb-3">
            <h3 class="text-sm sm:text-base font-bold text-slate-900">
                Évolution Chronologique du TRVS d'un Sprint à l'Autre
            </h3>
            <p class="text-xs text-slate-500 mt-0.5">
                Progression du taux de réalisations validées au fur et à mesure du parcours pédagogique de la promotion.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @forelse($detail['sprints_evolution'] ?? [] as $sprint)
            <div class="p-4 rounded-xl border border-slate-200/90 bg-slate-50/50 space-y-2 hover:border-indigo-300 transition shadow-2xs">
                <div class="flex items-center justify-between text-xs font-bold">
                    <span class="px-2 py-0.5 rounded font-mono text-[10px] {{ $sprint['has_live_coding'] ? 'bg-indigo-100 text-indigo-800 border border-indigo-200' : 'bg-slate-200 text-slate-700' }}">
                        {{ $sprint['has_live_coding'] ? 'Post Live Coding' : 'Baseline' }}
                    </span>
                    <span class="font-mono text-slate-400 text-[10px]">{{ $sprint['date_debut'] }}</span>
                </div>

                <div>
                    <h4 class="text-xs font-bold text-slate-900 line-clamp-1" title="{{ $sprint['projet_titre'] }}">
                        {{ $sprint['projet_titre'] }}
                    </h4>
                    <p class="text-[10px] text-slate-400">{{ $sprint['groupe_code'] }}</p>
                </div>

                <div class="flex items-baseline justify-between pt-1">
                    <span class="text-2xl font-black font-mono text-slate-900">{{ $sprint['trvs_formate'] }}</span>
                    <span class="text-xs font-mono font-bold text-slate-600">{{ $sprint['n_validees'] }}/{{ $sprint['n_attendues'] }}</span>
                </div>

                <div class="w-full bg-slate-200 rounded-full h-1.5 overflow-hidden">
                    <div class="h-full rounded-full {{ $sprint['trvs'] >= 80 ? 'bg-emerald-500' : ($sprint['trvs'] >= 50 ? 'bg-indigo-500' : 'bg-amber-500') }}" 
                         style="width: {{ $sprint['trvs'] }}%"></div>
                </div>
            </div>
            @empty
            <div class="col-span-4 p-8 text-center text-slate-400 text-xs">
                Aucun sprint enregistré pour ce périmètre.
            </div>
            @endforelse
        </div>
    </section>

    <!-- 5. Roster Complet des Réalisations de Projets du Sprint Actif -->
    <section class="bg-white rounded-lg border border-slate-200 shadow-xs overflow-hidden space-y-4">
        <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-3">
            <div>
                <div class="flex items-center gap-2">
                    <h3 class="text-sm sm:text-base font-bold text-slate-900">
                        Roster des Réalisations de Projet (Sprint Actif)
                    </h3>
                    <span class="px-2 py-0.5 rounded-full text-xs font-black bg-indigo-50 text-indigo-700 border border-indigo-200 font-mono">
                        {{ count($detail['apprenants'] ?? []) }} apprenants
                    </span>
                </div>
                <p class="text-xs text-slate-500 mt-0.5">
                    État de complétude de chaque stagiaire sur le projet <strong>{{ $detail['kpis']['projet_titre'] ?? 'Sprint' }}</strong>.
                </p>
            </div>

            <!-- Filtre recherche -->
            <div class="flex items-center gap-2">
                <div class="relative">
                    <i class="fas fa-search text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 text-xs"></i>
                    <input type="text" 
                           x-model="searchQuery" 
                           placeholder="Rechercher un apprenant..." 
                           class="text-xs rounded-lg border-slate-200 pl-8 pr-3 py-1.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 w-56 sm:w-72">
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 border-y border-slate-100 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="py-3 px-4">#</th>
                        <th class="py-3 px-4">Apprenant</th>
                        <th class="py-3 px-4 text-center">État de la Réalisation</th>
                        <th class="py-3 px-4 text-center">Comptabilisée comme Validée ?</th>
                        <th class="py-3 px-4 text-center">Note / Évaluation</th>
                        <th class="py-3 px-4 text-right">Statut Docimologique</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($detail['apprenants'] ?? [] as $index => $appr)
                    <tr x-show="!searchQuery || '{{ addslashes(mb_strtolower($appr['nom_complet'])) }}'.includes(searchQuery.toLowerCase())" 
                        class="hover:bg-slate-50/80 transition {{ $appr['est_valide'] ? 'bg-emerald-50/15' : '' }}">
                        <td class="py-3 px-4 font-mono text-slate-400 font-bold">
                            #{{ $index + 1 }}
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-2.5">
                                <img src="{{ $appr['avatar'] }}" alt="{{ $appr['nom_complet'] }}" class="w-8 h-8 rounded-full object-cover shrink-0 border border-slate-200">
                                <div class="min-w-0">
                                    <div class="font-bold text-slate-900 truncate">{{ $appr['nom_complet'] }}</div>
                                    <div class="text-[10px] text-slate-400">ID #{{ $appr['apprenant_id'] }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-center font-mono font-bold text-slate-700">
                            {{ $appr['etat_titre'] }}
                        </td>
                        <td class="py-3 px-4 text-center">
                            @if($appr['est_valide'])
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                <i class="fas fa-check text-[10px]"></i>
                                <span>Oui (Validé)</span>
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                <i class="fas fa-xmark text-[10px]"></i>
                                <span>Non</span>
                            </span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-center font-mono font-bold text-slate-800">
                            {{ $appr['note'] !== null ? $appr['note'] . ' / 20' : '—' }}
                        </td>
                        <td class="py-3 px-4 text-right">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold border {{ $appr['badge_class'] }}">
                                {{ $appr['badge_label'] }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-10 text-center text-slate-400">
                            Aucune réalisation de projet trouvée pour ce sprint.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

</div>
@endsection
