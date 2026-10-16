@extends('layouts.admin-v2')

@section('title', __('PkgTableauBord::tableauBord.formateur.title'))
@section('page_title', __('PkgTableauBord::tableauBord.formateur.page_title'))
@section('page_subtitle', __('PkgTableauBord::tableauBord.formateur.page_subtitle'))

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
            <span>Périmètre Pédagogique</span>
        </span>
    </div>
    <div class="flex items-center gap-2 flex-wrap sm:flex-nowrap">
        @if(!empty($canSelectFormateur) || !empty($canSelectGroupe))
        @include('PkgTableauBord::components.formateur-selector', [
            'canSelectFormateur' => $canSelectFormateur ?? false,
            'formateurs' => $formateurs ?? collect(),
            'selectedFormateurId' => $selectedFormateurId ?? null,
            'canSelectGroupe' => $canSelectGroupe ?? false,
            'groupes' => $groupes ?? collect(),
            'selectedGroupeId' => $selectedGroupeId ?? null,
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
<!-- En-tête contextuel Promotion & Affectation Active -->
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
                    Espace Formateur
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
                {{ $formateur ? ($formateur->nom . ' ' . $formateur->prenom) : 'Aucun formateur sélectionné' }}
            </h2>
            <p class="text-xs text-slate-500 mt-0.5">
                Matricule : <strong class="text-slate-800">{{ $formateur?->matricule ?? '-' }}</strong>
                &bull; Groupe actif : <strong class="text-slate-800">{{ $affectationProjet->groupe->code ?? 'Non assigné' }}</strong>
                @if($affectationProjet)
                &bull; Projet actif : <strong class="text-slate-800">{{ $affectationProjet->projet->titre ?? 'Aucun projet' }}</strong>
                &bull; Effectif : <strong class="text-slate-800">{{ $kpiAmorcage['total_apprenants'] ?? 0 }} apprenants</strong>
                @endif
            </p>
            @else
            <h2 class="text-xl font-bold text-slate-900 mt-1">
                Groupe {{ $affectationProjet->groupe->code ?? 'Non assigné' }} &bull; {{ $affectationProjet->projet->titre ?? 'Aucun projet' }}
            </h2>
            <p class="text-xs text-slate-500 mt-0.5">
                Lancement officiel : <strong class="text-slate-800">{{ $affectationProjet->cache_date_lancement ?? $affectationProjet->date_debut ?? 'Non défini' }}</strong>
                &bull; Effectif : <strong class="text-slate-800">{{ $kpiAmorcage['total_apprenants'] ?? 0 }} apprenants</strong>
            </p>
            @endif
        </div>

        <div class="flex items-center gap-2">
            @if($affectationProjet)
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                Groupe actif
            </span>
            @else
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                Aucun groupe actif
            </span>
            @endif
        </div>
    </div>
    @if(!$formateur)
    <div class="mt-4 p-3.5 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-xs flex items-center gap-3">
        <i class="fas fa-exclamation-triangle text-base text-amber-600"></i>
        <div>
            <strong>Aucun formateur trouvé.</strong> Aucun profil formateur n'a été sélectionné ou configuré.
        </div>
    </div>
    @elseif(!$affectationProjet)
    <div class="mt-4 p-3.5 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-xs flex items-center gap-3">
        <i class="fas fa-info-circle text-base text-amber-600"></i>
        <div>
            <strong>Aucune affectation trouvée.</strong> Aucune affectation de projet n'est actuellement rattachée à ce formateur pour ce groupe.
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
            Ce projet ne comporte pas d'épreuve de Live Coding ($N2$) ni de mobilisation d'Unités d'Apprentissage (UA).
            Les indicateurs certifiants (Gating, File d'attente, Intégrité IA, Couverture UA et Docimologie) sont désactivés.
            Seuls les indicateurs de suivi continu (Amorçage, Révision, Charge et Intervention) sont affichés.
        </p>
    </div>
</div>
@endif

<!-- Indicateurs Fonctionnels de Pilotage Didactique -->
<section class="space-y-4">
    <div>
        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">Indicateurs de Pilotage Didactique</h3>
        <p class="text-xs text-slate-400">Régulation du rythme de démarrage et validation des fondamentaux techniques au Live Coding.</p>
    </div>

    {{-- Grille dynamique des Cartes KPI --}}
    @if(!empty($dashboardCards))
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-{{ min(max(count($dashboardCards), 1), 9) }} gap-4">
        @foreach($dashboardCards as $card)
            @include($card['component'], ['type' => 'formateur', 'kpi' => $card['data']])
        @endforeach
    </div>
    @endif

    {{-- Volets Opérationnels & Panels Dynamiques --}}
    @if(!empty($dashboardPanels))
        @foreach($dashboardPanels as $panel)
            @include($panel['component'], ['type' => 'formateur', 'kpi' => $panel['data']])
        @endforeach
    @endif


</section>

<!-- Télémétrie d'activité formateur avec verrou d'inactivité à 5 minutes -->
@include('PkgTableauBord::components.telemetrie-activite', ['typeActivite' => 'PILOTAGE_KANBAN'])
@endsection