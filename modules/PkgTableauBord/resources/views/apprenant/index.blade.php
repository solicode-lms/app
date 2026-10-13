@extends('layouts.admin-v2')

@section('title', 'Tableau de Bord Apprenant — Solicode LMS V2')
@section('page_title', 'Mon Tableau de Bord')
@section('page_subtitle', 'Suivi d\'avancement et régulation du démarrage de projet')

@section('actions')
    @include('PkgTableauBord::components.session-selector', ['sessions' => $sessions ?? collect(), 'sessionActive' => $sessionFormationActive ?? null])
@endsection

@section('content')
    <!-- En-tête contextuel Apprenant & Projet en cours -->
    <section class="bg-white rounded-2xl border border-slate-200 p-5 sm:p-6 shadow-sm">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                        Espace Apprenant
                    </span>
                    @if(isset($projetActif) || isset($sessionFormationActive))
                        @php $currentActive = $projetActif ?? $sessionFormationActive; @endphp
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-100/70 text-indigo-800 border border-indigo-300">
                            Projet : {{ $currentActive->titre }}
                        </span>
                    @endif
                </div>
                <h2 class="text-xl font-bold text-slate-900 mt-1">
                    Bonjour, {{ $apprenant->prenom ?? (Auth::user()?->name ?? 'Apprenant') }} 👋
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    Groupe : <strong class="text-slate-800">{{ $realisationProjet->affectationProjet->groupe->code ?? 'Promotion' }}</strong>
                    &bull; Projet actif : <strong class="text-slate-800">{{ $realisationProjet->affectationProjet->projet->titre ?? 'Projet en cours' }}</strong>
                </p>
            </div>
            
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    Actif
                </span>
            </div>
        </div>
    </section>

    <!-- Indicateurs Pédagogiques Fonctionnels -->
    <section class="space-y-4">
        <div>
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">Indicateurs de Régulation Pédagogique</h3>
            <p class="text-xs text-slate-400">Suivi en direct de votre rythme d'amorçage et de la validation de vos jalons techniques.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @include('PkgTableauBord::components.kpi-amorcage', ['type' => 'apprenant', 'kpi' => $kpiAmorcage])
            @include('PkgTableauBord::components.kpi-gating', ['type' => 'apprenant', 'kpi' => $kpiGating])
        </div>
    </section>
@endsection
