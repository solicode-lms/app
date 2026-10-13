@extends('layouts.admin-v2')

@section('title', 'Tableau de Bord Formateur — Solicode LMS V2')
@section('page_title', 'Pilotage Didactique')
@section('page_subtitle', 'Régulation des promotions et suivi en temps réel de l\'amorçage')

@section('content')
    <!-- En-tête contextuel Promotion & Affectation Active -->
    <section class="bg-white rounded-2xl border border-slate-200 p-5 sm:p-6 shadow-sm">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                    Espace Formateur
                </span>
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

    <!-- Indicateur 01 Fonctionnel : Temps moyen de mise au travail -->
    <section class="space-y-4">
        <div>
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">Indicateur 01 — Démarrage du travail de la promotion</h3>
            <p class="text-xs text-slate-400">Délai moyen d'activation de la première tâche et suivi des apprenants en attente.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @include('PkgTableauBord::components.kpi-amorcage', ['type' => 'formateur', 'kpi' => $kpiAmorcage])
        </div>

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
    </section>
@endsection
