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

    {{-- Grille dynamique des Cartes KPI --}}
    @if(!empty($dashboardCards))
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-{{ min(max(count($dashboardCards), 1), 5) }} gap-5">
        @foreach($dashboardCards as $card)
            @include($card['component'], ['type' => 'apprenant', 'kpi' => $card['data']])
        @endforeach
    </div>
    @endif

    {{-- Volets Opérationnels & Panels Dynamiques --}}
    @if(!empty($dashboardPanels))
        @foreach($dashboardPanels as $panel)
            @include($panel['component'], ['type' => 'apprenant', 'kpi' => $panel['data']])
        @endforeach
    @endif

</section>
@endsection