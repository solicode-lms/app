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

    <!-- Statut Opérationnel Immédiat & File d'Attente (Indicateur 05 — T_file) -->
    @if(!empty($kpiFileAttente['est_en_file']))
        <section class="bg-white rounded-2xl border border-slate-200 p-5 sm:p-6 shadow-sm relative overflow-hidden">
            <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl bg-sky-50 text-sky-600 border border-sky-200 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold {{ $kpiFileAttente['badge_class'] }}">
                                {{ $kpiFileAttente['badge_label'] }}
                            </span>
                        </div>
                        <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 mt-1">
                            Votre rang de passage en direct : <span class="text-indigo-600">{{ $kpiFileAttente['rang_formate'] ?? 'Rang #1' }}</span>
                        </h2>
                        <p class="text-xs sm:text-sm text-slate-500 mt-1">
                            {{ $kpiFileAttente['tache_titre'] }} &bull; Temps d'attente : <strong class="text-slate-800">{{ $kpiFileAttente['temps_attente_formate'] }}</strong>
                            @if(!empty($kpiFileAttente['heure_passage_estimee']) && $kpiFileAttente['statut'] === 'en_attente')
                                &bull; Passage formateur estimé à <strong class="text-slate-800">{{ $kpiFileAttente['heure_passage_estimee'] }}</strong> (dans ~{{ $kpiFileAttente['temps_estime_restant_minutes'] }} min).
                            @endif
                        </p>
                    </div>
                </div>

                <div class="bg-slate-50 p-4 rounded-xl border border-slate-200/80 flex items-center gap-4 shrink-0 w-full lg:w-auto">
                    <div>
                        <div class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Livrable à soutenir</div>
                        <div class="text-xs font-bold text-slate-900">{{ $kpiFileAttente['tache_titre'] }}</div>
                    </div>
                    <span class="px-3.5 py-2 bg-indigo-50 text-indigo-700 text-xs font-bold rounded-lg border border-indigo-200">
                        Préparez votre IDE
                    </span>
                </div>
            </div>

            <div class="mt-4 pt-4 border-t border-slate-100 flex items-center gap-2 text-xs text-slate-600">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span><strong>Conseil méthodologique :</strong> Gardez votre code source ouvert sur la branche du prototype pour fluidifier le démarrage de l'évaluation Live Coding.</span>
            </div>
        </section>
    @endif

    <!-- Indicateurs Pédagogiques Fonctionnels -->
    <section class="space-y-4">
        <div>
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">Indicateurs de Régulation Pédagogique</h3>
            <p class="text-xs text-slate-400">Suivi en direct de votre rythme d'amorçage et de la validation de vos jalons techniques.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @include('PkgTableauBord::components.kpi-amorcage', ['type' => 'apprenant', 'kpi' => $kpiAmorcage])
            @include('PkgTableauBord::components.kpi-gating', ['type' => 'apprenant', 'kpi' => $kpiGating])
            @include('PkgTableauBord::components.kpi-revision', ['type' => 'apprenant', 'kpi' => $kpiRevision])
        </div>
    </section>
@endsection
