@extends('layouts.admin-v2')

@section('title', 'Détail Indicateur 04 — Charge Logistique Réelle du Formateur')
@section('page_title', 'Indicateur 04 : Charge Logistique Réelle du Formateur (C_logistique)')
@section('page_subtitle', 'Modèle déterministe par acte : conception de session, mobilisations d\'UA, tâches, affectations, suivi, validations et rédaction de feedbacks')

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
    }
}">

    <!-- En-tête contextuel & Fiche Didactique de l'Indicateur -->
    <section class="bg-white rounded-lg border border-slate-200 p-5 sm:p-6 shadow-xs space-y-4">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div class="space-y-1.5">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200 flex items-center gap-1.5">
                        <i class="fas fa-calculator text-indigo-500"></i>
                        <span>Indicateur Clé #04</span>
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                        Plafond Cible : &le; {{ $detail['cible_max'] }}h ({{ $detail['nb_semaines'] ?? 2 }} sem.)
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
                    @elseif($projetActif)
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-100 text-indigo-800 border border-indigo-200 truncate max-w-[280px]">
                            Session : {{ $projetActif->titre }}
                        </span>
                    @endif
                </div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                    Charge Logistique Réelle du Formateur (C<sub>logistique</sub>)
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 max-w-4xl leading-relaxed">
                    Évaluation déterministe basée sur les actes réels de gestion : modélisation de session, mobilisations d'UA (1 min), conception des tâches (3 min), affectation (3 min), suivi administratif des tâches (2 min / tâche, gestion hors pédagogie), validations (1 min / tâche validée) et rédaction de feedbacks (1 min / tâche avec feedback). La consultation du Tableau de bord (pédagogie pure) et la génération des notes (automatisée) sont décomptées à 0 min.
                </p>
            </div>

            <!-- Synthèse Globale -->
            <div class="shrink-0 flex items-center gap-4 bg-slate-50 p-3.5 rounded-lg border border-slate-200">
                <div class="text-center px-1">
                    <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">
                        {{ $projetActif ? 'Charge Session' : 'Moyenne Formateur' }}
                    </span>
                    <span class="text-2xl sm:text-3xl font-black {{ $detail['charge_moyenne_heures'] <= $detail['seuil_optimal'] ? 'text-emerald-700' : ($detail['charge_moyenne_heures'] <= $detail['seuil_alerte'] ? 'text-amber-600' : 'text-rose-700') }} font-mono">
                        {{ $detail['charge_moyenne_formatee'] }}
                    </span>
                    <span class="text-[10px] text-slate-500 block font-medium">heures estimées</span>
                </div>
                <div class="h-10 w-px bg-slate-200"></div>
                <div class="text-center px-1">
                    <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">Disponibilité Tutorat</span>
                    <span class="text-2xl sm:text-3xl font-black text-emerald-700 font-mono">
                        {{ $detail['taux_disponibilite_formate'] }}
                    </span>
                    <span class="text-[10px] text-slate-500 block font-medium">base {{ $detail['volume_hebdo'] ?? 26 }}h/sem</span>
                </div>
            </div>
        </div>
    </section>

    <!-- 5 Cartes Métriques Clés -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">

        {{-- 1. Charge Totale --}}
        <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-2xs hover:shadow-sm transition">
            <div class="flex items-start justify-between gap-2 mb-2">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">{{ $projetActif ? 'Charge Session' : 'Charge Moyenne' }}</span>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $detail['statut_badge'] }}">
                    {{ $detail['statut'] }}
                </span>
            </div>
            <div class="flex items-baseline gap-2 mb-1">
                <span class="text-2xl sm:text-3xl font-black text-slate-900 font-mono">
                    {{ $detail['charge_moyenne_formatee'] }}
                </span>
                <span class="text-xs text-slate-400 font-medium">{{ $projetActif ? 'estimée' : '/ session' }}</span>
            </div>
            <p class="text-xs text-slate-500 leading-snug">
                Plafond cible fixé à &le; {{ $detail['cible_max'] }}h ({{ $detail['nb_semaines'] ?? 2 }} sem. &times; 3h).
            </p>
            <div class="mt-3 pt-2 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                <span>{{ $projetActif ? 'Charge session :' : 'Moyenne / session :' }}</span>
                <strong class="font-mono text-slate-800">{{ $detail['charge_moyenne_formatee'] }}</strong>
            </div>
        </div>

        {{-- 2. Taux de Disponibilité Tutorat --}}
        <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-2xs hover:shadow-sm transition">
            <div class="flex items-start justify-between gap-2 mb-2">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Disponibilité Tutorat</span>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 font-mono">
                    &ge; 90% cible
                </span>
            </div>
            <div class="flex items-baseline gap-2 mb-1">
                <span class="text-2xl sm:text-3xl font-black text-emerald-700 font-mono">
                    {{ $detail['taux_disponibilite_formate'] }}
                </span>
            </div>
            <p class="text-xs text-slate-500 leading-snug">
                Temps sanctuarisé pour l'encadrement direct et le coaching des apprenants.
            </p>
            <div class="mt-3 pt-2 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                <span>Temps coaching :</span>
                <strong class="font-mono text-slate-800">~{{ $detail['temps_coaching_hebdo'] ?? 22 }}h / sem</strong>
            </div>
        </div>

        {{-- 3. Conception & Préparation --}}
        <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-2xs hover:shadow-sm transition">
            <div class="flex items-start justify-between gap-2 mb-2">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Conception Session</span>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200 font-mono">
                    {{ $detail['details_globaux']['conception_pct'] }}% mix
                </span>
            </div>
            <div class="flex items-baseline gap-2 mb-1">
                <span class="text-2xl sm:text-3xl font-black text-indigo-700 font-mono">
                    {{ $detail['details_globaux']['conception_formate'] ?? ($detail['details_globaux']['conception_heures'] . 'h') }}
                </span>
            </div>
            <p class="text-xs text-slate-500 leading-snug">
                Projets (15m), UA (1m), Tâches (3m), Affectation (3m).
            </p>
            <div class="mt-3 pt-2 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                <span>Poste :</span>
                <span class="font-mono text-slate-700">T_conception</span>
            </div>
        </div>

        {{-- 4. Suivi Opérationnel --}}
        <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-2xs hover:shadow-sm transition">
            <div class="flex items-start justify-between gap-2 mb-2">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Suivi Opérationnel</span>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-sky-50 text-sky-700 border border-sky-200 font-mono">
                    {{ $detail['details_globaux']['suivi_pct'] }}% mix
                </span>
            </div>
            <div class="flex items-baseline gap-2 mb-1">
                <span class="text-2xl sm:text-3xl font-black text-sky-700 font-mono">
                    {{ $detail['details_globaux']['suivi_formate'] ?? ($detail['details_globaux']['suivi_heures'] . 'h') }}
                </span>
            </div>
            <p class="text-xs text-slate-500 leading-snug">
                Suivi administratif des tâches du projet (2 min / tâche, gestion hors pédagogie).
            </p>
            <div class="mt-3 pt-2 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                <span>Poste :</span>
                <span class="font-mono text-slate-700">T_suivi</span>
            </div>
        </div>

        {{-- 5. Validations & Feedbacks --}}
        <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-2xs hover:shadow-sm transition">
            <div class="flex items-start justify-between gap-2 mb-2">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Validations & Feedbacks</span>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200 font-mono">
                    {{ $detail['details_globaux']['validation_pct'] + $detail['details_globaux']['feedback_pct'] }}% mix
                </span>
            </div>
            <div class="flex items-baseline gap-2 mb-1">
                <span class="text-2xl sm:text-3xl font-black text-amber-700 font-mono">
                    {{ $detail['details_globaux']['val_feedback_formate'] ?? ($detail['details_globaux']['validation_formate'] . ' + ' . $detail['details_globaux']['feedback_formate']) }}
                </span>
            </div>
            <p class="text-xs text-slate-500 leading-snug">
                Validations livrables (1m/tâche) et feedbacks écrits (1m/tâche avec feedback).
            </p>
            <div class="mt-3 pt-2 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                <span>Postes :</span>
                <span class="font-mono text-slate-700">T_val + T_feedback</span>
            </div>
        </div>
    </div>

    <!-- Ventilation Visuelle du Mix d'Activité Déterministe -->
    <section class="bg-white rounded-lg border border-slate-200 p-5 shadow-xs space-y-3">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                <h2 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                    <i class="fas fa-chart-pie text-indigo-500"></i>
                    <span>Ventilation Proportionnelle de l'Effort Logistique Réel</span>
                </h2>
                <p class="text-xs text-slate-500">
                    Répartition de la charge logistique calculée sur les actes didactiques réels enregistrés en base de données.
                </p>
            </div>
            <span class="text-xs font-mono font-bold text-slate-600 bg-slate-100 px-2.5 py-1 rounded">
                {{ $projetActif ? 'Charge session :' : 'Moyenne par session :' }} {{ $detail['charge_moyenne_formatee'] }}
            </span>
        </div>

        <!-- Jauge d'activité segmentée -->
        <div class="w-full h-4 bg-slate-100 rounded-full overflow-hidden flex shadow-inner">
            <div class="bg-indigo-600 transition-all duration-500" 
                 style="width: {{ $detail['details_globaux']['conception_pct'] }}%" 
                 title="Conception : {{ $detail['details_globaux']['conception_pct'] }}% ({{ $detail['details_globaux']['conception_heures'] }}h)"></div>
            <div class="bg-sky-500 transition-all duration-500" 
                 style="width: {{ $detail['details_globaux']['suivi_pct'] }}%" 
                 title="Suivi Opérationnel : {{ $detail['details_globaux']['suivi_pct'] }}% ({{ $detail['details_globaux']['suivi_heures'] }}h)"></div>
            <div class="bg-emerald-500 transition-all duration-500" 
                 style="width: {{ $detail['details_globaux']['validation_pct'] }}%" 
                 title="Validations : {{ $detail['details_globaux']['validation_pct'] }}% ({{ $detail['details_globaux']['validation_heures'] }}h)"></div>
            <div class="bg-amber-500 transition-all duration-500" 
                 style="width: {{ $detail['details_globaux']['feedback_pct'] }}%" 
                 title="Feedbacks : {{ $detail['details_globaux']['feedback_pct'] }}% ({{ $detail['details_globaux']['feedback_heures'] }}h)"></div>
        </div>

        <!-- Légende didactique avec barèmes paramétrés -->
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-3 pt-2 text-xs">
            <div class="flex items-start gap-2 p-2 rounded-lg bg-indigo-50/50 border border-indigo-100">
                <span class="w-3 h-3 rounded-full bg-indigo-600 shrink-0 mt-0.5"></span>
                <div>
                    <div class="flex items-center gap-1.5 font-bold text-slate-800">
                        <span>Conception Didactique</span>
                        <span class="text-indigo-700 font-mono">({{ $detail['details_globaux']['conception_pct'] }}%)</span>
                    </div>
                    <span class="text-[11px] text-slate-500 block">Projets (15m), UA (1m), Tâches (3m), Affectation (3m).</span>
                </div>
            </div>
            <div class="flex items-start gap-2 p-2 rounded-lg bg-sky-50/50 border border-sky-100">
                <span class="w-3 h-3 rounded-full bg-sky-500 shrink-0 mt-0.5"></span>
                <div>
                    <div class="flex items-center gap-1.5 font-bold text-slate-800">
                        <span>Suivi Opérationnel</span>
                        <span class="text-sky-700 font-mono">({{ $detail['details_globaux']['suivi_pct'] }}%)</span>
                    </div>
                    <span class="text-[11px] text-slate-500 block">1 min forfaitaire par tâche assignée et suivie.</span>
                </div>
            </div>
            <div class="flex items-start gap-2 p-2 rounded-lg bg-emerald-50/50 border border-emerald-100">
                <span class="w-3 h-3 rounded-full bg-emerald-500 shrink-0 mt-0.5"></span>
                <div>
                    <div class="flex items-center gap-1.5 font-bold text-slate-800">
                        <span>Validations Pédagogiques</span>
                        <span class="text-emerald-700 font-mono">({{ $detail['details_globaux']['validation_pct'] }}%)</span>
                    </div>
                    <span class="text-[11px] text-slate-500 block">1 min par livrable approuvé dans le workflow.</span>
                </div>
            </div>
            <div class="flex items-start gap-2 p-2 rounded-lg bg-amber-50/50 border border-amber-100">
                <span class="w-3 h-3 rounded-full bg-amber-500 shrink-0 mt-0.5"></span>
                <div>
                    <div class="flex items-center gap-1.5 font-bold text-slate-800">
                        <span>Feedbacks Écrits</span>
                        <span class="text-amber-700 font-mono">({{ $detail['details_globaux']['feedback_pct'] }}%)</span>
                    </div>
                    <span class="text-[11px] text-slate-500 block">1 min par tâche ayant reçu un feedback (gestion hors pédagogie).</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Tableau Comparatif (Benchmark Formateurs) -->
    <section class="bg-white rounded-lg border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-4 sm:p-5 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h2 class="text-sm sm:text-base font-bold text-slate-900 flex items-center gap-2">
                    <i class="fas fa-users-gear text-indigo-500"></i>
                    <span>Benchmark Individuel des Actes Logistiques par Formateur</span>
                </h2>
                <p class="text-xs text-slate-500">
                    Décomposition quantitative des actes réels et calcul déterministe de la charge administrative.
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
                        <th class="py-3 px-3 text-center">Charge {{ $projetActif ? 'Session' : 'Moyenne / Session' }} (C<sub>logistique</sub>)</th>
                        <th class="py-3 px-3 text-center">Conception</th>
                        <th class="py-3 px-3 text-center">Suivi Tâches</th>
                        <th class="py-3 px-3 text-center">Validations</th>
                        <th class="py-3 px-3 text-center">Feedbacks</th>
                        <th class="py-3 px-3 text-center">Disponibilité Tutorat</th>
                        <th class="py-3 px-3 text-center">Statut Didactique</th>
                        <th class="py-3 px-4 text-right">Décompte</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($detail['benchmark'] as $f)
                    <tr class="hover:bg-slate-50/80 transition"
                        x-show="(statusFilter === 'tous' || statusFilter === '{{ $f['zone'] }}') && 
                                ('{{ addslashes(strtolower($f['formateur_nom'])) }}'.includes(searchQuery.toLowerCase()) || 
                                 '{{ addslashes(strtolower($f['matricule'])) }}'.includes(searchQuery.toLowerCase()))">
                        
                        {{-- Formateur --}}
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center font-bold text-slate-600 text-xs shrink-0">
                                    {{ $f['initiales'] }}
                                </div>
                                <div class="min-w-0">
                                    <div class="font-bold text-slate-900 truncate">{{ $f['formateur_nom'] }}</div>
                                    <div class="text-[10px] text-slate-400 font-mono">Matricule : {{ $f['matricule'] }}</div>
                                </div>
                            </div>
                        </td>

                        {{-- Temps Logistique Total --}}
                        <td class="py-3 px-3 text-center">
                            <div class="inline-flex flex-col items-center">
                                <span class="font-black text-slate-900 font-mono text-sm">
                                    {{ $f['total_heures_formate'] }}
                                </span>
                                @if(!empty($f['is_moyenne_sessions']))
                                    <span class="text-[9px] text-slate-400 font-mono block">moy. / session ({{ $f['nb_sessions'] }} sess.)</span>
                                @endif
                                <!-- Mini-jauge relative au plafond de session -->
                                <div class="w-16 h-1 bg-slate-100 rounded-full overflow-hidden mt-1">
                                    <div class="{{ $f['zone'] === 'alerte' ? 'bg-rose-500' : ($f['zone'] === 'vigilance' ? 'bg-amber-500' : 'bg-indigo-600') }} h-full"
                                         style="width: {{ $f['pourcentage_cible'] }}%"></div>
                                </div>
                            </div>
                        </td>

                        {{-- Conception --}}
                        <td class="py-3 px-3 text-center">
                            <div class="font-mono font-bold text-indigo-700">{{ $f['details']['conception_formate'] ?? ($f['details']['conception_heures'] . 'h') }}</div>
                            <span class="text-[10px] text-slate-400 font-mono">
                                {{ $f['quantites']['nb_projets'] }}P / {{ $f['quantites']['nb_mobilisations'] }}UA / {{ $f['quantites']['nb_taches_creees'] }}T
                            </span>
                        </td>

                        {{-- Suivi Opérationnel --}}
                        <td class="py-3 px-3 text-center">
                            <div class="font-mono font-bold text-sky-700">{{ $f['details']['suivi_formate'] ?? ($f['details']['suivi_heures'] . 'h') }}</div>
                            <span class="text-[10px] text-slate-400 font-mono">{{ $f['quantites']['nb_suivi'] }} tâches</span>
                        </td>

                        {{-- Validations --}}
                        <td class="py-3 px-3 text-center">
                            <div class="font-mono font-bold text-emerald-700">{{ $f['details']['validation_formate'] ?? ($f['details']['validation_heures'] . 'h') }}</div>
                            <span class="text-[10px] text-slate-400 font-mono">{{ $f['quantites']['nb_validations'] }} validées</span>
                        </td>

                        {{-- Feedbacks --}}
                        <td class="py-3 px-3 text-center">
                            <div class="font-mono font-bold text-amber-700">{{ $f['details']['feedback_formate'] ?? ($f['details']['feedback_heures'] . 'h') }}</div>
                            <span class="text-[10px] text-slate-400 font-mono">{{ $f['quantites']['nb_feedbacks_distincts'] }} feedbacks</span>
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
                                <i class="fas fa-list-check text-[10px]"></i>
                                <span>Décompte</span>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-8 text-slate-400">
                            Aucune activité enregistrée pour le contexte sélectionné.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <!-- Guide Méthodologique & Explication Didactique -->
    <div class="bg-slate-50 rounded-lg border border-slate-200 p-4 sm:p-5 text-xs text-slate-600 space-y-2">
        <h3 class="font-bold text-slate-900 flex items-center gap-1.5 text-xs">
            <i class="fas fa-circle-info text-indigo-500"></i>
            <span>Méthodologie Déterministe de Calcul de la Charge Logistique (C<sub>logistique</sub>)</span>
        </h3>
        <p class="leading-relaxed">
            Contrairement à la télémétrie passive qui enregistrait indistinctement le temps d'écran, ce modèle comptabilise **les actes réels de gestion** selon des temps unitaires calibrés :
        </p>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 pt-1">
            <ul class="list-disc list-inside space-y-1 text-slate-600 ps-1">
                <li><strong>Conception de session :</strong> 15 min par projet + 1 min par mobilisation d'UA + 3 min par tâche rédigée + 3 min par affectation à un groupe.</li>
                <li><strong>Suivi opérationnel :</strong> 2 min par tâche créée pour la gestion administrative (contrôle kanban hors coaching direct).</li>
                <li><strong>Validation administrative :</strong> 1 min par tâche validée dans la session (gestion hors temps d'échange).</li>
            </ul>
            <ul class="list-disc list-inside space-y-1 text-slate-600 ps-1">
                <li><strong>Feedbacks administratifs :</strong> 1 min par tâche ayant reçu au moins un feedback (gestion de traçabilité hors pédagogie).</li>
                <li><strong>Consultation du Dashboard (0 min) :</strong> Reconnue comme un acte d'observation pédagogique et d'évaluation formative, exclue de la bureaucratie.</li>
                <li><strong>Génération des notes (0 min) :</strong> Processus automatisé instantanément par le moteur docimologique de SoliLMS.</li>
            </ul>
        </div>
    </div>

    <!-- Modale Décompte Détaillé Formateur -->
    <template x-if="activeModalFormateur">
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs"
             @click.self="closeModal()"
             @keydown.escape.window="closeModal()">
            <div class="bg-white rounded-xl shadow-xl border border-slate-200 max-w-2xl w-full p-5 space-y-4 max-h-[90vh] overflow-y-auto">
                <div class="flex items-start justify-between border-b border-slate-100 pb-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-indigo-50 text-indigo-700 font-bold flex items-center justify-center text-sm"
                             x-text="activeModalFormateur?.initiales"></div>
                        <div>
                            <h4 class="font-bold text-slate-900" x-text="activeModalFormateur?.formateur_nom"></h4>
                            <span class="text-xs text-slate-400 font-mono" x-text="'Matricule : ' + activeModalFormateur?.matricule"></span>
                        </div>
                    </div>
                    <button type="button" @click="closeModal()" class="text-slate-400 hover:text-slate-600 p-1">
                        <i class="fas fa-times text-sm"></i>
                    </button>
                </div>

                <div class="space-y-4 text-xs">
                    <!-- Synthèse Formateur -->
                    <div class="grid grid-cols-3 gap-3 bg-slate-50 p-3 rounded-lg border border-slate-200 font-mono text-center">
                        <div>
                            <span class="text-slate-400 block text-[10px] uppercase font-sans">Charge Logistique</span>
                            <strong class="text-slate-900 text-base" x-text="activeModalFormateur?.total_heures_formate"></strong>
                            <span class="text-slate-400 block text-[10px]" x-text="'(' + activeModalFormateur?.total_heures_dec + ')'"></span>
                        </div>
                        <div>
                            <span class="text-slate-400 block text-[10px] uppercase font-sans">Disponibilité Tutorat</span>
                            <strong class="text-emerald-700 text-base" x-text="activeModalFormateur?.taux_disponibilite + '%'"></strong>
                            <span class="text-slate-500 block text-[10px]" x-text="'base ' + (activeModalFormateur?.volume_hebdo || 26) + 'h/sem'"></span>
                        </div>
                        <div>
                            <span class="text-slate-400 block text-[10px] uppercase font-sans">Diagnostic</span>
                            <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold mt-1"
                                  :class="activeModalFormateur?.badge_class"
                                  x-text="activeModalFormateur?.badge_label"></span>
                        </div>
                    </div>

                    <!-- Décomposition Arithmétique Tableau -->
                    <div class="space-y-2">
                        <h5 class="font-bold text-slate-800 text-xs">Décomposition Arithmétique par Acte de Gestion :</h5>
                        <div class="overflow-x-auto border border-slate-200 rounded-lg">
                            <table class="w-full text-left text-xs border-collapse">
                                <thead>
                                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-semibold text-[10px] uppercase tracking-wider">
                                        <th class="p-2">Poste</th>
                                        <th class="p-2">Activité / Acte</th>
                                        <th class="p-2 text-center">Quantité</th>
                                        <th class="p-2 text-center">Barème Unitaire</th>
                                        <th class="p-2 text-right">Durée (H:Min)</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 text-slate-700 font-mono text-[11px]">
                                    <template x-for="(item, idx) in activeModalFormateur?.decompte_items" :key="idx">
                                        <tr class="hover:bg-slate-50/60">
                                            <td class="p-2 font-bold font-sans" 
                                                :class="item.poste === 'Conception' ? 'text-indigo-600' : (item.poste === 'Suivi' ? 'text-sky-600' : (item.poste === 'Validation' ? 'text-emerald-600' : (item.poste === 'Feedbacks' ? 'text-amber-600' : 'text-slate-400')))" 
                                                x-text="item.poste"></td>
                                            <td class="p-2 font-sans text-slate-800" x-text="item.action"></td>
                                            <td class="p-2 text-center" x-text="item.quantite"></td>
                                            <td class="p-2 text-center text-slate-500 text-[10px]" x-text="item.bareme"></td>
                                            <td class="p-2 text-right font-bold text-slate-900" x-text="item.total_duree_formate || (item.total_heures + 'h')"></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="p-3 rounded-lg border text-xs"
                         :class="activeModalFormateur?.zone === 'alerte' ? 'bg-rose-50 border-rose-200 text-rose-800' : (activeModalFormateur?.zone === 'vigilance' ? 'bg-amber-50 border-amber-200 text-amber-800' : 'bg-emerald-50 border-emerald-200 text-emerald-800')">
                        <strong class="block mb-1" x-text="'Recommandation Didactique : ' + activeModalFormateur?.badge_label"></strong>
                        <template x-if="activeModalFormateur?.zone === 'optimal'">
                            <span>Charge logistique exemplaire. Le formateur consacre l'essentiel de sa semaine à l'accompagnement direct et aux rétroactions formatives.</span>
                        </template>
                        <template x-if="activeModalFormateur?.zone === 'vigilance'">
                            <span>Charge administrative notable. Veiller à ce que les tâches de conception et de saisie de remarques n'érodent pas le temps de coaching en présentiel.</span>
                        </template>
                        <template x-if="activeModalFormateur?.zone === 'alerte'">
                            <span>Surcharge bureaucratique critique. Il est recommandé de mutualiser les projets ou de simplifier les tâches d'évaluation pour libérer du temps apprenant.</span>
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
