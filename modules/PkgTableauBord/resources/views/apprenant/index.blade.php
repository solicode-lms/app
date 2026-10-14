@extends('layouts.admin-v2')

@section('title', ($isSuperviseur ?? false) ? 'Tableau de Bord Apprenant' : 'Mon Tableau de Bord')
@section('page_title', ($isSuperviseur ?? false) ? 'Tableau de Bord Apprenant' : 'Mon Tableau de Bord')
@section('page_subtitle', 'Consulter une vue d’ensemble des activités, des indicateurs et de la progression.')

@section('actions')
<button onclick="window.print()" class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition shadow-2xs">
    <i class="fas fa-print text-slate-400"></i>
    <span>Imprimer</span>
</button>
@endsection

@section('filters')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <div class="flex items-center gap-2">
        <span class="text-xs font-bold uppercase tracking-wider text-slate-500 flex items-center gap-1.5">
            <i class="fas fa-sliders text-indigo-600 text-xs"></i>
            <span>Périmètre d'Apprentissage</span>
        </span>
    </div>
    <div class="flex items-center gap-2 flex-wrap sm:flex-nowrap">
        @if(!empty($canSelectApprenant))
        @include('PkgTableauBord::components.apprenant-selector', [
            'groupes' => $groupes ?? collect(),
            'selectedGroupeId' => $selectedGroupeId ?? null,
            'apprenants' => $apprenants ?? collect(),
            'selectedApprenantId' => $selectedApprenantId ?? null,
        ])
        @endif
        @include('PkgTableauBord::components.session-selector', [
            'sessions' => $sessions ?? collect(),
            'sessionActive' => $sessionFormationActive ?? null
        ])
    </div>
</div>
@endsection

@section('content')
<!-- En-tête contextuel Apprenant & Projet en cours -->
<section class="bg-white rounded-lg border border-slate-200 p-5 sm:p-6 shadow-sm">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 flex-wrap">
                @if(!empty($isSuperviseur))
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200 flex items-center gap-1.5">
                    <i class="fas fa-eye text-[11px] text-amber-600"></i>
                    <span>Vue Superviseur</span>
                </span>
                @else
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                    Espace Apprenant
                </span>
                @endif

                @if(isset($projetActif) || isset($sessionFormationActive))
                @php $currentActive = $projetActif ?? $sessionFormationActive; @endphp
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-100/70 text-indigo-800 border border-indigo-300">
                    Projet : {{ $currentActive->titre }}
                </span>
                @if(!empty($isProjetSessionFormation))
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200 flex items-center gap-1">
                    <i class="fas fa-certificate text-[10px] text-emerald-600"></i>
                    <span>Session N2</span>
                </span>
                @else
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-700 border border-slate-300 flex items-center gap-1">
                    <i class="fas fa-folder text-[10px] text-slate-500"></i>
                    <span>Projet Standard</span>
                </span>
                @endif
                @endif
            </div>

            @if(!empty($isSuperviseur))
            <h2 class="text-xl font-bold text-slate-900 mt-1">
                {{ $apprenant ? ($apprenant->nom . ' ' . $apprenant->prenom) : 'Aucun apprenant sélectionné' }}
            </h2>
            <p class="text-xs text-slate-500 mt-0.5">
                Groupe : <strong class="text-slate-800">{{ $apprenant?->groupes?->pluck('nom')->implode(', ') ?: ($realisationProjet->affectationProjet->groupe->code ?? 'Non assigné') }}</strong>
                &bull; Matricule : <strong class="text-slate-800">{{ $apprenant?->matricule ?? '-' }}</strong>
                @if($realisationProjet)
                &bull; Projet actif : <strong class="text-slate-800">{{ $realisationProjet->affectationProjet->projet->titre ?? 'Projet en cours' }}</strong>
                @endif
            </p>
            @else
            <h2 class="text-xl font-bold text-slate-900 mt-1">
                Bonjour, {{ $apprenant->prenom ?? (Auth::user()?->name ?? 'Apprenant') }} 👋
            </h2>
            <p class="text-xs text-slate-500 mt-0.5">
                Groupe : <strong class="text-slate-800">{{ $realisationProjet->affectationProjet->groupe->code ?? 'Promotion' }}</strong>
                &bull; Projet actif : <strong class="text-slate-800">{{ $realisationProjet->affectationProjet->projet->titre ?? 'Projet en cours' }}</strong>
            </p>
            @endif
        </div>

        <div class="flex items-center gap-2">
            @if($realisationProjet)
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                Actif
            </span>
            @else
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                Aucun projet assigné
            </span>
            @endif
        </div>
    </div>

    @if(!$apprenant)
    <div class="mt-4 p-3.5 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-xs flex items-center gap-3">
        <i class="fas fa-exclamation-triangle text-base text-amber-600"></i>
        <div>
            <strong>Aucun apprenant disponible.</strong> Aucun profil apprenant n'est rattaché à ce groupe ou à votre périmètre de formation.
        </div>
    </div>
    @elseif(!$realisationProjet)
    <div class="mt-4 p-3.5 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-xs flex items-center gap-3">
        <i class="fas fa-info-circle text-base text-amber-600"></i>
        <div>
            <strong>Aucun projet assigné.</strong> Aucun projet ou réalisation active n'est actuellement rattaché à cet apprenant.
        </div>
    </div>
    @endif
