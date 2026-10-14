@extends('layouts.admin-v2')

@section('title', 'Détail Indicateur 06 — Taux d\'Intervention et de Remédiation (T_intervention)')
@section('page_title', 'Indicateur 06 : Taux d\'Intervention et de Remédiation (T_intervention)')
@section('page_subtitle', 'Suivi des apprenants en état de remédiation (> 30 min après Live Coding). Le formateur les accompagne puis valide leur tâche.')

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
<div class="space-y-6" x-data="{ searchQuery: '' }">

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

    <!-- 1. En-tête Contextuel & Séance de Live Coding Active -->
    <section class="bg-white rounded-lg border border-slate-200 p-5 sm:p-6 shadow-sm space-y-4">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div class="space-y-1">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                        Session : {{ $detail['session_active']['projet_titre'] ?? 'Session de Formation' }}
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                        Groupe : {{ $detail['session_active']['groupe_code'] ?? 'Non défini' }}
                    </span>
                    @if(!empty($detail['session_active']['is_cloture']))
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span>Live Coding au tableau terminé ({{ $detail['session_active']['date_fin_heure'] }})</span>
                        </span>
                    @else
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-amber-500 animate-ping"></span>
                            <span>Live Coding en cours</span>
                        </span>
                    @endif
                </div>

                <h2 class="text-lg sm:text-xl font-bold text-slate-900 mt-1">
                    Indicateur 06 — Suivi et Résolution des Remédiations (T<sub>intervention</sub>)
                </h2>
                <p class="text-xs text-slate-500 leading-relaxed max-w-4xl">
                    Détection immédiate des ruptures post-Live Coding (seuil d'autonomie de {{ $detail['kpis']['seuil_minutes'] ?? 30 }} min). Le système bascule automatiquement les tâches non terminées en état <strong>« En remédiation »</strong>. Le formateur intervient auprès des stagiaires concernés, puis valide leur tâche.
                </p>
            </div>

            <!-- Badge Synthétique de Zone Didactique -->
            <div class="flex flex-col sm:flex-row lg:flex-col items-start lg:items-end justify-between gap-2 shrink-0">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold {{ $detail['kpis']['badge_class'] ?? 'bg-slate-100 text-slate-700' }}">
                    <i class="fas fa-certificate text-[11px]"></i>
                    <span>{{ $detail['kpis']['badge_label'] ?? 'Excellence' }}</span>
                </span>
                <span class="text-[11px] text-slate-400">
                    Seuil d'autonomie : <strong>{{ $detail['kpis']['seuil_minutes'] ?? 30 }} minutes</strong>
                </span>
            </div>
        </div>

        <!-- Formule et repères didactiques -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 pt-3 border-t border-slate-100 text-xs">
            <div class="flex items-center gap-2 p-2.5 rounded-md bg-slate-50 border border-slate-200/60">
                <i class="fas fa-square-root-variable text-indigo-500 shrink-0"></i>
                <div class="min-w-0">
                    <span class="text-slate-500 block text-[11px]">Formule d'autonomie :</span>
                    <span class="font-bold text-slate-800 font-mono text-[11px]">T<sub>intervention</sub> = (N<sub>autonomes</sub> / N<sub>total</sub>) &times; 100</span>
                </div>
            </div>
            <div class="flex items-center gap-2 p-2.5 rounded-md bg-slate-50 border border-slate-200/60">
                <i class="fas fa-bullseye text-emerald-500 shrink-0"></i>
                <div class="min-w-0">
                    <span class="text-slate-500 block text-[11px]">Objectif Cible :</span>
                    <span class="font-bold text-emerald-700 text-[11px]">&ge; 85% autonomes sans remédiation</span>
                </div>
            </div>
            <div class="flex items-center gap-2 p-2.5 rounded-md bg-slate-50 border border-slate-200/60">
                <i class="fas fa-hand-holding-hand text-amber-500 shrink-0"></i>
                <div class="min-w-0">
                    <span class="text-slate-500 block text-[11px]">Règle didactique :</span>
                    <span class="font-bold text-slate-700 text-[11px]">Accompagnement puis validation de tâche</span>
                </div>
            </div>
        </div>
    </section>

    <!-- 2. Synthèse Statistique (4 KPI Cards) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- KPI 1 : Taux d'Autonomie Initiale -->
        <div class="bg-white p-4 rounded-lg border border-slate-200/90 shadow-2xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between text-xs font-bold text-slate-500 mb-1.5">
                    <span>Taux d'Autonomie Initiale</span>
                    <i class="fas fa-chart-pie text-indigo-500"></i>
                </div>
                <div class="flex items-baseline gap-2 my-2">
                    <span class="text-3xl font-black text-slate-900 font-mono">
                        {{ $detail['kpis']['taux_autonomie_formate'] ?? $detail['kpis']['taux_intervention_formate'] ?? '100%' }}
                    </span>
                    <span class="text-xs font-medium text-slate-400">/ &ge; 85% cible</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden my-2">
                    <div class="h-full rounded-full transition-all duration-500 {{ ($detail['kpis']['taux_autonomie'] ?? 100) >= 85 ? 'bg-emerald-500' : (($detail['kpis']['taux_autonomie'] ?? 100) >= 60 ? 'bg-amber-500' : 'bg-rose-500') }}" 
                         style="width: {{ min(100, $detail['kpis']['taux_autonomie'] ?? 100) }}%"></div>
                </div>
            </div>
            <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-[11px]">
                <span class="text-slate-500">Sans remédiation</span>
                <span class="font-bold text-slate-700">
                    {{ $detail['kpis']['total_autonomes'] ?? 0 }} / {{ $detail['kpis']['total_apprenants'] ?? 0 }} apprenants
                </span>
            </div>
        </div>

        <!-- KPI 2 : Taux de Résolution des Remédiations -->
        <div class="bg-white p-4 rounded-lg border border-slate-200/90 shadow-2xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between text-xs font-bold text-slate-500 mb-1.5">
                    <span>Taux de Résolution</span>
                    <i class="fas fa-circle-check text-emerald-500"></i>
                </div>
                <div class="flex items-baseline gap-2 my-2">
                    <span class="text-3xl font-black text-emerald-600 font-mono">
                        {{ $detail['kpis']['taux_resolution_formate'] ?? '100%' }}
                    </span>
                    <span class="text-xs font-medium text-slate-400">résolues</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden my-2">
                    <div class="h-full rounded-full bg-emerald-500 transition-all duration-500" 
                         style="width: {{ min(100, $detail['kpis']['taux_resolution'] ?? 100) }}%"></div>
                </div>
            </div>
            <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-[11px]">
                <span class="text-slate-500">Débloqués & validés</span>
                <span class="font-bold text-emerald-700">
                    {{ $detail['kpis']['total_resolus'] ?? 0 }} / {{ $detail['kpis']['total_remedies'] ?? 0 }} remédiés
                </span>
            </div>
        </div>

        <!-- KPI 3 : Blocages Actifs en Cours -->
        <div class="bg-white p-4 rounded-lg border {{ ($detail['kpis']['total_en_remediation'] ?? 0) > 0 ? 'border-rose-300 bg-rose-50/20' : 'border-slate-200/90' }} shadow-2xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between text-xs font-bold {{ ($detail['kpis']['total_en_remediation'] ?? 0) > 0 ? 'text-rose-700' : 'text-slate-500' }} mb-1.5">
                    <span>Blocages Actifs</span>
                    <i class="fas fa-triangle-exclamation {{ ($detail['kpis']['total_en_remediation'] ?? 0) > 0 ? 'text-rose-500 animate-bounce' : 'text-slate-400' }}"></i>
                </div>
                <div class="flex items-baseline gap-2 my-2">
                    <span class="text-3xl font-black {{ ($detail['kpis']['total_en_remediation'] ?? 0) > 0 ? 'text-rose-600' : 'text-slate-900' }} font-mono">
                        {{ $detail['kpis']['total_en_remediation'] ?? 0 }}
                    </span>
                    <span class="text-xs font-medium text-slate-400">en attente d'aide</span>
                </div>
                <p class="text-[11px] text-slate-500">
                    Tâches actuellement en <strong>« En remédiation »</strong>.
                </p>
            </div>
            <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-[11px]">
                <span class="text-slate-500">Priorité formateur</span>
                <span class="font-bold {{ ($detail['kpis']['total_en_remediation'] ?? 0) > 0 ? 'text-rose-700' : 'text-emerald-700' }}">
                    {{ ($detail['kpis']['total_en_remediation'] ?? 0) > 0 ? 'Intervention requise' : 'Aucun blocage' }}
                </span>
            </div>
        </div>

        <!-- KPI 4 : Historique des Remédiations Nécessitées -->
        <div class="bg-white p-4 rounded-lg border border-slate-200/90 shadow-2xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between text-xs font-bold text-slate-500 mb-1.5">
                    <span>Total Ayant Nécessité Remédiation</span>
                    <i class="fas fa-clipboard-list text-slate-500"></i>
                </div>
                <div class="flex items-baseline gap-2 my-2">
                    <span class="text-3xl font-black text-slate-900 font-mono">
                        {{ $detail['kpis']['total_remedies'] ?? 0 }}
                    </span>
                    <span class="text-xs font-medium text-slate-400">tracés dans remediations</span>
                </div>
                <p class="text-[11px] text-slate-500">
                    {{ $detail['kpis']['total_resolus'] ?? 0 }} validé(s) &bull; {{ $detail['kpis']['total_en_remediation'] ?? 0 }} en cours.
                </p>
            </div>
            <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-[11px]">
                <span class="text-slate-500">Seuil d'autonomie</span>
                <span class="font-bold text-slate-700">
                    {{ $detail['kpis']['seuil_minutes'] ?? 30 }} minutes
                </span>
            </div>
        </div>
    </div>

    <!-- 3. Console d'Arbitrage & Flashbox Opérationnelle -->
    @if(empty($detail['session_active']['is_cloture']) && !empty($detail['session_active']['id']))
        <div class="bg-indigo-50 border-2 border-indigo-200 rounded-lg p-5 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-100 border border-indigo-200 flex items-center justify-center shrink-0">
                    <i class="fas fa-chalkboard-user text-indigo-600 text-lg"></i>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-indigo-900">Le Live Coding collectif au tableau est en cours</h4>
                    <p class="text-xs text-indigo-700 mt-0.5">
                        Lorsque la démonstration collective se termine, l'apprenant au tableau passe sa tâche à l'état <strong>« À valider Live coding »</strong>, ce qui enclenche automatiquement la fenêtre d'autonomie ({{ $detail['kpis']['seuil_minutes'] ?? 30 }} min).
                    </p>
                </div>
            </div>
            <form method="POST" action="{{ route('admin.PkgTableauBord.indicateurs.intervention.cloturer-live-coding') }}" class="shrink-0">
                @csrf
                <input type="hidden" name="affectation_projet_id" value="{{ $detail['session_active']['id'] }}">
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-bold bg-indigo-600 hover:bg-indigo-700 text-white shadow-sm transition">
                    <i class="fas fa-flag-checkered"></i>
                    <span>Marquer fin Live Coding</span>
                </button>
            </form>
        </div>
    @elseif(!empty($detail['apprenants_en_remediation']))
        <!-- Flashbox Alerte Rouge : Apprenants en Rupture Didactique -->
        <div class="bg-rose-50 border-2 border-rose-300 rounded-lg p-5 sm:p-6 shadow-sm space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-rose-200/80 pb-3">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-xl bg-rose-100 border border-rose-200 flex items-center justify-center shrink-0">
                        <i class="fas fa-hand-holding-hand text-rose-600 text-lg"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h4 class="text-sm font-bold text-rose-900">Apprenants en Remédiation (+{{ $detail['kpis']['seuil_minutes'] ?? 30 }} min)</h4>
                            <span class="px-2 py-0.5 rounded-full text-[11px] font-extrabold bg-rose-600 text-white font-mono animate-pulse">
                                {{ count($detail['apprenants_en_remediation']) }} en remédiation
                            </span>
                        </div>
                        <p class="text-xs text-rose-700 mt-0.5">
                            Le délai d'autonomie post-Live Coding ({{ $detail['kpis']['seuil_minutes'] ?? 30 }} min) est dépassé. Le formateur intervient auprès de ces apprenants puis valide leur tâche.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Cartes apprenants en remédiation (sans bouton d'action) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach($detail['apprenants_en_remediation'] as $bloque)
                <div class="bg-white p-4 rounded-xl border border-rose-200 shadow-2xs flex items-center justify-between gap-3 hover:border-rose-400 transition">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <img src="{{ $bloque['avatar'] }}" alt="{{ $bloque['nom_complet'] }}" class="w-9 h-9 rounded-full object-cover shrink-0 border border-slate-200">
                        <div class="min-w-0">
                            <div class="text-xs font-bold text-slate-900 truncate" title="{{ $bloque['nom_complet'] }}">
                                {{ $bloque['nom_complet'] }}
                            </div>
                            <div class="text-[11px] text-slate-500 truncate" title="{{ $bloque['tache_titre'] }}">
                                {{ $bloque['tache_titre'] }}
                            </div>
                        </div>
                    </div>
                    <div class="text-right shrink-0">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-black bg-rose-100 text-rose-700 font-mono">
                            {{ $bloque['retard_formate'] ?? '+30 min' }}
                        </span>
                        <span class="text-[10px] text-slate-400 block mt-0.5">dépassement</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    @else
        <!-- État Nominal : Aucune rupture -->
        <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4 sm:p-5 shadow-2xs flex items-center gap-3">
            <div class="w-9 h-9 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                <i class="fas fa-circle-check text-base"></i>
            </div>
            <div>
                <h4 class="text-xs sm:text-sm font-bold text-emerald-900">Autonomie Pédagogique Respectée</h4>
                <p class="text-xs text-emerald-700 mt-0.5">
                    Aucun apprenant n'est actuellement en situation de remédiation. Tous les stagiaires ont soumis leur livrable ou ont été validés par le formateur.
                </p>
            </div>
        </div>
    @endif

    <!-- 4. Table des Apprenants en Remédiation (Sans action formateur) -->
    <section class="bg-white rounded-lg border border-slate-200 shadow-xs overflow-hidden space-y-4">
        <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-3">
            <div>
                <div class="flex items-center gap-2">
                    <h3 class="text-sm sm:text-base font-bold text-slate-900">
                        Apprenants en Remédiation
                    </h3>
                    <span class="px-2 py-0.5 rounded-full text-xs font-black bg-rose-100 text-rose-700 font-mono">
                        {{ count($detail['apprenants_en_remediation'] ?? []) }} en attente
                    </span>
                </div>
                <p class="text-xs text-slate-500 mt-0.5">
                    Seuls les apprenants actuellement en état <strong>« En remédiation »</strong> sont listés. Le formateur intervient auprès d'eux puis valide leur tâche.
                </p>
            </div>

            <!-- Filtre recherche -->
            <div class="flex items-center gap-2">
                <div class="relative">
                    <i class="fas fa-search text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 text-xs"></i>
                    <input type="text" 
                           x-model="searchQuery" 
                           placeholder="Rechercher un apprenant..." 
                           class="text-xs rounded-lg border-slate-200 pl-8 pr-3 py-1.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 w-52 sm:w-64">
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 border-y border-slate-100 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="py-3 px-4">#</th>
                        <th class="py-3 px-4">Apprenant</th>
                        <th class="py-3 px-4">Tâche / Tutoriel concerné</th>
                        <th class="py-3 px-4">État Workflow</th>
                        <th class="py-3 px-4">Dépassement post-Live Coding</th>
                        <th class="py-3 px-4">Consigne Didactique</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($detail['apprenants_en_remediation'] ?? [] as $index => $appr)
                    <tr x-show="!searchQuery || '{{ addslashes(mb_strtolower($appr['nom_complet'])) }}'.includes(searchQuery.toLowerCase())" 
                        class="hover:bg-slate-50/80 transition">
                        <!-- Rang -->
                        <td class="py-3 px-4 font-mono text-slate-400 font-bold">
                            #{{ $index + 1 }}
                        </td>

                        <!-- Apprenant -->
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-2.5">
                                <img src="{{ $appr['avatar'] }}" alt="{{ $appr['nom_complet'] }}" class="w-8 h-8 rounded-full object-cover shrink-0 border border-slate-200">
                                <div class="min-w-0">
                                    <div class="font-bold text-slate-900 truncate">{{ $appr['nom_complet'] }}</div>
                                    <div class="text-[10px] text-slate-400">ID #{{ $appr['apprenant_id'] }}{{ !empty($appr['matricule']) ? ' &bull; ' . $appr['matricule'] : '' }}</div>
                                </div>
                            </div>
                        </td>

                        <!-- Tâche -->
                        <td class="py-3 px-4">
                            <span class="font-semibold text-slate-800">{{ $appr['tache_titre'] }}</span>
                        </td>

                        <!-- État Workflow -->
                        <td class="py-3 px-4">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200 flex items-center gap-1 w-fit">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                <span>{{ $appr['wf_label'] }}</span>
                            </span>
                        </td>

                        <!-- Dépassement -->
                        <td class="py-3 px-4">
                            <span class="text-rose-600 font-bold text-[11px] font-mono flex items-center gap-1">
                                <i class="fas fa-clock text-rose-500"></i>
                                <span>{{ $appr['retard_formate'] }}</span>
                            </span>
                        </td>

                        <!-- Consigne Didactique -->
                        <td class="py-3 px-4 text-slate-500 text-[11px]">
                            Accompagnement formateur en cours &bull; Valider la tâche une fois débloquée.
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-10 text-center">
                            <div class="flex flex-col items-center justify-center space-y-2">
                                <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center">
                                    <i class="fas fa-check text-lg"></i>
                                </div>
                                <div class="text-sm font-bold text-slate-800">Aucun apprenant en remédiation</div>
                                <div class="text-xs text-slate-400 max-w-sm">
                                    Tous les apprenants de la promotion ont validé leur livrable dans les temps impartis ou ont été certifiés par le formateur.
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <!-- 4-bis. Mémoire Pédagogique & Remédiations Résolues (Validées) -->
    @if(!empty($detail['apprenants_remediations_resolues']))
    <section class="bg-white rounded-lg border border-slate-200 shadow-xs overflow-hidden space-y-4">
        <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-3 bg-emerald-50/30">
            <div>
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs">
                        <i class="fas fa-certificate"></i>
                    </div>
                    <h3 class="text-sm sm:text-base font-bold text-slate-900">
                        Remédiations Résolues & Validées
                    </h3>
                    <span class="px-2 py-0.5 rounded-full text-xs font-black bg-emerald-100 text-emerald-800 font-mono">
                        {{ count($detail['apprenants_remediations_resolues']) }} résolue(s)
                    </span>
                </div>
                <p class="text-xs text-slate-500 mt-0.5">
                    Historique des apprenants ayant bénéficié d'une remédiation post-Live Coding et désormais validés avec succès par le formateur.
                </p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 border-y border-slate-100 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="py-3 px-4">#</th>
                        <th class="py-3 px-4">Apprenant</th>
                        <th class="py-3 px-4">Tâche Validée</th>
                        <th class="py-3 px-4">Statut Didactique</th>
                        <th class="py-3 px-4">Date Résolution</th>
                        <th class="py-3 px-4">Diagnostic Traité</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @foreach($detail['apprenants_remediations_resolues'] as $idx => $res)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="py-3 px-4 font-mono text-slate-400 font-bold">
                            #{{ $idx + 1 }}
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-2.5">
                                <img src="{{ $res['avatar'] }}" alt="{{ $res['nom_complet'] }}" class="w-8 h-8 rounded-full object-cover shrink-0 border border-slate-200">
                                <div class="min-w-0">
                                    <div class="font-bold text-slate-900 truncate">{{ $res['nom_complet'] }}</div>
                                    <div class="text-[10px] text-slate-400">ID #{{ $res['apprenant_id'] }}{{ !empty($res['matricule']) ? ' &bull; ' . $res['matricule'] : '' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <span class="font-semibold text-slate-800">{{ $res['tache_titre'] }}</span>
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200 inline-flex items-center gap-1">
                                <i class="fas fa-check-circle text-emerald-600"></i>
                                <span>Résolu & Validé</span>
                            </span>
                        </td>
                        <td class="py-3 px-4 font-mono text-slate-600 text-[11px]">
                            {{ $res['date_resolution'] }}
                        </td>
                        <td class="py-3 px-4 text-slate-500 text-[11px] truncate max-w-xs" title="{{ $res['diagnostic'] }}">
                            {{ $res['diagnostic'] ?? 'Dépassement seuil autonomie post-Live Coding' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
    @endif

    <!-- 5. Matrice de Benchmark Inter-Sessions (Formateur / Responsable) -->
    @if(!empty($detail['benchmark_sessions']))
    <section class="bg-white rounded-lg border border-slate-200 shadow-xs overflow-hidden space-y-3">
        <div class="p-4 sm:p-5 border-b border-slate-100">
            <h4 class="text-sm sm:text-base font-bold text-slate-900">
                Benchmark Inter-Sessions : Taux d'Intervention & Ruptures
            </h4>
            <p class="text-xs text-slate-500 mt-0.5">
                Comparatif de réactivité didactique entre les différentes sessions actives et promotions.
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 border-y border-slate-100 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="py-3 px-4">Groupe</th>
                        <th class="py-3 px-4">Session / Projet</th>
                        <th class="py-3 px-4">Taux Remédiation</th>
                        <th class="py-3 px-4">En Rupture</th>
                        <th class="py-3 px-4">Zone Didactique</th>
                        <th class="py-3 px-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @foreach($detail['benchmark_sessions'] as $bench)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="py-3 px-4 font-bold text-slate-900">{{ $bench['groupe_code'] }}</td>
                        <td class="py-3 px-4 text-slate-700 truncate max-w-xs">{{ $bench['projet_titre'] }}</td>
                        <td class="py-3 px-4 font-black font-mono text-slate-900">{{ $bench['taux_intervention_formate'] }}</td>
                        <td class="py-3 px-4">
                            <span class="font-bold {{ $bench['en_attente_soutien'] > 0 ? 'text-rose-600 font-mono' : 'text-slate-400' }}">
                                {{ $bench['en_attente_soutien'] }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $bench['badge_class'] }}">
                                {{ $bench['badge_label'] }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-right">
                            <a href="{{ route('admin.PkgTableauBord.indicateurs.intervention', ['affectation_id' => $bench['affectation_id']]) }}" 
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
