@extends('layouts.admin-v2')

@section('title', 'Détail Indicateur 03 — Fréquence des Révisions & Stabilité du Premier Jet')
@section('page_title', 'Indicateur 03 : Fréquence des Révisions & Stabilité du Premier Jet (F_révision)')
@section('page_subtitle', 'Analyse approfondie des boucles d\'itération, messages formateurs, stabilité du code dès le premier rendu et points de friction didactiques')

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
    activeModalApprenant: null,
    openModal(appr) {
        this.activeModalApprenant = appr;
    },
    closeModal() {
        this.activeModalApprenant = null;
    }
}">

    <!-- En-tête contextuel & Fiche Didactique de l'Indicateur -->
    <section class="bg-white rounded-lg border border-slate-200 p-5 sm:p-6 shadow-xs space-y-4">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div class="space-y-1.5">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200 flex items-center gap-1.5">
                        <i class="fas fa-arrows-rotate text-indigo-500"></i>
                        <span>Indicateur Clé #03</span>
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                        Objectif Optimal : &lt; {{ $detail['seuil_optimal'] }} itér./tâche
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                        Seuil Alerte : &gt; {{ $detail['seuil_alerte'] }}
                    </span>
                    @if(!empty($isVisionGlobale))
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200 flex items-center gap-1">
                            <i class="fas fa-globe text-[11px] text-emerald-600"></i>
                            <span>Vision Globale ({{ count($detail['benchmark']) }} sessions)</span>
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
                    Fréquence des Boucles de Révision & Stabilité du Premier Jet (F<sub>révision</sub>)
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 max-w-4xl leading-relaxed">
                    Mesure le nombre moyen de reprises exigées par le formateur avant validation d'une tâche. Un indicateur bas (&lt; 0.5) atteste d'une qualité initiale élevée du code et de consignes limpides. Un indicateur élevé (&gt; 1.2) révèle une démarche par essais-erreurs, une surcharge cognitive ou des critères d'acceptation ambigus.
                </p>
            </div>

            <!-- Jauge synthétique globale -->
            <div class="shrink-0 flex items-center gap-4 bg-slate-50 p-3.5 rounded-lg border border-slate-200">
                <div class="text-center">
                    <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">Fréquence Moyenne (F<sub>révision</sub>)</span>
                    <span class="text-2xl sm:text-3xl font-black {{ $detail['frequence_moyenne'] < $detail['seuil_optimal'] ? 'text-emerald-700' : ($detail['frequence_moyenne'] <= $detail['seuil_alerte'] ? 'text-amber-600' : 'text-rose-700') }} font-mono">
                        {{ $detail['frequence_moyenne'] }}
                    </span>
                    <span class="text-[10px] text-slate-500 block font-medium">
                        reprise / tâche
                    </span>
                </div>
                <div class="h-10 w-px bg-slate-200"></div>
                <div class="text-center">
                    <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">Premier Jet Direct</span>
                    <span class="text-2xl sm:text-3xl font-black text-emerald-700 font-mono">
                        {{ $detail['taux_premier_jet_formate'] }}
                    </span>
                    <span class="text-[10px] text-slate-500 block font-medium">
                        {{ $detail['taches_sans_reprise'] }}/{{ $detail['total_taches'] }} direct
                    </span>
                </div>
            </div>
        </div>
    </section>

    <!-- 5 Cartes Métriques Clés -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">

        {{-- 1. Fréquence Globale --}}
        <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-2xs hover:shadow-sm transition">
            <div class="flex items-start justify-between gap-2 mb-2">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Fréquence Moyenne</span>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $detail['statut_badge'] }}">
                    {{ $detail['statut'] }}
                </span>
            </div>
            <div class="my-2 flex items-baseline gap-2">
                <span class="text-3xl font-black text-slate-900 font-mono tracking-tight">{{ $detail['frequence_moyenne'] }}</span>
                <span class="text-xs text-slate-400 font-medium">itér. / tâche</span>
            </div>
            <div class="pt-2.5 mt-2 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <span>Cible didactique :</span>
                <strong class="text-slate-800 font-mono">&lt; {{ $detail['seuil_optimal'] }} itér.</strong>
            </div>
        </div>

        {{-- 2. Taux de Premier Jet Direct --}}
        <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-2xs hover:shadow-sm transition">
            <div class="flex items-start justify-between gap-2 mb-2">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Stabilité 1<sup>er</sup> Jet</span>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    {{ $detail['taux_premier_jet_formate'] }}
                </span>
            </div>
            <div class="my-2 flex items-baseline gap-2">
                <span class="text-3xl font-black text-emerald-700 font-mono tracking-tight">
                    {{ $detail['taches_sans_reprise'] }}
                </span>
                <span class="text-xs text-slate-400 font-medium">tâches directes</span>
            </div>
            <div class="w-full bg-slate-100 rounded-full h-1.5 my-1.5 overflow-hidden">
                <div class="h-1.5 rounded-full transition-all duration-500 bg-emerald-500" 
                     style="width: {{ $detail['total_taches'] > 0 ? min(100, max(0, round(($detail['taches_sans_reprise'] / $detail['total_taches']) * 100))) : 0 }}%"></div>
            </div>
            <div class="pt-1 mt-1 text-[11px] text-slate-400 truncate">
                Validées sans aucun retour
            </div>
        </div>

        {{-- 3. Volume Total de Révisions --}}
        <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-2xs hover:shadow-sm transition">
            <div class="flex items-start justify-between gap-2 mb-2">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Rejets Formateur</span>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700">
                    Itérations
                </span>
            </div>
            <div class="my-2 flex items-baseline gap-2">
                <span class="text-3xl font-black text-slate-800 font-mono tracking-tight">{{ $detail['total_rejets'] }}</span>
                <span class="text-xs text-slate-400 font-medium">reprises cumulées</span>
            </div>
            <div class="pt-2.5 mt-2 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <span>Charge de relecture :</span>
                <strong class="text-slate-800 font-medium text-[11px]">{{ $detail['total_rejets'] > 0 ? 'Activité soutenue' : 'Minimale' }}</strong>
            </div>
        </div>

        {{-- 4. Tâches en Tâtonnement Critique --}}
        <div class="bg-white p-4 rounded-lg border {{ $detail['taches_critiques'] > 0 ? 'border-amber-200 bg-amber-50/20' : 'border-slate-200' }} shadow-2xs hover:shadow-sm transition">
            <div class="flex items-start justify-between gap-2 mb-2">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Tâtonnement (&ge; 2 reprises)</span>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $detail['taches_critiques'] > 0 ? 'bg-amber-100 text-amber-700 border border-amber-200' : 'bg-slate-100 text-slate-600' }}">
                    {{ $detail['taches_critiques'] > 0 ? 'Vigilance' : 'Aucun' }}
                </span>
            </div>
            <div class="my-2 flex items-baseline gap-2">
                <span class="text-3xl font-black text-amber-600 font-mono tracking-tight">{{ $detail['taches_critiques'] }}</span>
                <span class="text-xs text-slate-400 font-medium">tâches à friction</span>
            </div>
            <div class="pt-2.5 mt-2 border-t border-amber-100 flex items-center justify-between text-xs text-slate-500">
                <span>Consignes à clarifier :</span>
                <strong class="text-amber-800 font-mono text-xs">{{ $detail['taches_critiques'] }} cas</strong>
            </div>
        </div>

        {{-- 5. Cohorte Couverte --}}
        <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-2xs hover:shadow-sm transition">
            <div class="flex items-start justify-between gap-2 mb-2">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Périmètre Analysé</span>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                    Cohorte
                </span>
            </div>
            <div class="my-2 flex items-baseline gap-2">
                <span class="text-3xl font-black text-slate-700 font-mono tracking-tight">{{ $detail['total_taches'] }}</span>
                <span class="text-xs text-slate-400 font-medium">tâches réparties</span>
            </div>
            <div class="pt-2.5 mt-2 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500 truncate">
                <span>Apprenants évalués :</span>
                <strong class="text-slate-800 font-mono">{{ $detail['total_apprenants'] }} stagiaires</strong>
            </div>
        </div>

    </div>

    <!-- Histogramme de Répartition Didactique (4 Tranches de Stabilité) -->
    @php
        $totalT = max(1, $detail['total_taches']);
        $pctPremierJet = round(($detail['tranches']['premier_jet'] / $totalT) * 100, 1);
        $pctUneReprise = round(($detail['tranches']['une_reprise'] / $totalT) * 100, 1);
        $pctDeuxTrois = round(($detail['tranches']['deux_trois_reprises'] / $totalT) * 100, 1);
        $pctQuatrePlus = round(($detail['tranches']['quatre_plus'] / $totalT) * 100, 1);
    @endphp
    <section class="bg-white rounded-lg border border-slate-200 p-5 sm:p-6 shadow-xs">
        <div class="space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div>
                    <h2 class="text-sm sm:text-base font-bold text-slate-900 flex items-center gap-2">
                        <i class="fas fa-chart-column text-indigo-600"></i>
                        <span>Répartition des Tâches par Tranche de Stabilité Didactique</span>
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Ventilation des {{ $detail['total_taches'] }} tâches selon le nombre de renvois en révision exigés par le formateur.
                    </p>
                </div>
                <div class="flex items-center gap-3 text-xs font-semibold text-slate-500 font-mono">
                    <span>Total Tâches : <strong class="text-slate-800">{{ $detail['total_taches'] }}</strong></span>
                </div>
            </div>

            <!-- Barre Empilée Interactive -->
            <div class="w-full bg-slate-100 rounded-lg h-7 p-1 flex gap-1 overflow-hidden">
                @if($pctPremierJet > 0)
                    <div class="bg-emerald-500 rounded h-full flex items-center justify-center text-white text-[11px] font-bold font-mono transition-all duration-300 hover:opacity-90"
                         style="width: {{ $pctPremierJet }}%" 
                         title="Premier Jet (0 reprise) : {{ $detail['tranches']['premier_jet'] }} ({{ $pctPremierJet }}%)">
                        @if($pctPremierJet >= 10) {{ $pctPremierJet }}% @endif
                    </div>
                @endif
                @if($pctUneReprise > 0)
                    <div class="bg-sky-500 rounded h-full flex items-center justify-center text-white text-[11px] font-bold font-mono transition-all duration-300 hover:opacity-90"
                         style="width: {{ $pctUneReprise }}%" 
                         title="Ajustement mineur (1 reprise) : {{ $detail['tranches']['une_reprise'] }} ({{ $pctUneReprise }}%)">
                        @if($pctUneReprise >= 10) {{ $pctUneReprise }}% @endif
                    </div>
                @endif
                @if($pctDeuxTrois > 0)
                    <div class="bg-amber-400 rounded h-full flex items-center justify-center text-slate-900 text-[11px] font-bold font-mono transition-all duration-300 hover:opacity-90"
                         style="width: {{ $pctDeuxTrois }}%" 
                         title="Tâtonnement modéré (2-3 reprises) : {{ $detail['tranches']['deux_trois_reprises'] }} ({{ $pctDeuxTrois }}%)">
                        @if($pctDeuxTrois >= 10) {{ $pctDeuxTrois }}% @endif
                    </div>
                @endif
                @if($pctQuatrePlus > 0)
                    <div class="bg-rose-500 rounded h-full flex items-center justify-center text-white text-[11px] font-bold font-mono transition-all duration-300 hover:opacity-90"
                         style="width: {{ $pctQuatrePlus }}%" 
                         title="Difficulté critique (≥ 4 reprises) : {{ $detail['tranches']['quatre_plus'] }} ({{ $pctQuatrePlus }}%)">
                        @if($pctQuatrePlus >= 10) {{ $pctQuatrePlus }}% @endif
                    </div>
                @endif
            </div>

            <!-- Légende des 4 Tranches avec effectifs -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-2">
                <div class="flex items-center gap-2.5 p-2 rounded-lg bg-emerald-50/50 border border-emerald-100">
                    <span class="w-3 h-3 rounded-full bg-emerald-500 shrink-0"></span>
                    <div class="min-w-0">
                        <span class="text-[11px] font-bold text-slate-700 block truncate">Premier Jet (0 reprise)</span>
                        <span class="text-xs font-mono font-black text-emerald-800">{{ $detail['tranches']['premier_jet'] }}</span>
                        <span class="text-[10px] text-slate-400">({{ $pctPremierJet }}%)</span>
                    </div>
                </div>

                <div class="flex items-center gap-2.5 p-2 rounded-lg bg-sky-50/50 border border-sky-100">
                    <span class="w-3 h-3 rounded-full bg-sky-500 shrink-0"></span>
                    <div class="min-w-0">
                        <span class="text-[11px] font-bold text-slate-700 block truncate">Ajustement (1 reprise)</span>
                        <span class="text-xs font-mono font-black text-sky-800">{{ $detail['tranches']['une_reprise'] }}</span>
                        <span class="text-[10px] text-slate-400">({{ $pctUneReprise }}%)</span>
                    </div>
                </div>

                <div class="flex items-center gap-2.5 p-2 rounded-lg bg-amber-50/50 border border-amber-100">
                    <span class="w-3 h-3 rounded-full bg-amber-400 shrink-0"></span>
                    <div class="min-w-0">
                        <span class="text-[11px] font-bold text-slate-700 block truncate">Vigilance (2-3 reprises)</span>
                        <span class="text-xs font-mono font-black text-amber-800">{{ $detail['tranches']['deux_trois_reprises'] }}</span>
                        <span class="text-[10px] text-slate-400">({{ $pctDeuxTrois }}%)</span>
                    </div>
                </div>

                <div class="flex items-center gap-2.5 p-2 rounded-lg bg-rose-50/50 border border-rose-100">
                    <span class="w-3 h-3 rounded-full bg-rose-500 shrink-0"></span>
                    <div class="min-w-0">
                        <span class="text-[11px] font-bold text-slate-700 block truncate">Alerte (≥ 4 reprises)</span>
                        <span class="text-xs font-mono font-black text-rose-800">{{ $detail['tranches']['quatre_plus'] }}</span>
                        <span class="text-[10px] text-slate-400">({{ $pctQuatrePlus }}%)</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Palmarès Pareto des Tâches les Plus Révisées (Didactic Friction Points) -->
    <section class="bg-white rounded-lg border border-slate-200 p-5 sm:p-6 shadow-xs">
        <div class="space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div>
                    <h2 class="text-sm sm:text-base font-bold text-slate-900 flex items-center gap-2">
                        <i class="fas fa-bullseye text-amber-500"></i>
                        <span>Didactic Friction Points — Tâches Concentrant le Plus de Reprises</span>
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Détection automatique des tâches présentant une friction anormale pour réviser et expliciter les consignes formateurs.
                    </p>
                </div>
                <span class="px-2.5 py-1 rounded-md text-[11px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                    Top {{ count($detail['taches_pareto']) }} Tâches Révélatrices
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-[11px] uppercase tracking-wider text-slate-500 font-bold">
                            <th class="py-2.5 px-3">Tâche Pédagogique</th>
                            <th class="py-2.5 px-3 text-center">Total Rejets</th>
                            <th class="py-2.5 px-3 text-center">Apprenants Touchés</th>
                            <th class="py-2.5 px-3 text-center">Fréquence Moy.</th>
                            <th class="py-2.5 px-3">Diagnostic Didactique</th>
                            <th class="py-2.5 px-3">Dernier Feedback Formateur Relevé</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs">
                        @forelse($detail['taches_pareto'] as $tp)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-3 px-3 font-bold text-slate-900 min-w-[200px]">
                                    <div class="flex items-center gap-2">
                                        <span class="w-6 h-6 rounded-full bg-indigo-50 text-indigo-700 flex items-center justify-center font-bold text-[10px] shrink-0 border border-indigo-200">
                                            #{{ $tp['tache_id'] }}
                                        </span>
                                        <span class="truncate" title="{{ $tp['titre'] }}">{{ $tp['titre'] }}</span>
                                    </div>
                                </td>
                                <td class="py-3 px-3 text-center font-mono font-black text-rose-700">
                                    {{ $tp['total_rejets'] }}
                                </td>
                                <td class="py-3 px-3 text-center font-mono font-semibold text-slate-700">
                                    {{ $tp['apprenants_touches'] }} / {{ $tp['total_evaluations'] }}
                                </td>
                                <td class="py-3 px-3 text-center font-mono font-bold text-slate-800">
                                    {{ $tp['frequence_moyenne'] }} itér.
                                </td>
                                <td class="py-3 px-3">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $tp['badge'] }}">
                                        {{ $tp['diagnostic'] }}
                                    </span>
                                </td>
                                <td class="py-3 px-3 max-w-xs text-slate-600 text-[11px] italic truncate" title="{{ !empty($tp['messages']) ? $tp['messages'][0] : 'Aucun commentaire textuel' }}">
                                    @if(!empty($tp['messages']))
                                        <i class="fas fa-comment-dots text-slate-400 me-1"></i>
                                        <span>« {{ Str::limit($tp['messages'][0], 80) }} »</span>
                                    @else
                                        <span class="text-slate-400">Aucun message textuel extrait</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-6 text-center text-slate-400 italic">
                                    Aucune révision enregistrée sur ce périmètre.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
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
                        || (this.filter === 'premier_jet' && appr.statut_code === 'premier_jet')
                        || (this.filter === 'ajustement' && appr.statut_code === 'ajustement')
                        || (this.filter === 'tattonnement' && appr.statut_code === 'tattonnement')
                        || (this.filter === 'non_evalue' && appr.statut_code === 'non_evalue');

                    const q = this.search.toLowerCase().trim();
                    const matchesSearch = !q 
                        || appr.nom_complet.toLowerCase().includes(q) 
                        || appr.groupe_code.toLowerCase().includes(q)
                        || (appr.matricule && appr.matricule.toLowerCase().includes(q))
                        || (appr.tache_critique && appr.tache_critique.toLowerCase().includes(q));

                    return matchesFilter && matchesSearch;
                }
             }">
        <!-- Barre de filtres et recherche du tableau -->
        <div class="p-4 sm:p-5 border-b border-slate-200 bg-slate-50/50 flex flex-col md:flex-row md:items-center justify-between gap-3">
            <div>
                <h2 class="text-sm sm:text-base font-bold text-slate-900 flex items-center gap-2">
                    <i class="fas fa-users-gear text-indigo-600"></i>
                    <span>Roster des Apprenants & Suivi des Révisions</span>
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    Consultation individuelle de la stabilité du code, des itérations de reprise et des remarques formateurs.
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
                    <button @click="filter = 'premier_jet'" 
                            :class="filter === 'premier_jet' ? 'bg-emerald-600 text-white shadow-xs' : 'hover:text-slate-900'"
                            class="px-2.5 py-1 rounded-md transition">
                        Excellence (&lt; 0.5)
                    </button>
                    <button @click="filter = 'ajustement'" 
                            :class="filter === 'ajustement' ? 'bg-sky-600 text-white shadow-xs' : 'hover:text-slate-900'"
                            class="px-2.5 py-1 rounded-md transition">
                        Ajustements (&le; 1.2)
                    </button>
                    <button @click="filter = 'tattonnement'" 
                            :class="filter === 'tattonnement' ? 'bg-rose-600 text-white shadow-xs' : 'hover:text-slate-900'"
                            class="px-2.5 py-1 rounded-md transition">
                        Tâtonnement (&gt; 1.2)
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
                        <th class="py-3 px-4 text-center">Tâches Évaluées</th>
                        <th class="py-3 px-4 text-center">Total Reprises</th>
                        <th class="py-3 px-4 text-center">Taux 1<sup>er</sup> Jet</th>
                        <th class="py-3 px-4 text-center">F<sub>révision</sub> (Indiv.)</th>
                        <th class="py-3 px-4">Tâche la Plus Difficile</th>
                        <th class="py-3 px-4 text-center">Statut Didactique</th>
                        <th class="py-3 px-4 text-center">Détail des Feedbacks</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($detail['apprenants'] as $appr)
                        <tr x-show="matches({{ json_encode($appr) }})" 
                            class="hover:bg-slate-50/80 transition {{ $appr['statut_code'] === 'tattonnement' ? 'bg-rose-50/20' : '' }}">
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

                            <!-- Tâches Évaluées -->
                            <td class="py-3 px-4 text-center font-mono font-bold text-slate-800">
                                {{ $appr['total_taches'] }}
                            </td>

                            <!-- Total Reprises -->
                            <td class="py-3 px-4 text-center font-mono font-black {{ $appr['total_rejets'] > 0 ? 'text-rose-700' : 'text-slate-400' }}">
                                {{ $appr['total_rejets'] }}
                            </td>

                            <!-- Taux 1er Jet -->
                            <td class="py-3 px-4 text-center font-mono font-bold text-emerald-700">
                                {{ $appr['taux_premier_jet'] }}%
                            </td>

                            <!-- Fréquence Individuelle -->
                            <td class="py-3 px-4 text-center font-mono font-black {{ $appr['frequence_individuelle'] < $detail['seuil_optimal'] ? 'text-emerald-700' : ($appr['frequence_individuelle'] <= $detail['seuil_alerte'] ? 'text-amber-600' : 'text-rose-700') }}">
                                {{ $appr['frequence_individuelle'] }}
                            </td>

                            <!-- Tâche la Plus Difficile -->
                            <td class="py-3 px-4 text-slate-600 text-[11px] truncate max-w-[200px]" title="{{ $appr['tache_critique'] }}">
                                <span class="truncate block">{{ $appr['tache_critique'] }}</span>
                            </td>

                            <!-- Statut Didactique -->
                            <td class="py-3 px-4 text-center">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $appr['statut_badge'] }}">
                                    {{ $appr['statut_label'] }}
                                </span>
                            </td>

                            <!-- Bouton Consulter Révisions -->
                            <td class="py-3 px-4 text-center">
                                <button type="button" 
                                        @click="openModal({{ json_encode($appr) }})"
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-semibold text-indigo-700 bg-indigo-50 border border-indigo-200 hover:bg-indigo-100 transition shadow-2xs">
                                    <i class="fas fa-list-check text-[10px]"></i>
                                    <span>Tâches ({{ count($appr['taches_details']) }})</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-8 text-center text-slate-400 italic">
                                Aucun apprenant trouvé pour ces critères de filtrage.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <!-- Modal d'Inspection des Tâches et des Révisions d'un Apprenant -->
    <template x-if="activeModalApprenant">
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4"
             @click.self="closeModal()">
            <div class="bg-white rounded-xl shadow-2xl border border-slate-200 w-full max-w-4xl max-h-[85vh] flex flex-col overflow-hidden animate-in fade-in zoom-in-95 duration-150">
                <!-- En-tête Modal -->
                <div class="p-4 sm:p-5 border-b border-slate-200 bg-slate-50 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-black text-sm shrink-0 border border-indigo-200">
                            <span x-text="activeModalApprenant.prenom.charAt(0) + activeModalApprenant.nom.charAt(0)"></span>
                        </div>
                        <div class="min-w-0">
                            <h3 class="text-base font-bold text-slate-900 truncate" x-text="activeModalApprenant.nom_complet"></h3>
                            <div class="flex items-center gap-2 text-xs text-slate-500 font-mono mt-0.5">
                                <span class="px-1.5 py-0.2 rounded bg-slate-200 text-slate-700" x-text="activeModalApprenant.groupe_code"></span>
                                <span>F_révision : <strong class="text-indigo-700" x-text="activeModalApprenant.frequence_individuelle + ' itér.'"></strong></span>
                                <span>Total rejets : <strong class="text-rose-600" x-text="activeModalApprenant.total_rejets"></strong></span>
                            </div>
                        </div>
                    </div>
                    <button @click="closeModal()" class="w-8 h-8 rounded-lg hover:bg-slate-200 text-slate-400 hover:text-slate-700 flex items-center justify-center transition">
                        <i class="fas fa-xmark text-sm"></i>
                    </button>
                </div>

                <!-- Contenu Modal : Liste des Tâches et Révisions -->
                <div class="p-4 sm:p-6 overflow-y-auto space-y-4">
                    <template x-for="tache in activeModalApprenant.taches_details" :key="tache.id">
                        <div class="border rounded-lg p-3.5 space-y-2.5 transition"
                             :class="tache.nb_revisions > 0 ? 'border-amber-200 bg-amber-50/15' : 'border-slate-200 bg-white'">
                            <div class="flex items-start justify-between gap-2">
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2">
                                        <span class="px-1.5 py-0.5 rounded text-[10px] font-bold font-mono bg-slate-100 text-slate-700 border border-slate-200" 
                                              x-text="'#' + tache.tache_id"></span>
                                        <h4 class="text-xs sm:text-sm font-bold text-slate-900 truncate" x-text="tache.titre"></h4>
                                    </div>
                                    <div class="flex items-center gap-2 text-[11px] text-slate-500 mt-1">
                                        <span>État : <strong x-text="tache.etat_titre"></strong></span>
                                        <span>&bull;</span>
                                        <span>Reprises : <strong :class="tache.nb_revisions > 0 ? 'text-rose-600 font-bold' : 'text-emerald-700 font-semibold'" x-text="tache.nb_revisions"></strong></span>
                                    </div>
                                </div>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold shrink-0"
                                      :class="tache.nb_revisions === 0 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200'"
                                      x-text="tache.nb_revisions === 0 ? 'Premier Jet Conforme' : tache.nb_revisions + ' reprise(s)'">
                                </span>
                            </div>

                            <!-- Liste des Itérations de Révision Formateur -->
                            <template x-if="tache.revisions && tache.revisions.length > 0">
                                <div class="mt-2 space-y-2 border-t border-slate-100 pt-2">
                                    <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider block">
                                        Historique structuré des feedbacks formateur :
                                    </span>
                                    <div class="space-y-1.5">
                                        <template x-for="rev in tache.revisions" :key="rev.id">
                                            <div class="bg-white p-2.5 rounded-md border border-slate-200 text-xs shadow-2xs space-y-1">
                                                <div class="flex items-center justify-between gap-2">
                                                    <span class="font-bold text-indigo-700 font-mono text-[11px]" x-text="'Tentative #' + rev.tentative"></span>
                                                    <span class="text-[10px] text-slate-400" x-text="rev.date_demande"></span>
                                                </div>
                                                <p class="text-slate-700 text-[11px] leading-relaxed bg-slate-50 p-2 rounded border border-slate-100 italic" 
                                                   x-text="rev.message_formateur ? '« ' + rev.message_formateur + ' »' : 'Aucun commentaire textuel saisi.'">
                                                </p>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>

                <!-- Pied de Modal -->
                <div class="p-3 border-t border-slate-200 bg-slate-50 flex justify-end">
                    <button @click="closeModal()" class="px-4 py-1.5 rounded-lg border border-slate-300 text-xs font-semibold text-slate-700 hover:bg-slate-100 transition">
                        Fermer
                    </button>
                </div>
            </div>
        </div>
    </template>

    <!-- Benchmark Multi-Sessions / Multi-Groupes -->
    @if(!empty($detail['benchmark']))
    <section class="bg-white rounded-lg border border-slate-200 p-5 sm:p-6 shadow-xs">
        <div class="space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div>
                    <h2 class="text-sm sm:text-base font-bold text-slate-900 flex items-center gap-2">
                        <i class="fas fa-layer-group text-indigo-600"></i>
                        <span>Benchmark Didactique Multi-Sessions</span>
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Comparatif de la fréquence de révision et du taux de stabilité du premier jet sur l'ensemble des promotions.
                    </p>
                </div>
                <span class="text-xs text-slate-500 font-mono font-semibold">
                    {{ count($detail['benchmark']) }} sessions analysées
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-[11px] uppercase tracking-wider text-slate-500 font-bold">
                            <th class="py-2.5 px-3">Session de Formation</th>
                            <th class="py-2.5 px-3">Groupe</th>
                            <th class="py-2.5 px-3">Filière</th>
                            <th class="py-2.5 px-3 text-center">Apprenants</th>
                            <th class="py-2.5 px-3 text-center">Tâches Évaluées</th>
                            <th class="py-2.5 px-3 text-center">Total Rejets</th>
                            <th class="py-2.5 px-3 text-center">F<sub>révision</sub> Moy.</th>
                            <th class="py-2.5 px-3 text-center">Premier Jet</th>
                            <th class="py-2.5 px-3 text-center">Statut Didactique</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs">
                        @foreach($detail['benchmark'] as $bench)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-3 px-3 font-bold text-slate-900 truncate max-w-[220px]" title="{{ $bench['session_titre'] }}">
                                    {{ $bench['session_titre'] }}
                                </td>
                                <td class="py-3 px-3 font-semibold text-slate-700">
                                    <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-800 border border-slate-200 font-mono text-[11px]">
                                        {{ $bench['groupe_code'] }}
                                    </span>
                                </td>
                                <td class="py-3 px-3 text-slate-600">
                                    {{ $bench['filiere_code'] }}
                                </td>
                                <td class="py-3 px-3 text-center font-mono font-semibold text-slate-800">
                                    {{ $bench['total_apprenants'] }}
                                </td>
                                <td class="py-3 px-3 text-center font-mono text-slate-700">
                                    {{ $bench['total_taches'] }}
                                </td>
                                <td class="py-3 px-3 text-center font-mono font-bold {{ $bench['total_rejets'] > 0 ? 'text-rose-700' : 'text-slate-400' }}">
                                    {{ $bench['total_rejets'] }}
                                </td>
                                <td class="py-3 px-3 text-center font-mono font-black {{ $bench['frequence_revision'] < $detail['seuil_optimal'] ? 'text-emerald-700' : ($bench['frequence_revision'] <= $detail['seuil_alerte'] ? 'text-amber-600' : 'text-rose-700') }}">
                                    {{ $bench['frequence_formatee'] }}
                                </td>
                                <td class="py-3 px-3 text-center font-mono font-bold text-emerald-700">
                                    {{ $bench['taux_premier_jet_formate'] }}
                                </td>
                                <td class="py-3 px-3 text-center">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $bench['statut_badge'] }}">
                                        {{ $bench['statut'] }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    @endif

</div>
@endsection
