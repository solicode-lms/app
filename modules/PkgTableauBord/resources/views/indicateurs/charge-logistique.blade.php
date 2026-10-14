@extends('layouts.admin-v2')

@section('title', 'Détail Indicateur 04 — Charge Logistique Hebdomadaire du Formateur')
@section('page_title', 'Indicateur 04 : Charge Logistique Hebdomadaire du Formateur (C_logistique)')
@section('page_subtitle', 'Télémétrie d\'activité, temps passé sur le Kanban, la file d\'attente FIFO, les bordereaux et disponibilité pour le tutorat direct')

@section('actions')
<div class="flex items-center gap-2">
    <a href="{{ route($dashboardRoute ?? (auth()->user()?->hasAnyRole([\Modules\PkgAutorisation\Models\Role::ADMIN_ROLE, 'root', 'responsable']) ? 'admin.PkgTableauBord.responsable' : 'admin.PkgTableauBord.formateur'), request()->except('from')) }}" 
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
<div class="space-y-6" x-data="{
    searchQuery: '',
    statusFilter: 'tous',
    activeModalFormateur: null,
    openModal(f) {
        this.activeModalFormateur = f;
    },
    closeModal() {
        this.activeModalFormateur = null;
    },
    changeSemaine(semaine) {
        const url = new URL(window.location.href);
        url.searchParams.set('semaine', semaine);
        window.location.href = url.toString();
    }
}">

    <!-- En-tête contextuel & Fiche Didactique de l'Indicateur -->
    <section class="bg-white rounded-lg border border-slate-200 p-5 sm:p-6 shadow-xs space-y-4">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div class="space-y-1.5">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200 flex items-center gap-1.5">
                        <i class="fas fa-clock text-indigo-500"></i>
                        <span>Indicateur Clé #04</span>
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                        Plafond Cible : &le; {{ $detail['cible_max'] }}h / sem
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        Optimal : &le; {{ $detail['seuil_optimal'] }}h
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                        Alerte : &gt; {{ $detail['seuil_alerte'] }}h
                    </span>
                    @if(!empty($isVisionGlobale))
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200 flex items-center gap-1">
                            <i class="fas fa-globe text-[11px] text-emerald-600"></i>
                            <span>Vision Globale ({{ $detail['nombre_formateurs'] }} formateurs)</span>
                        </span>
                    @endif
                </div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                    Charge Logistique Hebdomadaire du Formateur (C<sub>logistique</sub>)
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 max-w-4xl leading-relaxed">
                    Mesure le temps actif consacré aux micro-tâches de gestion : pilotage Kanban, arbitrage de la file FIFO d'évaluation et exports de bordereaux. L'objectif pédagogique est de minimiser la bureaucratie pour sanctuariser un maximum de temps dédié au tutorat direct (&ge; 90% d'une semaine de 35h).
                </p>
            </div>

            <!-- Sélecteur de Semaine & Synthèse Équipe -->
            <div class="shrink-0 flex flex-col sm:flex-row items-stretch sm:items-center gap-3 bg-slate-50 p-3.5 rounded-lg border border-slate-200">
                <div class="flex flex-col gap-1">
                    <label for="select-semaine" class="text-[10px] uppercase font-bold text-slate-500 tracking-wider flex items-center gap-1">
                        <i class="fas fa-calendar-week text-slate-400"></i>
                        <span>Semaine Analysée</span>
                    </label>
                    <select id="select-semaine" 
                            class="bg-white border border-slate-300 text-slate-800 text-xs font-bold rounded-md px-2.5 py-1.5 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                            @change="changeSemaine($event.target.value)">
                        @foreach($detail['semaines_disponibles'] as $sem)
                            <option value="{{ $sem }}" {{ $sem === $detail['semaine'] ? 'selected' : '' }}>
                                Semaine {{ $sem }} {{ $sem === now()->format('o-\WW') ? '(En cours)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="hidden sm:block h-10 w-px bg-slate-200"></div>
                <div class="text-center px-1">
                    <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">Moyenne Équipe</span>
                    <span class="text-2xl sm:text-3xl font-black {{ $detail['charge_moyenne_heures'] <= $detail['seuil_optimal'] ? 'text-emerald-700' : ($detail['charge_moyenne_heures'] <= $detail['seuil_alerte'] ? 'text-amber-600' : 'text-rose-700') }} font-mono">
                        {{ $detail['charge_moyenne_formatee'] }}
                    </span>
                    <span class="text-[10px] text-slate-500 block font-medium">/ sem / formateur</span>
                </div>
                <div class="hidden sm:block h-10 w-px bg-slate-200"></div>
                <div class="text-center px-1">
                    <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">Disponibilité Tutorat</span>
                    <span class="text-2xl sm:text-3xl font-black text-emerald-700 font-mono">
                        {{ $detail['taux_disponibilite_formate'] }}
                    </span>
                    <span class="text-[10px] text-slate-500 block font-medium">base 35h</span>
                </div>
            </div>
        </div>
    </section>

    <!-- 5 Cartes Métriques Clés -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">

        {{-- 1. Charge Moyenne Équipe --}}
        <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-2xs hover:shadow-sm transition">
            <div class="flex items-start justify-between gap-2 mb-2">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Charge Moyenne</span>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $detail['statut_badge'] }}">
                    {{ $detail['statut'] }}
                </span>
            </div>
            <div class="flex items-baseline gap-2 mb-1">
                <span class="text-2xl sm:text-3xl font-black text-slate-900 font-mono">
                    {{ $detail['charge_moyenne_formatee'] }}
                </span>
                <span class="text-xs text-slate-400 font-medium">/ sem</span>
            </div>
            <p class="text-xs text-slate-500 leading-snug">
                Plafond cible didactique fixé à &le; {{ $detail['cible_max'] }}h.
            </p>
            <div class="mt-3 pt-2 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                <span>Temps total équipe :</span>
                <strong class="font-mono text-slate-800">{{ $detail['total_heures_equipe_formate'] }}</strong>
            </div>
        </div>

        {{-- 2. Taux de Disponibilité Tutorat --}}
        <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-2xs hover:shadow-sm transition">
            <div class="flex items-start justify-between gap-2 mb-2">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Disponibilité Tutorat</span>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    &ge; 90% cible
                </span>
            </div>
            <div class="flex items-baseline gap-2 mb-1">
                <span class="text-2xl sm:text-3xl font-black text-emerald-700 font-mono">
                    {{ $detail['taux_disponibilite_formate'] }}
                </span>
            </div>
            <p class="text-xs text-slate-500 leading-snug">
                Part du temps hebdomadaire préservée pour l'accompagnement des apprenants.
            </p>
            <div class="mt-3 pt-2 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                <span>Temps résiduel :</span>
                <strong class="font-mono text-slate-800">~{{ round(35.0 - $detail['charge_moyenne_heures'], 1) }}h / sem</strong>
            </div>
        </div>

        {{-- 3. Pilotage Kanban --}}
        <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-2xs hover:shadow-sm transition">
            <div class="flex items-start justify-between gap-2 mb-2">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Pilotage Kanban</span>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200 font-mono">
                    {{ $detail['details_globaux']['pilotage_pct'] }}% mix
                </span>
            </div>
            <div class="flex items-baseline gap-2 mb-1">
                <span class="text-2xl sm:text-3xl font-black text-indigo-700 font-mono">
                    {{ $detail['details_globaux']['pilotage_heures'] }}h
                </span>
                <span class="text-xs text-slate-400 font-medium font-mono">({{ $detail['details_globaux']['pilotage_formate'] }})</span>
            </div>
            <p class="text-xs text-slate-500 leading-snug">
                Supervision des colonnes de tâches et synchronisation globale du projet.
            </p>
            <div class="mt-3 pt-2 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                <span>Catégorie :</span>
                <span class="font-mono text-slate-700">PILOTAGE_KANBAN</span>
            </div>
        </div>

        {{-- 4. File FIFO d'Évaluation --}}
        <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-2xs hover:shadow-sm transition">
            <div class="flex items-start justify-between gap-2 mb-2">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">File FIFO Évaluation</span>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-sky-50 text-sky-700 border border-sky-200 font-mono">
                    {{ $detail['details_globaux']['file_pct'] }}% mix
                </span>
            </div>
            <div class="flex items-baseline gap-2 mb-1">
                <span class="text-2xl sm:text-3xl font-black text-sky-700 font-mono">
                    {{ $detail['details_globaux']['file_heures'] }}h
                </span>
                <span class="text-xs text-slate-400 font-medium font-mono">({{ $detail['details_globaux']['file_formate'] }})</span>
            </div>
            <p class="text-xs text-slate-500 leading-snug">
                Dépilage prioritaire des revues de code et passage des Live Codings.
            </p>
            <div class="mt-3 pt-2 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                <span>Catégorie :</span>
                <span class="font-mono text-slate-700">GESTION_FILE_FIFO</span>
            </div>
        </div>

        {{-- 5. Bordereaux & Notes --}}
        <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-2xs hover:shadow-sm transition">
            <div class="flex items-start justify-between gap-2 mb-2">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Bordereaux & Notes</span>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200 font-mono">
                    {{ $detail['details_globaux']['bordereaux_pct'] }}% mix
                </span>
            </div>
            <div class="flex items-baseline gap-2 mb-1">
                <span class="text-2xl sm:text-3xl font-black text-amber-700 font-mono">
                    {{ $detail['details_globaux']['bordereaux_heures'] }}h
                </span>
                <span class="text-xs text-slate-400 font-medium font-mono">({{ $detail['details_globaux']['bordereaux_formate'] }})</span>
            </div>
            <p class="text-xs text-slate-500 leading-snug">
                Génération des rapports, consolidation des notes et exports docimologiques.
            </p>
            <div class="mt-3 pt-2 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                <span>Catégorie :</span>
                <span class="font-mono text-slate-700">EXPORT_BORDEREAU</span>
            </div>
        </div>
    </div>

    <!-- Ventilation Visuelle du Mix d'Activité Télémesuré -->
    <section class="bg-white rounded-lg border border-slate-200 p-5 shadow-xs space-y-3">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                <h2 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                    <i class="fas fa-chart-pie text-indigo-500"></i>
                    <span>Ventilation du Mix de Micro-Gestion Administrative (Semaine {{ $detail['semaine'] }})</span>
                </h2>
                <p class="text-xs text-slate-500">
                    Répartition en pourcentage du temps actif mesuré par la télémétrie navigateur (exclusion automatique de l'inactivité après {{ $detail['timeout_inactivite'] }} min).
                </p>
            </div>
            <span class="text-xs font-mono font-bold text-slate-600 bg-slate-100 px-2.5 py-1 rounded">
                Total équipe : {{ $detail['total_heures_equipe_formate'] }}
            </span>
        </div>

        <!-- Jauge d'activité segmentée -->
        <div class="w-full h-4 bg-slate-100 rounded-full overflow-hidden flex shadow-inner">
            <div class="bg-indigo-600 transition-all duration-500" 
                 style="width: {{ $detail['details_globaux']['pilotage_pct'] }}%" 
                 title="Pilotage Kanban : {{ $detail['details_globaux']['pilotage_pct'] }}% ({{ $detail['details_globaux']['pilotage_heures'] }}h)"></div>
            <div class="bg-sky-500 transition-all duration-500" 
                 style="width: {{ $detail['details_globaux']['file_pct'] }}%" 
                 title="File FIFO : {{ $detail['details_globaux']['file_pct'] }}% ({{ $detail['details_globaux']['file_heures'] }}h)"></div>
            <div class="bg-amber-500 transition-all duration-500" 
                 style="width: {{ $detail['details_globaux']['bordereaux_pct'] }}%" 
                 title="Bordereaux : {{ $detail['details_globaux']['bordereaux_pct'] }}% ({{ $detail['details_globaux']['bordereaux_heures'] }}h)"></div>
        </div>

        <!-- Légende didactique -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-2 text-xs">
            <div class="flex items-start gap-2 p-2 rounded-lg bg-indigo-50/50 border border-indigo-100">
                <span class="w-3 h-3 rounded-full bg-indigo-600 shrink-0 mt-0.5"></span>
                <div>
                    <div class="flex items-center gap-1.5 font-bold text-slate-800">
                        <span>Pilotage Kanban</span>
                        <span class="text-indigo-700 font-mono">({{ $detail['details_globaux']['pilotage_pct'] }}%)</span>
                    </div>
                    <span class="text-[11px] text-slate-500 block">Temps actif passé sur le tableau de suivi des tâches du projet.</span>
                </div>
            </div>
            <div class="flex items-start gap-2 p-2 rounded-lg bg-sky-50/50 border border-sky-100">
                <span class="w-3 h-3 rounded-full bg-sky-500 shrink-0 mt-0.5"></span>
                <div>
                    <div class="flex items-center gap-1.5 font-bold text-slate-800">
                        <span>File d'Attente FIFO</span>
                        <span class="text-sky-700 font-mono">({{ $detail['details_globaux']['file_pct'] }}%)</span>
                    </div>
                    <span class="text-[11px] text-slate-500 block">Organisation et dépilage des demandes de validation d'étapes.</span>
                </div>
            </div>
            <div class="flex items-start gap-2 p-2 rounded-lg bg-amber-50/50 border border-amber-100">
                <span class="w-3 h-3 rounded-full bg-amber-500 shrink-0 mt-0.5"></span>
                <div>
                    <div class="flex items-center gap-1.5 font-bold text-slate-800">
                        <span>Bordereaux & Exports</span>
                        <span class="text-amber-700 font-mono">({{ $detail['details_globaux']['bordereaux_pct'] }}%)</span>
                    </div>
                    <span class="text-[11px] text-slate-500 block">Exportation des relevés et formalisation des notes docimologiques.</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Tableau Comparatif (Benchmark Équipe Formateurs) -->
    <section class="bg-white rounded-lg border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-4 sm:p-5 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h2 class="text-sm sm:text-base font-bold text-slate-900 flex items-center gap-2">
                    <i class="fas fa-users-gear text-indigo-500"></i>
                    <span>Benchmark Individuel des Formateurs (Semaine {{ $detail['semaine'] }})</span>
                </h2>
                <p class="text-xs text-slate-500">
                    Détection des surcharges administratives individuelles et préservation de la disponibilité tutorat.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <!-- Filtre par Statut (Pills) -->
                <div class="inline-flex rounded-lg border border-slate-200 p-0.5 bg-slate-50 text-xs">
                    <button type="button" 
                            @click="statusFilter = 'tous'" 
                            :class="statusFilter === 'tous' ? 'bg-white shadow-2xs font-bold text-slate-900' : 'text-slate-600 hover:text-slate-900'"
                            class="px-2.5 py-1 rounded-md transition text-xs">
                        Tous ({{ count($detail['benchmark']) }})
                    </button>
                    <button type="button" 
                            @click="statusFilter = 'optimal'" 
                            :class="statusFilter === 'optimal' ? 'bg-white shadow-2xs font-bold text-emerald-800' : 'text-slate-600 hover:text-emerald-700'"
                            class="px-2.5 py-1 rounded-md transition text-xs">
                        Optimal ({{ $detail['nb_optimal'] }})
                    </button>
                    <button type="button" 
                            @click="statusFilter = 'vigilance'" 
                            :class="statusFilter === 'vigilance' ? 'bg-white shadow-2xs font-bold text-amber-800' : 'text-slate-600 hover:text-amber-700'"
                            class="px-2.5 py-1 rounded-md transition text-xs">
                        Vigilance ({{ $detail['nb_vigilance'] }})
                    </button>
                    <button type="button" 
                            @click="statusFilter = 'alerte'" 
                            :class="statusFilter === 'alerte' ? 'bg-white shadow-2xs font-bold text-rose-800' : 'text-slate-600 hover:text-rose-700'"
                            class="px-2.5 py-1 rounded-md transition text-xs">
                        Alerte ({{ $detail['nb_alerte'] }})
                    </button>
                </div>

                <!-- Recherche rapide -->
                <div class="relative">
                    <input type="text" 
                           x-model="searchQuery" 
                           placeholder="Rechercher un formateur..." 
                           class="w-48 sm:w-64 bg-slate-50 border border-slate-200 text-slate-800 text-xs rounded-lg pl-8 pr-3 py-1.5 focus:bg-white focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    <i class="fas fa-search absolute left-2.5 top-2.5 text-slate-400 text-xs"></i>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200 text-slate-500 font-semibold uppercase tracking-wider text-[11px]">
                        <th class="py-3 px-4">Formateur</th>
                        <th class="py-3 px-3 text-center">Temps Logistique (C<sub>logistique</sub>)</th>
                        <th class="py-3 px-3 text-center">Pilotage Kanban</th>
                        <th class="py-3 px-3 text-center">File FIFO</th>
                        <th class="py-3 px-3 text-center">Bordereaux</th>
                        <th class="py-3 px-3 text-center">Disponibilité Tutorat</th>
                        <th class="py-3 px-3 text-center">Statut Didactique</th>
                        <th class="py-3 px-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($detail['benchmark'] as $f)
                    <tr class="hover:bg-slate-50/80 transition"
                        x-show="(statusFilter === 'tous' || statusFilter === '{{ $f['zone'] }}') && 
                                ('{{ addslashes(strtolower($f['nom'])) }}'.includes(searchQuery.toLowerCase()) || 
                                 '{{ addslashes(strtolower($f['matricule'])) }}'.includes(searchQuery.toLowerCase()))">
                        
                        {{-- Formateur --}}
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center font-bold text-slate-600 text-xs shrink-0">
                                    {{ $f['initiales'] }}
                                </div>
                                <div class="min-w-0">
                                    <div class="font-bold text-slate-900 truncate">{{ $f['nom'] }}</div>
                                    <div class="text-[10px] text-slate-400 font-mono">Matricule : {{ $f['matricule'] }}</div>
                                </div>
                            </div>
                        </td>

                        {{-- Temps Logistique Total --}}
                        <td class="py-3 px-3 text-center">
                            <div class="inline-flex flex-col items-center">
                                <span class="font-black text-slate-900 font-mono text-sm">
                                    {{ $f['total_heures_dec'] }}
                                </span>
                                <span class="text-[10px] text-slate-400 font-mono">({{ $f['total_heures_formate'] }})</span>
                                <!-- Mini-jauge relative au plafond 3h -->
                                <div class="w-16 h-1 bg-slate-100 rounded-full overflow-hidden mt-1">
                                    <div class="{{ $f['zone'] === 'alerte' ? 'bg-rose-500' : ($f['zone'] === 'vigilance' ? 'bg-amber-500' : 'bg-indigo-600') }} h-full"
                                         style="width: {{ $f['pourcentage_cible'] }}%"></div>
                                </div>
                            </div>
                        </td>

                        {{-- Pilotage Kanban --}}
                        <td class="py-3 px-3 text-center">
                            <div class="font-mono font-bold text-indigo-700">{{ $f['pilotage_heures'] }}h</div>
                            <span class="text-[10px] text-slate-400 font-mono">{{ $f['pilotage_pct'] }}%</span>
                        </td>

                        {{-- File FIFO --}}
                        <td class="py-3 px-3 text-center">
                            <div class="font-mono font-bold text-sky-700">{{ $f['file_heures'] }}h</div>
                            <span class="text-[10px] text-slate-400 font-mono">{{ $f['file_pct'] }}%</span>
                        </td>

                        {{-- Bordereaux --}}
                        <td class="py-3 px-3 text-center">
                            <div class="font-mono font-bold text-amber-700">{{ $f['bordereaux_heures'] }}h</div>
                            <span class="text-[10px] text-slate-400 font-mono">{{ $f['bordereaux_pct'] }}%</span>
                        </td>

                        {{-- Disponibilité Tutorat Direct --}}
                        <td class="py-3 px-3 text-center">
                            <span class="inline-block px-2 py-0.5 rounded text-xs font-mono font-bold {{ $f['taux_disponibilite'] >= 90 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                                {{ $f['taux_disponibilite'] }}%
                            </span>
                        </td>

                        {{-- Statut & Zone --}}
                        <td class="py-3 px-3 text-center">
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold border {{ $f['badge_class'] }}">
                                <span class="w-1.5 h-1.5 rounded-full bg-current opacity-75"></span>
                                <span>{{ $f['badge_label'] }}</span>
                            </span>
                        </td>

                        {{-- Action --}}
                        <td class="py-3 px-4 text-right">
                            <button type="button" 
                                    @click="openModal({{ json_encode($f) }})" 
                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded border border-slate-200 hover:border-indigo-300 hover:bg-indigo-50/50 text-slate-600 hover:text-indigo-700 transition font-medium text-xs">
                                <i class="fas fa-eye text-[10px]"></i>
                                <span>Détails</span>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-8 text-slate-400">
                            Aucune activité télémétrée enregistrée pour la semaine sélectionnée.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <!-- Historique Multi-Semaines (Tendance & Évolution) -->
    @if(count($detail['historique_semaines']) > 1)
    <section class="bg-white rounded-lg border border-slate-200 p-5 shadow-xs space-y-4">
        <div>
            <h2 class="text-sm sm:text-base font-bold text-slate-900 flex items-center gap-2">
                <i class="fas fa-chart-line text-indigo-500"></i>
                <span>Évolution Temporelle de la Charge Logistique (Semaines Antérieures)</span>
            </h2>
            <p class="text-xs text-slate-500">
                Suivi plurimensuel pour vérifier l'efficacité des simplifications de processus et de l'automatisation LMS.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
            @foreach($detail['historique_semaines'] as $h)
            <div class="p-3.5 rounded-lg border {{ $h['semaine'] === $detail['semaine'] ? 'bg-indigo-50/40 border-indigo-200 ring-1 ring-indigo-300' : 'bg-slate-50 border-slate-200' }} space-y-2">
                <div class="flex items-center justify-between text-xs">
                    <span class="font-bold text-slate-800">{{ $h['semaine'] }}</span>
                    @if($h['semaine'] === $detail['semaine'])
                        <span class="px-1.5 py-0.2 rounded text-[9px] font-bold bg-indigo-600 text-white">Sélectionnée</span>
                    @endif
                </div>
                <div class="flex items-baseline justify-between">
                    <span class="text-xl font-black text-slate-900 font-mono">{{ $h['charge_moyenne_formatee'] }}</span>
                    <span class="text-[11px] font-mono text-emerald-700 font-bold">{{ $h['taux_dispo'] }}% dispo</span>
                </div>
                <div class="text-[10px] text-slate-500 grid grid-cols-3 gap-1 pt-1 border-t border-slate-200/60 font-mono">
                    <div>K: {{ $h['pilotage_heures'] }}h</div>
                    <div>F: {{ $h['file_heures'] }}h</div>
                    <div>B: {{ $h['bordereaux_heures'] }}h</div>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    <!-- Guide Méthodologique & Explication Didactique -->
    <div class="bg-slate-50 rounded-lg border border-slate-200 p-4 sm:p-5 text-xs text-slate-600 space-y-2">
        <h3 class="font-bold text-slate-900 flex items-center gap-1.5 text-xs">
            <i class="fas fa-circle-info text-indigo-500"></i>
            <span>Méthodologie de Mesure de la Charge Logistique (C<sub>logistique</sub>)</span>
        </h3>
        <p class="leading-relaxed">
            La charge logistique hebdomadaire mesure le temps actif passé par les formateurs sur les interfaces administratives de la plateforme :
        </p>
        <ul class="list-disc list-inside space-y-1 text-slate-600 ps-1">
            <li><strong>Télémétrie active continue :</strong> Un battement de cœur (heartbeat ping) est transmis toutes les 60 secondes tant que le formateur interagit avec l'application.</li>
            <li><strong>Verrou d'inactivité didactique :</strong> Après <strong>{{ $detail['timeout_inactivite'] }} minutes sans interaction</strong> (clics, défilement, frappes), le chronométrage s'arrête automatiquement pour exclure le temps de veille passif.</li>
            <li><strong>Sanctuarisation du tutorat :</strong> Le temps restant (cible &ge; 90% d'une semaine de 35h) est préservé pour les interactions directes, revues orales et ateliers de remédiation en présentiel.</li>
        </ul>
    </div>

    <!-- Modale Détail Individuel Formateur -->
    <template x-if="activeModalFormateur">
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs"
             @click.self="closeModal()"
             @keydown.escape.window="closeModal()">
            <div class="bg-white rounded-xl shadow-xl border border-slate-200 max-w-lg w-full p-5 space-y-4">
                <div class="flex items-start justify-between border-b border-slate-100 pb-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-indigo-50 text-indigo-700 font-bold flex items-center justify-center text-sm"
                             x-text="activeModalFormateur?.initiales"></div>
                        <div>
                            <h4 class="font-bold text-slate-900" x-text="activeModalFormateur?.nom"></h4>
                            <span class="text-xs text-slate-400 font-mono" x-text="'Matricule : ' + activeModalFormateur?.matricule"></span>
                        </div>
                    </div>
                    <button type="button" @click="closeModal()" class="text-slate-400 hover:text-slate-600 p-1">
                        <i class="fas fa-times text-sm"></i>
                    </button>
                </div>

                <div class="space-y-3 text-xs">
                    <div class="grid grid-cols-2 gap-3 bg-slate-50 p-3 rounded-lg border border-slate-200 font-mono">
                        <div>
                            <span class="text-slate-400 block text-[10px] uppercase font-sans">Temps Total Détecté</span>
                            <strong class="text-slate-900 text-base" x-text="activeModalFormateur?.total_heures_dec"></strong>
                            <span class="text-slate-500 block text-[10px]" x-text="'(' + activeModalFormateur?.total_heures_formate + ')'"></span>
                        </div>
                        <div>
                            <span class="text-slate-400 block text-[10px] uppercase font-sans">Disponibilité Tutorat</span>
                            <strong class="text-emerald-700 text-base" x-text="activeModalFormateur?.taux_disponibilite + '%'"></strong>
                            <span class="text-slate-500 block text-[10px]">base 35h</span>
                        </div>
                    </div>

                    <div class="space-y-2 pt-1">
                        <span class="font-bold text-slate-700 block">Détail par catégorie télémétrique :</span>
                        
                        <div class="p-2 rounded border border-slate-100 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-indigo-600"></span>
                                <span class="text-slate-700 font-medium">Pilotage Kanban</span>
                            </div>
                            <div class="text-right font-mono">
                                <strong class="text-indigo-700" x-text="activeModalFormateur?.pilotage_heures + 'h'"></strong>
                                <span class="text-slate-400 text-[10px]" x-text="' (' + activeModalFormateur?.pilotage_pct + '%)'"></span>
                            </div>
                        </div>

                        <div class="p-2 rounded border border-slate-100 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-sky-500"></span>
                                <span class="text-slate-700 font-medium">File FIFO Évaluation</span>
                            </div>
                            <div class="text-right font-mono">
                                <strong class="text-sky-700" x-text="activeModalFormateur?.file_heures + 'h'"></strong>
                                <span class="text-slate-400 text-[10px]" x-text="' (' + activeModalFormateur?.file_pct + '%)'"></span>
                            </div>
                        </div>

                        <div class="p-2 rounded border border-slate-100 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                                <span class="text-slate-700 font-medium">Bordereaux & Exports</span>
                            </div>
                            <div class="text-right font-mono">
                                <strong class="text-amber-700" x-text="activeModalFormateur?.bordereaux_heures + 'h'"></strong>
                                <span class="text-slate-400 text-[10px]" x-text="' (' + activeModalFormateur?.bordereaux_pct + '%)'"></span>
                            </div>
                        </div>
                    </div>

                    <div class="p-3 rounded-lg border text-xs"
                         :class="activeModalFormateur?.zone === 'alerte' ? 'bg-rose-50 border-rose-200 text-rose-800' : (activeModalFormateur?.zone === 'vigilance' ? 'bg-amber-50 border-amber-200 text-amber-800' : 'bg-emerald-50 border-emerald-200 text-emerald-800')">
                        <strong class="block mb-1" x-text="'Diagnostic Didactique : ' + activeModalFormateur?.badge_label"></strong>
                        <template x-if="activeModalFormateur?.zone === 'optimal'">
                            <span>Temps logistique exemplaire (&le; 2.5h). Le formateur consacre l'essentiel de sa semaine à l'accompagnement direct et aux rétroactions en direct.</span>
                        </template>
                        <template x-if="activeModalFormateur?.zone === 'vigilance'">
                            <span>Temps administratif notable. Il est conseillé de grouper les validations de tâches et d'utiliser les raccourcis de navigation pour limiter la charge mentale.</span>
                        </template>
                        <template x-if="activeModalFormateur?.zone === 'alerte'">
                            <span>Surcharge bureaucratique critique (&gt; 5h). Identifier si le formateur rencontre des difficultés avec les exports ou passe un temps excessif sur la file d'attente.</span>
                        </template>
                    </div>
                </div>

                <div class="flex justify-end pt-2 border-t border-slate-100">
                    <button type="button" @click="closeModal()" class="px-3.5 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition">
                        Fermer
                    </button>
                </div>
            </div>
        </div>
    </template>

</div>
@endsection
