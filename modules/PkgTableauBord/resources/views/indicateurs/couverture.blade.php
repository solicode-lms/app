@extends('layouts.admin-v2')

@section('title', 'Détail Indicateur 08 — Progression des Compétences du Référentiel National (ICP)')
@section('page_title', 'Indicateur 08 : Progression des Compétences & Couverture du Référentiel National')
@section('page_subtitle', 'Mesure docimologique dynamique confrontant progression réelle et progression idéale (progression_ideal_cache) pour éradiquer les faux positifs temporels')

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
<div class="space-y-6" x-data="{ searchQuery: '', tabActive: 'competences' }">

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
                        Filière : {{ $detail['kpis']['filiere_nom'] ?? 'Développement Digital' }}
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                        Groupe : {{ $detail['kpis']['groupe_code'] ?? 'Classe' }}
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200 flex items-center gap-1.5">
                        <i class="fas fa-certificate text-[10px] text-emerald-600"></i>
                        <span>Rythme Cible : &ge; 95% du calendrier idéal</span>
                    </span>
                </div>

                <h2 class="text-lg sm:text-xl font-bold text-slate-900 mt-1">
                    Indicateur 08 — Progression dans le Référentiel National (ICP Temporel)
                </h2>
                <p class="text-xs text-slate-500 leading-relaxed max-w-4xl">
                    Évalue la progression réelle de la promotion (<code class="text-indigo-600 font-mono font-bold">progression_cache</code>) en la confrontant à la progression idéale attendue (<code class="text-emerald-700 font-mono font-bold">progression_ideal_cache</code>). En début/cours d'année, un niveau &lt; 85% est normal : seules les compétences présentant un décrochage significatif face au calendrier sont qualifiées d'impasses.
                </p>
            </div>

            <!-- Badge Synthétique de Zone Didactique -->
            <div class="flex flex-col sm:flex-row lg:flex-col items-start lg:items-end justify-between gap-2 shrink-0">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold {{ $detail['kpis']['badge_class'] ?? 'bg-slate-100 text-slate-700' }}">
                    <i class="fas fa-award text-[11px]"></i>
                    <span>{{ $detail['kpis']['badge_label'] ?? 'Trajectoire Régulière' }}</span>
                </span>
                <span class="text-[11px] text-slate-400">
                    Cohorte : <strong>{{ $detail['kpis']['effectif'] ?? 0 }} apprenants</strong> &bull; <strong>{{ count($detail['competences'] ?? []) }} compétences</strong>
                </span>
            </div>
        </div>

        <!-- Formule et repères didactiques temporels -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 pt-3 border-t border-slate-100 text-xs">
            <div class="flex items-center gap-2 p-2.5 rounded-md bg-slate-50 border border-slate-200/60">
                <i class="fas fa-calculator text-indigo-500 shrink-0"></i>
                <div class="min-w-0">
                    <span class="text-slate-500 block text-[11px]">Taux de Rythme Temporel :</span>
                    <span class="font-bold text-slate-800 font-mono text-[11px]">Rythme = (Progression / Progression Idéale) &times; 100</span>
                </div>
            </div>
            <div class="flex items-center gap-2 p-2.5 rounded-md bg-slate-50 border border-slate-200/60">
                <i class="fas fa-bullseye text-emerald-500 shrink-0"></i>
                <div class="min-w-0">
                    <span class="text-slate-500 block text-[11px]">Trajectoire Optimale :</span>
                    <span class="font-bold text-emerald-700 text-[11px]">Rythme &ge; 95% ou &Delta; &ge; -5 pts</span>
                </div>
            </div>
            <div class="flex items-center gap-2 p-2.5 rounded-md bg-slate-50 border border-slate-200/60">
                <i class="fas fa-triangle-exclamation text-amber-500 shrink-0"></i>
                <div class="min-w-0">
                    <span class="text-slate-500 block text-[11px]">Impasse Didactique Réelle :</span>
                    <span class="font-bold text-rose-700 text-[11px]">Rythme &lt; 60% face à l'idéal (&ge; 25%)</span>
                </div>
            </div>
        </div>
    </section>

    <!-- 2. Synthèse Statistique (4 KPI Cards Temporelles) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- KPI 1 : Taux de Rythme Cohorte -->
        <div class="bg-white p-4 rounded-lg border border-slate-200/90 shadow-2xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between text-xs font-bold text-slate-500 mb-1.5">
                    <span>Taux de Rythme Global</span>
                    <i class="fas fa-gauge-high text-indigo-500"></i>
                </div>
                <div class="flex items-baseline gap-2 my-2">
                    <span class="text-3xl font-black text-slate-900 font-mono">
                        {{ $detail['kpis']['taux_rythme_formate'] ?? '100%' }}
                    </span>
                    <span class="text-xs font-medium text-slate-400">adhérence au calendrier</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden my-2">
                    <div class="h-full rounded-full transition-all duration-500 {{ ($detail['kpis']['taux_rythme'] ?? 0) >= 95 ? 'bg-emerald-500' : (($detail['kpis']['taux_rythme'] ?? 0) >= 80 ? 'bg-indigo-500' : 'bg-amber-500') }}" 
                         style="width: {{ min(100, $detail['kpis']['taux_rythme'] ?? 0) }}%"></div>
                </div>
            </div>
            <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-[11px]">
                <span class="text-slate-500">Écart au calendrier</span>
                <span class="font-bold font-mono {{ ($detail['kpis']['delta'] ?? 0) >= 0 ? 'text-emerald-700' : 'text-slate-700' }}">
                    {{ $detail['kpis']['delta_formate'] ?? '0 pts' }}
                </span>
            </div>
        </div>

        <!-- KPI 2 : Progression Réelle vs Idéale -->
        <div class="bg-white p-4 rounded-lg border border-slate-200/90 shadow-2xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between text-xs font-bold text-slate-500 mb-1.5">
                    <span>Progression Réelle vs Idéale</span>
                    <i class="fas fa-chart-line text-emerald-500"></i>
                </div>
                <div class="flex items-baseline gap-2 my-2">
                    <span class="text-3xl font-black text-emerald-600 font-mono">
                        {{ $detail['kpis']['progression_reelle_moyenne'] ?? 0 }}%
                    </span>
                    <span class="text-xs font-medium text-slate-400">/ {{ $detail['kpis']['progression_ideale_moyenne'] ?? 0 }}% attendu</span>
                </div>
                <div class="relative w-full bg-slate-100 rounded-full h-1.5 overflow-hidden my-2">
                    <div class="h-full rounded-full bg-emerald-500 transition-all duration-500" 
                         style="width: {{ min(100, $detail['kpis']['progression_reelle_moyenne'] ?? 0) }}%"></div>
                    @if(($detail['kpis']['progression_ideale_moyenne'] ?? 0) > 0)
                    <div class="absolute top-0 bottom-0 w-0.5 bg-slate-900 z-10" 
                         style="left: {{ min(100, $detail['kpis']['progression_ideale_moyenne'] ?? 0) }}%"></div>
                    @endif
                </div>
            </div>
            <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-[11px]">
                <span class="text-slate-500">Repère temporel</span>
                <span class="font-bold text-slate-700">Idéale à date : {{ $detail['kpis']['progression_ideale_moyenne'] ?? 0 }}%</span>
            </div>
        </div>

        <!-- KPI 3 : Impasses Didactiques Réelles -->
        <div class="bg-white p-4 rounded-lg border {{ ($detail['kpis']['nb_impasses'] ?? 0) > 0 ? 'border-rose-300 bg-rose-50/15' : 'border-slate-200/90' }} shadow-2xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between text-xs font-bold {{ ($detail['kpis']['nb_impasses'] ?? 0) > 0 ? 'text-rose-800' : 'text-slate-500' }} mb-1.5">
                    <span>Impasses Réelles Décrochées</span>
                    <i class="fas fa-triangle-exclamation {{ ($detail['kpis']['nb_impasses'] ?? 0) > 0 ? 'text-rose-500' : 'text-slate-400' }}"></i>
                </div>
                <div class="flex items-baseline gap-2 my-2">
                    <span class="text-3xl font-black {{ ($detail['kpis']['nb_impasses'] ?? 0) > 0 ? 'text-rose-600' : 'text-emerald-600' }} font-mono">
                        {{ $detail['kpis']['nb_impasses'] ?? 0 }}
                    </span>
                    <span class="text-xs font-medium text-slate-400">compétence(s) &lt; 60% rythme</span>
                </div>
                <p class="text-[11px] text-slate-500">
                    {{ ($detail['kpis']['nb_impasses'] ?? 0) > 0 ? 'Décrochage sévère par rapport au calendrier' : 'Aucune rupture de calendrier' }}
                </p>
            </div>
            <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-[11px]">
                <span class="text-slate-500">Critère temporel</span>
                <span class="font-bold text-slate-700">Rythme &lt; 60% et Idéal &ge; 25%</span>
            </div>
        </div>

        <!-- KPI 4 : Apprenants dans les Temps -->
        <div class="bg-white p-4 rounded-lg border border-slate-200/90 shadow-2xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between text-xs font-bold text-slate-500 mb-1.5">
                    <span>Stagiaires dans les Temps</span>
                    <i class="fas fa-users-viewfinder text-indigo-500"></i>
                </div>
                <div class="flex items-baseline gap-2 my-2">
                    <span class="text-3xl font-black text-indigo-600 font-mono">
                        {{ $detail['kpis']['taux_homogeneite'] ?? 0 }}%
                    </span>
                    <span class="text-xs font-medium text-slate-400">à rythme &ge; 80%</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden my-2">
                    <div class="h-full rounded-full bg-indigo-500 transition-all duration-500" 
                         style="width: {{ min(100, $detail['kpis']['taux_homogeneite'] ?? 0) }}%"></div>
                </div>
            </div>
            <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-[11px]">
                <span class="text-slate-500">Effectif conforme</span>
                <span class="font-bold text-slate-700">
                    {{ $detail['kpis']['nb_homogenes'] ?? 0 }} / {{ $detail['kpis']['effectif'] ?? 0 }} apprenants
                </span>
            </div>
        </div>
    </div>

    <!-- 3. Section Alerte Pédagogique : Diagnostic des Impasses Didactiques Réelles -->
    @if(!empty($detail['impasses']))
    <section class="bg-amber-50 border-2 border-amber-300 rounded-lg p-5 sm:p-6 shadow-sm space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-amber-200/80 pb-3">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-100 border border-amber-200 flex items-center justify-center shrink-0">
                    <i class="fas fa-triangle-exclamation text-amber-600 text-lg"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h4 class="text-sm font-bold text-amber-900">Diagnostic des Impasses & Retards Didactiques Avérés</h4>
                        <span class="px-2 py-0.5 rounded-full text-[11px] font-extrabold bg-amber-600 text-white font-mono">
                            {{ count($detail['impasses']) }} compétence(s) identifiée(s)
                        </span>
                    </div>
                    <p class="text-xs text-amber-700 mt-0.5">
                        Ces compétences accusent un retard critique face au calendrier idéal théorique. Programmation d'ateliers de consolidation ou de sprint de rattrapage recommandée.
                    </p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach($detail['impasses'] as $imp)
            <div class="bg-white p-4 rounded-xl border border-amber-200 shadow-2xs space-y-2 hover:border-amber-400 transition">
                <div class="flex items-start justify-between gap-2">
                    <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold {{ $imp['severite'] === 'critique' ? 'bg-rose-100 text-rose-800 border border-rose-200' : 'bg-amber-100 text-amber-800 border border-amber-200' }}">
                        {{ $imp['code'] }}
                    </span>
                    <span class="text-xs font-mono font-black {{ $imp['severite'] === 'critique' ? 'text-rose-600' : 'text-amber-600' }}">
                        Rythme : {{ $imp['taux_rythme_formate'] }}
                    </span>
                </div>
                <div>
                    <h5 class="text-xs font-bold text-slate-900 leading-snug line-clamp-2" title="{{ $imp['titre'] }}">
                        {{ $imp['titre'] }}
                    </h5>
                    <p class="text-[10px] text-slate-400 mt-0.5 truncate">
                        {{ $imp['module_code'] ?? '' }} &bull; {{ $imp['module_nom'] ?? 'Module' }}
                    </p>
                </div>

                <!-- Jauge comparative réelle vs idéale -->
                <div class="relative w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                    <div class="h-full rounded-full {{ $imp['severite'] === 'critique' ? 'bg-rose-500' : 'bg-amber-500' }}" 
                         style="width: {{ min(100, $imp['progression']) }}%"></div>
                    @if($imp['progression_ideal'] > 0)
                    <div class="absolute top-0 bottom-0 w-0.5 bg-slate-900 z-10" 
                         style="left: {{ min(100, $imp['progression_ideal']) }}%" 
                         title="Repère Idéal : {{ $imp['progression_ideal'] }}%"></div>
                    @endif
                </div>

                <div class="flex items-center justify-between text-[11px] pt-1 border-t border-slate-100 text-slate-600">
                    <span>Réelle : <strong class="text-slate-900 font-mono">{{ $imp['progression_formate'] }}</strong></span>
                    <span>Idéale : <strong class="text-slate-700 font-mono">{{ $imp['progression_ideal_formate'] }}</strong></span>
                    <span class="font-mono font-bold text-rose-600">{{ $imp['delta_formate'] }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @else
    <section class="bg-emerald-50 border border-emerald-200 rounded-lg p-4 sm:p-5 shadow-2xs flex items-center gap-3">
        <div class="w-9 h-9 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
            <i class="fas fa-circle-check text-base"></i>
        </div>
        <div>
            <h4 class="text-xs sm:text-sm font-bold text-emerald-900">Progression Parfaitement Alignée sur le Calendrier</h4>
            <p class="text-xs text-emerald-700 mt-0.5">
                Aucune impasse didactique critique détectée. Toutes les compétences actives progressent en cohérence avec le calendrier pédagogique prévisionnel.
            </p>
        </div>
    </section>
    @endif

    <!-- 4. Onglets de Navigation : Compétences Macro vs Micro-Compétences vs Apprenants -->
    <div class="flex items-center gap-2 border-b border-slate-200 pb-2 flex-wrap">
        <button type="button" 
                @click="tabActive = 'competences'" 
                :class="tabActive === 'competences' ? 'bg-indigo-600 text-white shadow-2xs' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50'"
                class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-2">
            <i class="fas fa-layer-group"></i>
            <span>Compétences Nationales ({{ count($detail['competences'] ?? []) }})</span>
        </button>
        <button type="button" 
                @click="tabActive = 'matrice'" 
                :class="tabActive === 'matrice' ? 'bg-indigo-600 text-white shadow-2xs' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50'"
                class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-2">
            <i class="fas fa-table-cells"></i>
            <span>Micro-Compétences ({{ count($detail['micro_competences'] ?? []) }})</span>
        </button>
        <button type="button" 
                @click="tabActive = 'apprenants'" 
                :class="tabActive === 'apprenants' ? 'bg-indigo-600 text-white shadow-2xs' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50'"
                class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-2">
            <i class="fas fa-user-graduate"></i>
            <span>Grille Individuelle des Apprenants ({{ count($detail['apprenants'] ?? []) }})</span>
        </button>
    </div>

    <!-- 5. Vue Onglet 1 : Tableau Macro de Progression des Compétences Nationales -->
    <section x-show="tabActive === 'competences'" class="bg-white rounded-lg border border-slate-200 shadow-xs overflow-hidden space-y-4">
        <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-3">
            <div>
                <div class="flex items-center gap-2">
                    <h3 class="text-sm sm:text-base font-bold text-slate-900">
                        Progression des Compétences du Référentiel National (ICP)
                    </h3>
                    <span class="px-2 py-0.5 rounded-full text-xs font-black bg-indigo-50 text-indigo-700 border border-indigo-200 font-mono">
                        {{ count($detail['competences'] ?? []) }} compétences
                    </span>
                </div>
                <p class="text-xs text-slate-500 mt-0.5">
                    Confrontation directe de la <code class="text-indigo-600 font-mono font-bold">progression_cache</code> et de la <code class="text-emerald-700 font-mono font-bold">progression_ideal_cache</code> au sein du groupe {{ $detail['kpis']['groupe_code'] ?? 'Classe' }}.
                </p>
            </div>

            <!-- Filtre recherche -->
            <div class="flex items-center gap-2">
                <div class="relative">
                    <i class="fas fa-search text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 text-xs"></i>
                    <input type="text" 
                           x-model="searchQuery" 
                           placeholder="Rechercher une compétence..." 
                           class="text-xs rounded-lg border-slate-200 pl-8 pr-3 py-1.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 w-56 sm:w-72">
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 border-y border-slate-100 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="py-3 px-4">#</th>
                        <th class="py-3 px-4">Code</th>
                        <th class="py-3 px-4">Compétence Nationale</th>
                        <th class="py-3 px-4">Module</th>
                        <th class="py-3 px-4">Progression Réelle vs Idéale</th>
                        <th class="py-3 px-4">Écart (&Delta;)</th>
                        <th class="py-3 px-4">Taux de Rythme</th>
                        <th class="py-3 px-4 text-right">Diagnostic Temporel</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($detail['competences'] ?? [] as $index => $comp)
                    <tr x-show="!searchQuery || '{{ addslashes(mb_strtolower($comp['titre'] . ' ' . $comp['code'] . ' ' . ($comp['module_nom'] ?? '')))}}'.includes(searchQuery.toLowerCase())" 
                        class="hover:bg-slate-50/80 transition {{ $comp['is_impasse'] ? 'bg-rose-50/20' : ($comp['severite'] === 'tension' ? 'bg-amber-50/20' : '') }}">
                        <td class="py-3 px-4 font-mono text-slate-400 font-bold">
                            #{{ $index + 1 }}
                        </td>
                        <td class="py-3 px-4">
                            <span class="font-mono text-xs font-bold px-2 py-0.5 rounded bg-slate-100 text-slate-800 border border-slate-200">
                                {{ $comp['code'] }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <div class="font-bold text-slate-900">{{ $comp['titre'] }}</div>
                            @if(!empty($comp['description']))
                            <div class="text-[11px] text-slate-400 line-clamp-1 max-w-md" title="{{ $comp['description'] }}">
                                {{ $comp['description'] }}
                            </div>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-slate-700">
                            <div class="font-semibold">{{ $comp['module_code'] ?? '-' }}</div>
                            <div class="text-[10px] text-slate-400 truncate max-w-xs">{{ $comp['module_nom'] ?? '' }}</div>
                        </td>
                        <td class="py-3 px-4 min-w-[190px]">
                            <div class="space-y-1">
                                <div class="flex items-center justify-between text-[11px]">
                                    <span class="font-mono font-bold text-slate-900">{{ $comp['progression_formate'] }}</span>
                                    <span class="text-slate-400 text-[10px]">Idéal : {{ $comp['progression_ideal_formate'] }}</span>
                                </div>
                                <div class="relative w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                    <div class="h-full rounded-full transition-all duration-500 {{ $comp['severite'] === 'optimale' ? 'bg-emerald-500' : ($comp['severite'] === 'regulier' ? 'bg-indigo-500' : ($comp['severite'] === 'tension' ? 'bg-amber-500' : ($comp['severite'] === 'neutre' ? 'bg-slate-300' : 'bg-rose-500'))) }}" 
                                         style="width: {{ min(100, $comp['progression']) }}%"></div>
                                    @if($comp['progression_ideal'] > 0)
                                    <div class="absolute top-0 bottom-0 w-0.5 bg-slate-900 z-10" 
                                         style="left: {{ min(100, $comp['progression_ideal']) }}%" 
                                         title="Repère Idéal à date : {{ $comp['progression_ideal'] }}%"></div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4 font-mono font-bold {{ $comp['delta'] >= 0 ? 'text-emerald-700' : ($comp['delta'] >= -15 ? 'text-slate-700' : 'text-rose-700') }}">
                            {{ $comp['delta_formate'] }}
                        </td>
                        <td class="py-3 px-4 font-mono font-black text-xs text-slate-900">
                            {{ $comp['taux_rythme_formate'] }}
                        </td>
                        <td class="py-3 px-4 text-right">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold border {{ $comp['badge_class'] }}">
                                {{ $comp['statut_label'] }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-10 text-center text-slate-400">
                            Aucune compétence trouvée pour ce périmètre.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <!-- 6. Vue Onglet 2 : Matrice Complète des Micro-Compétences -->
    <section x-show="tabActive === 'matrice'" class="bg-white rounded-lg border border-slate-200 shadow-xs overflow-hidden space-y-4">
        <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-3">
            <div>
                <div class="flex items-center gap-2">
                    <h3 class="text-sm sm:text-base font-bold text-slate-900">
                        Matrice Détaillée des Micro-Compétences
                    </h3>
                    <span class="px-2 py-0.5 rounded-full text-xs font-black bg-indigo-50 text-indigo-700 border border-indigo-200 font-mono">
                        {{ count($detail['micro_competences'] ?? []) }} micro-compétences
                    </span>
                </div>
                <p class="text-xs text-slate-500 mt-0.5">
                    Détail des validations par micro-compétence au sein de la promotion.
                </p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 border-y border-slate-100 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="py-3 px-4">#</th>
                        <th class="py-3 px-4">Code</th>
                        <th class="py-3 px-4">Micro-Compétence</th>
                        <th class="py-3 px-4">Compétence de Rattachement</th>
                        <th class="py-3 px-4">Taux de Validation</th>
                        <th class="py-3 px-4 text-right">Validations</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($detail['micro_competences'] ?? [] as $idx => $mc)
                    <tr x-show="!searchQuery || '{{ addslashes(mb_strtolower($mc['titre'] . ' ' . $mc['code'] . ' ' . $mc['competence_nom'])) }}'.includes(searchQuery.toLowerCase())" 
                        class="hover:bg-slate-50/80 transition">
                        <td class="py-3 px-4 font-mono text-slate-400 font-bold">
                            #{{ $idx + 1 }}
                        </td>
                        <td class="py-3 px-4">
                            <span class="font-mono text-xs font-bold px-2 py-0.5 rounded bg-slate-100 text-slate-800 border border-slate-200">
                                {{ $mc['code'] }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <div class="font-bold text-slate-900">{{ $mc['titre'] }}</div>
                            @if(!empty($mc['description']))
                            <div class="text-[11px] text-slate-400 line-clamp-1 max-w-md" title="{{ $mc['description'] }}">
                                {{ $mc['description'] }}
                            </div>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-slate-700">
                            <div class="font-semibold">{{ $mc['competence_nom'] ?? '-' }}</div>
                            <div class="text-[10px] text-slate-400">{{ $mc['module_nom'] ?? '' }}</div>
                        </td>
                        <td class="py-3 px-4 min-w-[140px]">
                            <div class="flex items-center gap-2">
                                <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                    <div class="h-full rounded-full {{ $mc['taux'] >= 85 ? 'bg-emerald-500' : ($mc['taux'] >= 50 ? 'bg-indigo-500' : ($mc['taux'] >= 30 ? 'bg-amber-500' : 'bg-rose-500')) }}" 
                                         style="width: {{ $mc['taux'] }}%"></div>
                                </div>
                                <span class="font-mono font-bold text-xs text-slate-800 shrink-0">
                                    {{ $mc['taux_formate'] }}
                                </span>
                            </div>
                        </td>
                        <td class="py-3 px-4 font-mono font-bold text-slate-700 text-right">
                            {{ $mc['nb_valides'] }} / {{ $mc['effectif'] }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-10 text-center text-slate-400">
                            Aucune micro-compétence trouvée pour ce périmètre.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <!-- 7. Vue Onglet 3 : Grille Individuelle des Apprenants -->
    <section x-show="tabActive === 'apprenants'" class="bg-white rounded-lg border border-slate-200 shadow-xs overflow-hidden space-y-4">
        <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-3">
            <div>
                <div class="flex items-center gap-2">
                    <h3 class="text-sm sm:text-base font-bold text-slate-900">
                        Grille Individuelle des Apprenants & Confrontation Temporelle
                    </h3>
                    <span class="px-2 py-0.5 rounded-full text-xs font-black bg-indigo-50 text-indigo-700 border border-indigo-200 font-mono">
                        {{ count($detail['apprenants'] ?? []) }} apprenants
                    </span>
                </div>
                <p class="text-xs text-slate-500 mt-0.5">
                    Progression réelle (<code class="text-indigo-600 font-mono font-bold">progression_cache</code>), progression idéale (<code class="text-emerald-700 font-mono font-bold">progression_ideal_cache</code>) et taux de rythme individuel.
                </p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 border-y border-slate-100 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="py-3 px-4">Rang</th>
                        <th class="py-3 px-4">Apprenant</th>
                        <th class="py-3 px-4">Progression Réelle</th>
                        <th class="py-3 px-4">Progression Idéale</th>
                        <th class="py-3 px-4">Écart (&Delta;)</th>
                        <th class="py-3 px-4">Taux de Rythme</th>
                        <th class="py-3 px-4 text-right">Trajectoire Didactique</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($detail['apprenants'] ?? [] as $idx => $appr)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="py-3 px-4 font-mono font-bold {{ $idx < 3 ? 'text-amber-600' : 'text-slate-400' }}">
                            #{{ $idx + 1 }}
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-2.5">
                                <img src="{{ $appr['avatar'] }}" alt="{{ $appr['nom_complet'] }}" class="w-8 h-8 rounded-full object-cover shrink-0 border border-slate-200">
                                <div class="min-w-0">
                                    <div class="font-bold text-slate-900 truncate">{{ $appr['nom_complet'] }}</div>
                                    <div class="text-[10px] text-slate-400">ID #{{ $appr['id'] }} &bull; {{ $appr['matricule'] }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4 min-w-[140px]">
                            <div class="flex items-center gap-2">
                                <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                    <div class="h-full rounded-full bg-indigo-500" style="width: {{ min(100, $appr['progression']) }}%"></div>
                                </div>
                                <span class="font-mono font-bold text-xs text-slate-800 shrink-0">
                                    {{ $appr['progression_formate'] }}
                                </span>
                            </div>
                        </td>
                        <td class="py-3 px-4 font-mono font-bold text-slate-600">
                            {{ $appr['progression_ideal_formate'] }}
                        </td>
                        <td class="py-3 px-4 font-mono font-bold {{ ($appr['delta'] ?? 0) >= 0 ? 'text-emerald-700' : (($appr['delta'] ?? 0) >= -15 ? 'text-slate-700' : 'text-rose-700') }}">
                            {{ $appr['delta_formate'] ?? '0 pts' }}
                        </td>
                        <td class="py-3 px-4 font-mono font-black text-xs text-slate-900">
                            {{ $appr['taux_rythme_formate'] }}
                        </td>
                        <td class="py-3 px-4 text-right">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold border {{ $appr['badge_class'] }}">
                                {{ $appr['statut'] }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-10 text-center text-slate-400">
                            Aucun apprenant trouvé pour cette promotion.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <!-- 8. Benchmark Macro Inter-Groupes de l'Établissement -->
    @if(!empty($detail['benchmark_groupes']))
    <section class="bg-white rounded-lg border border-slate-200 shadow-xs overflow-hidden space-y-3">
        <div class="p-4 sm:p-5 border-b border-slate-100">
            <h4 class="text-sm sm:text-base font-bold text-slate-900">
                Benchmark Inter-Promotions : Couverture Référentiel National (ICP)
            </h4>
            <p class="text-xs text-slate-500 mt-0.5">
                Comparatif de la couverture pédagogique nationale entre toutes les cohortes actives de l'établissement.
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 border-y border-slate-100 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="py-3 px-4">Promotion / Groupe</th>
                        <th class="py-3 px-4">Effectif</th>
                        <th class="py-3 px-4">Micro-Compétences</th>
                        <th class="py-3 px-4">Validations Réalisées</th>
                        <th class="py-3 px-4">Taux ICP</th>
                        <th class="py-3 px-4">Statut Réglementaire</th>
                        <th class="py-3 px-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @foreach($detail['benchmark_groupes'] as $bench)
                    <tr class="hover:bg-slate-50/80 transition {{ ($bench['groupe_id'] ?? null) == ($detail['session_active']['groupe_id'] ?? null) ? 'bg-indigo-50/30' : '' }}">
                        <td class="py-3 px-4 font-bold text-slate-900">
                            {{ $bench['groupe_nom'] }}
                            @if(($bench['groupe_id'] ?? null) == ($detail['session_active']['groupe_id'] ?? null))
                            <span class="ms-1.5 px-2 py-0.5 rounded text-[10px] bg-indigo-100 text-indigo-700 font-bold">Actif</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 font-mono text-slate-700">{{ $bench['effectif'] }} stagiaires</td>
                        <td class="py-3 px-4 font-mono text-slate-700">{{ $bench['total_mc'] }} MC</td>
                        <td class="py-3 px-4 font-mono text-slate-700">{{ $bench['validations'] }}</td>
                        <td class="py-3 px-4 font-black font-mono text-slate-900">{{ $bench['icp_formate'] }}</td>
                        <td class="py-3 px-4">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $bench['badge_class'] }}">
                                {{ $bench['statut_badge'] }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-right">
                            <a href="{{ route('admin.PkgTableauBord.indicateurs.couverture', ['groupe_id' => $bench['groupe_id']]) }}" 
                               class="text-indigo-600 hover:text-indigo-800 font-semibold text-[11px]">
                                Inspecter &rarr;
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
