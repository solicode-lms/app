@extends('layouts.admin-v2')

@section('title', 'Détail Indicateur 09 — Taux de Couverture de Planification des UA (TCP-UA)')
@section('page_title', 'Indicateur 09 : Taux de Couverture de Planification des UA par SessionFormation')
@section('page_subtitle', 'Anticipation institutionnelle et pédagogique mesurant la proportion des Unités d\'Apprentissage (UA) couvertes par une planification évaluative')

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
<div class="space-y-6" x-data="{ searchQuery: '', tabActive: 'matrice', filterStatut: 'all' }">

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
                        <i class="fas fa-bullseye text-[10px] text-emerald-600"></i>
                        <span>Objectif Institutionnel : &ge; 80% des UA planifiées</span>
                    </span>
                </div>

                <h2 class="text-lg sm:text-xl font-bold text-slate-900 mt-1">
                    Indicateur 09 — Taux de Couverture de Planification des UA (TCP-UA)
                </h2>
                <p class="text-xs text-slate-500 leading-relaxed max-w-4xl">
                    Mesure prévisionnelle calculant le ratio entre les <strong>Unités d'Apprentissage (UA)</strong> mobilisées dans les sessions de formation actives et le total des UA obligatoires du programme. Ce suivi en amont garantit que chaque compétence fera l'objet d'un apprentissage et d'une évaluation pour sécuriser les notes de fin d'année.
                </p>
            </div>

            <!-- Badge Synthétique -->
            <div class="flex flex-col sm:flex-row lg:flex-col items-start lg:items-end justify-between gap-2 shrink-0">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold {{ $detail['kpis']['badge_class'] ?? 'bg-slate-100 text-slate-700' }}">
                    <i class="fas fa-layer-group text-[11px]"></i>
                    <span>{{ $detail['kpis']['badge_label'] ?? 'Planification Active' }}</span>
                </span>
                <span class="text-[11px] text-slate-400">
                    Sessions actives : <strong>{{ $detail['kpis']['nb_sessions'] ?? 0 }} sessions</strong> &bull; <strong>{{ $detail['kpis']['n_couvertes'] ?? 0 }} / {{ $detail['kpis']['n_attendues'] ?? 0 }} UA</strong>
                </span>
            </div>
        </div>

        <!-- 2. Formule & Règle Métier -->
        <div class="p-3.5 bg-slate-50 rounded-lg border border-slate-200 text-xs flex flex-col md:flex-row md:items-center justify-between gap-3">
            <div class="space-y-1">
                <div class="font-bold text-slate-700 flex items-center gap-2">
                    <i class="fas fa-square-root-variable text-indigo-600"></i>
                    <span>Formule Officielle du TCP-UA :</span>
                </div>
                <div class="text-[11px] text-slate-500">
                    <code class="px-1.5 py-0.5 bg-white border border-slate-300 rounded font-mono font-bold text-indigo-700">TCP-UA = (N_couvertes / N_attendues) &times; 100</code>
                    <span class="ml-2">— Une UA est comptabilisée une seule fois dès lors qu'elle est alignée sur au moins une SessionFormation.</span>
                </div>
            </div>
            <div class="text-right shrink-0">
                <span class="text-[11px] text-slate-400 block">Écart à la cible (80%)</span>
                @php
                    $ecartCible = round(($detail['kpis']['tcp_ua'] ?? 0) - 80.0, 1);
                @endphp
                <span class="text-xs font-mono font-bold {{ $ecartCible >= 0 ? 'text-emerald-600' : 'text-amber-600' }}">
                    {{ $ecartCible >= 0 ? '+' : '' }}{{ $ecartCible }} pts
                </span>
            </div>
        </div>
    </section>

    <!-- 3. Cartes KPIs Synthétiques (4 Métriques Clés) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- KPI 1 : Taux Global TCP-UA -->
        <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-2xs hover:border-indigo-300 transition flex flex-col justify-between">
            <div class="flex items-center justify-between text-slate-500 text-xs mb-1">
                <span class="font-bold">Taux de Couverture (TCP-UA)</span>
                <i class="fas fa-chart-pie text-indigo-500"></i>
            </div>
            <div class="my-2">
                <div class="text-2xl sm:text-3xl font-extrabold text-indigo-700 font-mono">
                    {{ $detail['kpis']['tcp_ua_formate'] ?? '45.3%' }}
                </div>
                <div class="w-full bg-slate-100 rounded-full h-2 mt-2 overflow-hidden">
                    <div class="h-2 rounded-full {{ ($detail['kpis']['tcp_ua'] ?? 0) >= 80 ? 'bg-emerald-500' : (($detail['kpis']['tcp_ua'] ?? 0) >= 50 ? 'bg-indigo-500' : 'bg-amber-500') }}"
                         style="width: {{ min(100, $detail['kpis']['tcp_ua'] ?? 0) }}%"></div>
                </div>
            </div>
            <div class="pt-2 border-t border-slate-100 text-[11px] text-slate-400 flex items-center justify-between">
                <span>Cible de planification :</span>
                <span class="font-mono font-bold text-slate-700">&ge; 80%</span>
            </div>
        </div>

        <!-- KPI 2 : UA Couvertes & Mobilisées -->
        <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-2xs hover:border-emerald-300 transition flex flex-col justify-between">
            <div class="flex items-center justify-between text-slate-500 text-xs mb-1">
                <span class="font-bold">UA Couvertes</span>
                <i class="fas fa-check-double text-emerald-500"></i>
            </div>
            <div class="my-2">
                <div class="text-2xl sm:text-3xl font-extrabold text-emerald-700 font-mono">
                    {{ $detail['kpis']['n_couvertes'] ?? 24 }}
                    <span class="text-xs font-normal text-slate-400">/ {{ $detail['kpis']['n_attendues'] ?? 53 }}</span>
                </div>
                <p class="text-[11px] text-slate-500 mt-1">
                    UA associées à des sessions de formation actives.
                </p>
            </div>
            <div class="pt-2 border-t border-slate-100 text-[11px] text-emerald-700 font-medium flex items-center gap-1">
                <i class="fas fa-circle-check text-[10px]"></i>
                <span>Évaluations N2 sécurisées</span>
            </div>
        </div>

        <!-- KPI 3 : UA Non Planifiées (Alertes) -->
        <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-2xs hover:border-amber-300 transition flex flex-col justify-between">
            <div class="flex items-center justify-between text-slate-500 text-xs mb-1">
                <span class="font-bold">UA Non Planifiées</span>
                <i class="fas fa-triangle-exclamation text-amber-500"></i>
            </div>
            <div class="my-2">
                <div class="text-2xl sm:text-3xl font-extrabold text-amber-600 font-mono">
                    {{ $detail['kpis']['n_manquantes'] ?? 29 }}
                </div>
                <p class="text-[11px] text-slate-500 mt-1">
                    UA sans session de formation attribuée.
                </p>
            </div>
            <div class="pt-2 border-t border-slate-100 text-[11px] text-amber-700 font-medium flex items-center gap-1">
                <i class="fas fa-clock text-[10px]"></i>
                <span>À inscrire dans le calendrier</span>
            </div>
        </div>

        <!-- KPI 4 : Sessions de Formation Déployées -->
        <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-2xs hover:border-slate-300 transition flex flex-col justify-between">
            <div class="flex items-center justify-between text-slate-500 text-xs mb-1">
                <span class="font-bold">Sessions Déployées</span>
                <i class="fas fa-calendar-days text-slate-500"></i>
            </div>
            <div class="my-2">
                <div class="text-2xl sm:text-3xl font-extrabold text-slate-800 font-mono">
                    {{ $detail['kpis']['nb_sessions'] ?? 3 }}
                </div>
                <p class="text-[11px] text-slate-500 mt-1">
                    Périodes de formation structurées pour la filière.
                </p>
            </div>
            <div class="pt-2 border-t border-slate-100 text-[11px] text-slate-500 flex items-center justify-between">
                <span>Filière active :</span>
                <span class="font-semibold text-slate-700 truncate max-w-[120px]">{{ $detail['kpis']['filiere_nom'] ?? 'DWB' }}</span>
            </div>
        </div>
    </div>

    <!-- 4. Navigation par Onglets Didactiques -->
    <div class="border-b border-slate-200">
        <nav class="flex space-x-6 text-xs font-semibold" aria-label="Tabs">
            <button type="button" 
                    @click="tabActive = 'matrice'" 
                    :class="tabActive === 'matrice' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'"
                    class="py-3 px-1 border-b-2 font-medium flex items-center gap-2 transition">
                <i class="fas fa-table-list"></i>
                <span>Matrice Complète des UA ({{ count($detail['matrice_uas'] ?? []) }})</span>
            </button>
            <button type="button" 
                    @click="tabActive = 'sessions'" 
                    :class="tabActive === 'sessions' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'"
                    class="py-3 px-1 border-b-2 font-medium flex items-center gap-2 transition">
                <i class="fas fa-calendar-check"></i>
                <span>Sessions & UA Mobilisées ({{ count($detail['sessions_detail'] ?? []) }})</span>
            </button>
            <button type="button" 
                    @click="tabActive = 'alertes'" 
                    :class="tabActive === 'alertes' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'"
                    class="py-3 px-1 border-b-2 font-medium flex items-center gap-2 transition">
                <i class="fas fa-list-check"></i>
                <span>Diagnostic par Module ({{ count($detail['modules_diagnostic'] ?? []) }})</span>
            </button>
            <button type="button" 
                    @click="tabActive = 'benchmark'" 
                    :class="tabActive === 'benchmark' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'"
                    class="py-3 px-1 border-b-2 font-medium flex items-center gap-2 transition">
                <i class="fas fa-chart-column"></i>
                <span>Benchmark Macro Filières</span>
            </button>
        </nav>
    </div>

    <!-- 5. Contenu des Onglets -->

    <!-- Onglet 1 : Matrice Complète des UA -->
    <div x-show="tabActive === 'matrice'" x-cloak class="space-y-4">
        <!-- Filtres et Recherche Live -->
        <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-2xs flex flex-col md:flex-row items-center justify-between gap-3">
            <div class="relative flex-1 w-full md:max-w-md">
                <i class="fas fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text" 
                       x-model="searchQuery" 
                       placeholder="Filtrer par code UA, intitulé, module ou compétence..." 
                       class="w-full pl-8 pr-3 py-1.5 rounded-lg border border-slate-200 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div class="flex items-center gap-2 w-full md:w-auto justify-end">
                <span class="text-xs text-slate-500 font-medium">Filtrer par statut :</span>
                <select x-model="filterStatut" class="px-2.5 py-1.5 rounded-lg border border-slate-200 text-xs text-slate-700 bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500">
                    <option value="all">Toutes les UA</option>
                    <option value="couverte">Planifiées uniquement</option>
                    <option value="non_couverte">Non planifiées (Manquantes)</option>
                </select>
            </div>
        </div>

        <!-- Tableau Matrice -->
        <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-500 font-semibold bg-slate-50/70">
                            <th class="py-3 px-4">Code UA</th>
                            <th class="py-3 px-4">Intitulé de l'Unité d'Apprentissage</th>
                            <th class="py-3 px-4">Module</th>
                            <th class="py-3 px-4">Compétence Liée</th>
                            <th class="py-3 px-4">Statut d'Alignement</th>
                            <th class="py-3 px-4">Session(s) de Formation</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($detail['matrice_uas'] ?? [] as $ua)
                        @php
                            $searchHaystack = strtolower($ua['code'] . ' ' . $ua['nom'] . ' ' . $ua['module_code'] . ' ' . $ua['module_nom'] . ' ' . $ua['competence_code'] . ' ' . $ua['competence_nom'] . ' ' . ($ua['session_titre'] ?? ''));
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition"
                            x-show="(searchQuery === '' || '{{ addslashes($searchHaystack) }}'.includes(searchQuery.toLowerCase())) && (filterStatut === 'all' || (filterStatut === 'couverte' && {{ $ua['est_couverte'] ? 'true' : 'false' }}) || (filterStatut === 'non_couverte' && !{{ $ua['est_couverte'] ? 'true' : 'false' }}))">
                            <td class="py-3 px-4 font-mono font-bold text-slate-900">
                                {{ $ua['code'] }}
                            </td>
                            <td class="py-3 px-4 font-medium text-slate-800">
                                <div>{{ $ua['nom'] }}</div>
                                <span class="text-[10px] text-slate-400 font-normal">Ordre #{{ $ua['ordre'] ?? 1 }}</span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="font-bold text-slate-700">{{ $ua['module_code'] }}</span>
                                <span class="text-[10px] text-slate-400 block truncate max-w-[150px]" title="{{ $ua['module_nom'] }}">{{ $ua['module_nom'] }}</span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="font-bold text-indigo-700">{{ $ua['competence_code'] }}</span>
                                <span class="text-[10px] text-slate-500 block truncate max-w-[180px]" title="{{ $ua['competence_nom'] }}">{{ $ua['competence_nom'] }}</span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold {{ $ua['badge_class'] }}">
                                    @if($ua['est_couverte'])
                                    <i class="fas fa-check-circle text-[10px]"></i>
                                    @else
                                    <i class="fas fa-circle-exclamation text-[10px]"></i>
                                    @endif
                                    <span>{{ $ua['statut_label'] }}</span>
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                @if(!empty($ua['sessions']))
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($ua['sessions'] as $sf)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-100 text-slate-700 border border-slate-200" title="{{ $sf['session_titre'] }}">
                                            <i class="fas fa-calendar-day text-[9px] text-slate-400"></i>
                                            <span>{{ $sf['session_code'] }}</span>
                                        </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-slate-400 italic text-[11px]">Non planifiée dans les sessions</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-400">Aucune unité d'apprentissage répertoriée pour cette filière.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Onglet 2 : Sessions & UA Mobilisées -->
    <div x-show="tabActive === 'sessions'" x-cloak class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @forelse($detail['sessions_detail'] ?? [] as $session)
            <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-5 flex flex-col justify-between space-y-4">
                <div>
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                Session #{{ $session['ordre'] }}
                            </span>
                            <h3 class="text-sm font-bold text-slate-900 mt-1">
                                {{ $session['code'] }} — {{ $session['titre'] }}
                            </h3>
                        </div>
                        <span class="px-2 py-0.5 rounded-full text-xs font-mono font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            {{ $session['nb_uas'] }} UA
                        </span>
                    </div>

                    @if(!empty($session['thematique']))
                    <p class="text-xs text-slate-500 mt-2 italic">
                        &laquo; {{ $session['thematique'] }} &raquo;
                    </p>
                    @endif

                    <div class="mt-3 text-[11px] text-slate-500 space-y-1">
                        <div class="flex items-center gap-1.5">
                            <i class="fas fa-calendar-days text-slate-400 w-3.5"></i>
                            <span>Période : Du <strong>{{ $session['date_debut'] }}</strong> au <strong>{{ $session['date_fin'] }}</strong></span>
                        </div>
                        @if(!empty($session['projets']))
                        <div class="flex items-center gap-1.5">
                            <i class="fas fa-diagram-project text-slate-400 w-3.5"></i>
                            <span>Projets associés : <strong>{{ implode(', ', $session['projets']) }}</strong></span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Liste des UA alignées sur cette session -->
                <div class="pt-3 border-t border-slate-100">
                    <span class="text-[11px] font-bold text-slate-600 uppercase tracking-wider block mb-2">
                        Unités d'Apprentissage Mobilisées :
                    </span>
                    <div class="flex flex-wrap gap-1 max-h-36 overflow-y-auto pr-1">
                        @forelse($session['uas'] as $u)
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-mono font-bold bg-slate-100 text-slate-800 border border-slate-200" title="{{ $u->nom }}">
                            <i class="fas fa-check text-[9px] text-emerald-600"></i>
                            <span>{{ $u->code }}</span>
                        </span>
                        @empty
                        <span class="text-xs text-slate-400 italic">Aucune UA alignée sur cette session.</span>
                        @endforelse
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-3 bg-white p-8 rounded-lg border border-slate-200 text-center text-slate-400">
                Aucune session de formation enregistrée pour cette filière.
            </div>
            @endforelse
        </div>
    </div>

    <!-- Onglet 3 : Diagnostic par Module & Alertes -->
    <div x-show="tabActive === 'alertes'" x-cloak class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            @forelse($detail['modules_diagnostic'] ?? [] as $m)
            <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-5 space-y-4">
                <div class="flex items-start justify-between gap-2">
                    <div>
                        <span class="text-[11px] font-mono font-bold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded">
                            {{ $m['code'] }}
                        </span>
                        <h3 class="text-sm font-bold text-slate-900 mt-1">
                            {{ $m['nom'] }}
                        </h3>
                    </div>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold {{ $m['badge_class'] }}">
                        {{ $m['taux_formate'] }}
                    </span>
                </div>

                <!-- Jauge de Couverture du Module -->
                <div>
                    <div class="flex justify-between text-xs text-slate-500 mb-1">
                        <span>Progression de planification :</span>
                        <span class="font-mono font-bold text-slate-700">{{ $m['couvertes_uas'] }} / {{ $m['total_uas'] }} UA</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                        <div class="h-2 rounded-full {{ $m['taux'] >= 80 ? 'bg-emerald-500' : ($m['taux'] >= 50 ? 'bg-indigo-500' : 'bg-rose-500') }}"
                             style="width: {{ $m['taux'] }}%"></div>
                    </div>
                </div>

                <!-- Liste des UA Manquantes pour ce module -->
                @if($m['manquantes_uas'] > 0)
                <div class="p-3 bg-amber-50/70 border border-amber-200 rounded-lg">
                    <div class="flex items-center gap-1.5 text-xs font-bold text-amber-800 mb-2">
                        <i class="fas fa-triangle-exclamation text-amber-600"></i>
                        <span>{{ $m['manquantes_uas'] }} UA non planifiée(s) dans les sessions :</span>
                    </div>
                    <ul class="space-y-1.5 max-h-40 overflow-y-auto pr-1">
                        @foreach($m['manquantes_list'] as $missingUa)
                        <li class="text-[11px] text-slate-700 flex items-start gap-1.5">
                            <span class="font-mono font-bold text-amber-700 shrink-0">[{{ $missingUa['code'] }}]</span>
                            <span class="flex-1 leading-tight">{{ $missingUa['nom'] }}</span>
                            <span class="text-[10px] text-slate-400 shrink-0">({{ $missingUa['comp_code'] }})</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @else
                <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-lg text-xs font-bold text-emerald-800 flex items-center gap-2">
                    <i class="fas fa-circle-check text-emerald-600"></i>
                    <span>Toutes les UA de ce module sont couvertes par la planification.</span>
                </div>
                @endif
            </div>
            @empty
            <div class="col-span-2 bg-white p-8 rounded-lg border border-slate-200 text-center text-slate-400">
                Aucun module analysé.
            </div>
            @endforelse
        </div>
    </div>

    <!-- Onglet 4 : Benchmark Macro Inter-Filières -->
    <div x-show="tabActive === 'benchmark'" x-cloak class="space-y-4">
        <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Comparatif Institutionnel par Filière de Formation</h3>
                    <p class="text-xs text-slate-500">Vue macroscopique de l'alignement pédagogique à l'échelle de l'établissement</p>
                </div>
                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                    Cible &ge; 80%
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-500 font-semibold bg-slate-50/70">
                            <th class="py-3 px-5">Filière</th>
                            <th class="py-3 px-4">Code</th>
                            <th class="py-3 px-4 font-mono">Total UA</th>
                            <th class="py-3 px-4 font-mono">UA Couvertes</th>
                            <th class="py-3 px-4 font-mono">UA Manquantes</th>
                            <th class="py-3 px-4 font-mono">Sessions Déployées</th>
                            <th class="py-3 px-4 font-mono font-bold text-indigo-700">Taux TCP-UA</th>
                            <th class="py-3 px-5 text-right">Diagnostic</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($detail['benchmark_filieres'] ?? [] as $fb)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3 px-5 font-bold text-slate-900">{{ $fb['filiere_nom'] }}</td>
                            <td class="py-3 px-4 font-mono text-slate-600">{{ $fb['filiere_code'] }}</td>
                            <td class="py-3 px-4 font-mono text-slate-800">{{ $fb['total_uas'] }}</td>
                            <td class="py-3 px-4 font-mono font-bold text-emerald-700">{{ $fb['couvertes_uas'] }}</td>
                            <td class="py-3 px-4 font-mono text-amber-600">{{ $fb['manquantes_uas'] }}</td>
                            <td class="py-3 px-4 font-mono text-slate-600">{{ $fb['nb_sessions'] }}</td>
                            <td class="py-3 px-4 font-mono font-extrabold text-indigo-700 text-sm">{{ $fb['tcp_ua_formate'] }}</td>
                            <td class="py-3 px-5 text-right">
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $fb['badge_class'] }}">
                                    {{ $fb['statut_badge'] }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="p-6 text-center text-slate-400">Aucune donnée de filière disponible.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