</section>

@if(isset($isProjetSessionFormation) && !$isProjetSessionFormation)
<div class="p-4 rounded-lg bg-amber-50 border border-amber-200 text-amber-900 text-xs flex items-start gap-3 shadow-xs">
    <i class="fas fa-info-circle text-amber-600 text-base mt-0.5 shrink-0"></i>
    <div class="space-y-1">
        <p class="font-bold">Projet Standard (Hors Session de formation N2)</p>
        <p class="text-amber-800 leading-relaxed">
            Ce projet ne comporte pas d'épreuve de Live Coding ($N2$).
            Les indicateurs certifiants (Gating, File d'attente, Intégrité IA, Couverture UA et Docimologie) ne sont pas applicables à ce projet.
        </p>
    </div>
</div>
@endif

<!-- Indicateurs Pédagogiques Fonctionnels -->
<section class="space-y-4">
    <div>
        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">Indicateurs de Régulation Pédagogique</h3>
        <p class="text-xs text-slate-400">Suivi en direct de votre rythme d'amorçage et de la validation de vos jalons techniques.</p>
    </div>

    @if($isProjetSessionFormation ?? true)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-5">
        {{-- Indicateur 01 : Démarrage du sprint (T_amorçage) --}}
        @include('PkgTableauBord::components.kpi-amorcage', ['type' => 'apprenant', 'kpi' => $kpiAmorcage])
        {{-- Indicateur 02 : Validation Prototype (R_gating) --}}
        @include('PkgTableauBord::components.kpi-gating', ['type' => 'apprenant', 'kpi' => $kpiGating])
        {{-- Indicateur 03 : Qualité du 1er rendu (F_révision) --}}
        @include('PkgTableauBord::components.kpi-revision', ['type' => 'apprenant', 'kpi' => $kpiRevision])
        {{-- Indicateur 08 : Progression Référentiel National (ICP) --}}
        @include('PkgTableauBord::components.kpi-couverture-pedagogique', ['type' => 'apprenant', 'kpi' => $kpiCouverture])
        {{-- Indicateur 09 : Contrôle Docimologique & Notes (65/35) --}}
        @include('PkgTableauBord::components.kpi-docimologie', ['type' => 'apprenant', 'kpi' => $kpiDocimologie])
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        {{-- Indicateur 01 : Démarrage du sprint --}}
        @include('PkgTableauBord::components.kpi-amorcage', ['type' => 'apprenant', 'kpi' => $kpiAmorcage])
        {{-- Indicateur 03 : Qualité du 1er rendu --}}
        @include('PkgTableauBord::components.kpi-revision', ['type' => 'apprenant', 'kpi' => $kpiRevision])
    </div>
    @endif

    <!-- Indicateur 05 — Statut Opérationnel Immédiat & File d'Attente (T_file) -->
    @if(($isProjetSessionFormation ?? true) && !empty($kpiFileAttente['est_en_file']))
    <section class="bg-white rounded-lg border border-slate-200 p-5 sm:p-6 shadow-sm relative overflow-hidden">
        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-sky-50 text-sky-600 border border-sky-200 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold {{ $kpiFileAttente['badge_label'] ?? '' }}">
                            {{ $kpiFileAttente['badge_label'] ?? 'File active' }} (Indicateur 05)
                        </span>
                    </div>
                    <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 mt-1">
                        Votre rang de passage en direct : <span class="text-indigo-600">{{ $kpiFileAttente['rang_formate'] ?? 'Rang #1' }}</span>
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1">
                        {{ $kpiFileAttente['tache_titre'] ?? 'Évaluation' }} &bull; Temps d'attente : <strong class="text-slate-800">{{ $kpiFileAttente['temps_attente_formate'] ?? '0 min' }}</strong>
                        @if(!empty($kpiFileAttente['heure_passage_estimee']) && ($kpiFileAttente['statut'] ?? '') === 'en_attente')
                        &bull; Passage formateur estimé à <strong class="text-slate-800">{{ $kpiFileAttente['heure_passage_estimee'] }}</strong> (dans ~{{ $kpiFileAttente['temps_estime_restant_minutes'] }} min).
                        @endif
                    </p>
                </div>
            </div>

            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200/80 flex items-center gap-4 shrink-0 w-full lg:w-auto">
                <div>
                    <div class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Livrable à soutenir</div>
                    <div class="text-xs font-bold text-slate-900">{{ $kpiFileAttente['tache_titre'] ?? 'Session de validation' }}</div>
                </div>
                <span class="px-3.5 py-2 bg-indigo-50 text-indigo-700 text-xs font-bold rounded-lg border border-indigo-200">
                    Préparez votre IDE
                </span>
            </div>
        </div>

        <div class="mt-4 pt-4 border-t border-slate-100 flex items-center gap-2 text-xs text-slate-600">
            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span><strong>Conseil méthodologique :</strong> Gardez votre code source ouvert sur la branche du prototype pour fluidifier le démarrage de l'évaluation Live Coding.</span>
        </div>
    </section>
    @endif

    <!-- Indicateur 06 — Message Bienveillant d'Accompagnement Didactique (T_intervention) -->
    @if(!empty($kpiIntervention['besoin_aide']))
    <section class="bg-amber-50 border-2 border-amber-300 rounded-lg p-5 sm:p-6 shadow-sm">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-start gap-3.5">
                <div class="w-10 h-10 rounded-xl bg-amber-100 border border-amber-200 flex items-center justify-center shrink-0">
                    <i class="fas fa-handshake-angle text-amber-600 text-lg"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h4 class="text-sm font-bold text-amber-900">Besoin d'un coup de pouce sur votre code ? (Indicateur 06)</h4>
                        <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-amber-200 text-amber-800 font-mono">
                            +{{ $kpiIntervention['minutes_depassement'] ?? 10 }} min post-séance
                        </span>
                    </div>
                    <p class="text-xs text-amber-800 mt-1">
                        {{ $kpiIntervention['message'] }}
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <span class="px-3 py-1.5 rounded-xl text-xs font-bold bg-white text-amber-900 border border-amber-300 shadow-2xs">
                    {{ $kpiIntervention['tache_titre'] ?? 'Tutoriel' }}
                </span>
            </div>
        </div>
    </section>
    @elseif(!empty($kpiIntervention['message']))
    <section class="bg-indigo-50 border border-indigo-200 rounded-lg p-4 shadow-sm flex items-center gap-3 text-xs text-indigo-900">
        <i class="fas fa-circle-check text-indigo-600 text-base"></i>
        <span><strong>Indicateur 06 :</strong> {{ $kpiIntervention['message'] }}</span>
    </section>
    @endif

    <!-- Indicateur 07 — Validation du Projet de Sprint (TRVS) -->
    @if(($isProjetSessionFormation ?? true) && !empty($kpiIntegriteIA['statut']))
    <div class="bg-white rounded-lg border border-slate-200 p-4 shadow-2xs flex items-center justify-between text-xs text-slate-700">
        <div class="flex items-center gap-2.5">
            <i class="fas {{ !empty($kpiIntegriteIA['est_valide']) ? 'fa-circle-check text-emerald-600' : 'fa-clock text-amber-500' }}"></i>
            <span><strong>Indicateur 07 — Réalisation de Sprint :</strong> {{ $kpiIntegriteIA['message'] ?? 'État de la réalisation' }}</span>
        </div>
        <span class="font-mono font-bold px-2.5 py-0.5 rounded {{ $kpiIntegriteIA['badge_class'] ?? 'bg-indigo-50 text-indigo-700 border border-indigo-200' }}">{{ $kpiIntegriteIA['statut'] ?? 'En cours' }}</span>
    </div>
    @endif
</section>
@endsection