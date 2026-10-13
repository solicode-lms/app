@extends('layouts.admin-v2')

@section('title', 'Tableau de Bord Formateur — Solicode LMS V2')
@section('page_title', 'Pilotage Didactique')
@section('page_subtitle', 'Régulation des promotions et suivi en temps réel de l\'amorçage')

@section('actions')
    @include('PkgTableauBord::components.session-selector', ['sessions' => $sessions ?? collect(), 'sessionActive' => $sessionFormationActive ?? null])
@endsection

@section('content')
    <!-- En-tête contextuel Promotion & Affectation Active -->
    <section class="bg-white rounded-2xl border border-slate-200 p-5 sm:p-6 shadow-sm">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                        Espace Formateur
                    </span>
                    @if(isset($projetActif) || isset($sessionFormationActive))
                        @php $currentActive = $projetActif ?? $sessionFormationActive; @endphp
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-100/70 text-indigo-800 border border-indigo-300">
                            Projet : {{ $currentActive->titre }}
                        </span>
                    @endif
                </div>
                <h2 class="text-xl font-bold text-slate-900 mt-1">
                    Promotion {{ $affectationProjet->groupe->code ?? 'Groupe' }} &bull; {{ $affectationProjet->projet->titre ?? 'Projet actif' }}
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    Lancement officiel : <strong class="text-slate-800">{{ $affectationProjet->cache_date_lancement ?? $affectationProjet->date_debut ?? 'Non défini' }}</strong>
                    &bull; Effectif : <strong class="text-slate-800">{{ $kpiAmorcage['total_apprenants'] ?? 0 }} apprenants</strong>
                </p>
            </div>

            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    Cohorte active
                </span>
            </div>
        </div>
    </section>

    <!-- Indicateurs Fonctionnels de Pilotage Didactique -->
    <section class="space-y-4">
        <div>
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">Indicateurs de Pilotage Didactique</h3>
            <p class="text-xs text-slate-400">Régulation du rythme de démarrage et validation des fondamentaux techniques au Live Coding.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @include('PkgTableauBord::components.kpi-amorcage', ['type' => 'formateur', 'kpi' => $kpiAmorcage])
            @include('PkgTableauBord::components.kpi-gating', ['type' => 'formateur', 'kpi' => $kpiGating])
            @include('PkgTableauBord::components.kpi-revision', ['type' => 'formateur', 'kpi' => $kpiRevision])
        </div>

        <!-- Top 3 Tâches Nécessitant un Réalignement Didactique -->
        @if(!empty($kpiRevision['top_taches']))
            <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div>
                        <h4 class="text-sm font-bold text-slate-900">Tâches Nécessitant un Réalignement Didactique</h4>
                        <p class="text-xs text-slate-500">Tâches concentrant le plus grand nombre de rejets formateurs ({{ $kpiRevision['frequence_formatee'] }})</p>
                    </div>
                    <span class="text-xs font-bold text-amber-700 bg-amber-50 px-2.5 py-1 rounded-lg border border-amber-200">Top 3 Révélateur</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach($kpiRevision['top_taches'] as $tacheItem)
                        <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200/80 space-y-1">
                            <div class="flex items-center justify-between text-xs font-bold text-slate-900">
                                <span class="truncate pr-2" title="{{ $tacheItem['titre'] }}">{{ $tacheItem['titre'] }}</span>
                                <span class="text-rose-600 font-mono shrink-0">{{ $tacheItem['frequence_formatee'] }}</span>
                            </div>
                            <div class="text-[11px] text-slate-500">{{ $tacheItem['explication'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Deux colonnes d'alertes : Retard amorçage & Remédiation requise -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
            <!-- Liste des apprenants en retard d'amorçage si présents -->
            @if(!empty($kpiAmorcage['apprenants_en_retard']))
                <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5 shadow-2xs">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-triangle-exclamation text-amber-600 text-lg"></i>
                        <div>
                            <h4 class="text-xs font-bold text-amber-900 uppercase tracking-wider">Apprenants n'ayant pas encore amorcé leur projet</h4>
                            <p class="text-xs text-amber-700 mt-0.5">Ces stagiaires n'ont soumis aucun travail sur le projet actuel.</p>
                        </div>
                    </div>

                    <div class="mt-4 flex flex-wrap gap-2">
                        @foreach($kpiAmorcage['apprenants_en_retard'] as $stagiaire)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium bg-white text-slate-700 border border-amber-200 shadow-2xs">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                {{ $stagiaire['nom_complet'] }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Liste des apprenants orientés en remédiation prototype si présents -->
            @if(!empty($kpiGating['apprenants_en_remediation']))
                <div class="bg-rose-50 border border-rose-200 rounded-2xl p-5 shadow-2xs">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-user-clock text-rose-600 text-lg"></i>
                        <div>
                            <h4 class="text-xs font-bold text-rose-900 uppercase tracking-wider">Stagiaires en Remédiation (Live Coding non validé)</h4>
                            <p class="text-xs text-rose-700 mt-0.5">Ces apprenants n'ont pas validé au 1er passage et doivent consolider leurs prérequis.</p>
                        </div>
                    </div>

                    <div class="mt-4 flex flex-wrap gap-2">
                        @foreach($kpiGating['apprenants_en_remediation'] as $stagiaire)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium bg-white text-slate-700 border border-rose-200 shadow-2xs">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                {{ $stagiaire['nom_complet'] }}
                                <span class="text-[10px] text-slate-400">({{ $stagiaire['tentatives'] }} passage{{ $stagiaire['tentatives'] > 1 ? 's' : '' }})</span>
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection
