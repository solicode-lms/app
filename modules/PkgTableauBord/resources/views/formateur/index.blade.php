@extends('layouts.admin-v2')

@section('title', ($isSuperviseur ?? false) ? 'Tableau de Bord Formateur' : 'Suivi pédagogique')
@section('page_title', ($isSuperviseur ?? false) ? 'Tableau de Bord Formateur' : 'Suivi pédagogique')
@section('page_subtitle', 'Suivre les activités, la progression et les compétences des apprenants.')

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

    @if($isProjetSessionFormation ?? true)
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-9 gap-4">
        {{-- Indicateur 01 : Démarrage du sprint (T_amorçage) --}}
        @include('PkgTableauBord::components.kpi-amorcage', ['type' => 'formateur', 'kpi' => $kpiAmorcage])

        {{-- Indicateur 02 : Gating Prototype 1er essai (R_gating) --}}
        @include('PkgTableauBord::components.kpi-gating', ['type' => 'formateur', 'kpi' => $kpiGating])

        {{-- Indicateur 03 : Fréquence des Révisions (F_révision) --}}
        @include('PkgTableauBord::components.kpi-revision', ['type' => 'formateur', 'kpi' => $kpiRevision])

        {{-- Indicateur 04 : Charge Logistique Formateur (C_logistique) --}}
        @include('PkgTableauBord::components.kpi-charge-logistique', ['type' => 'formateur', 'kpi' => $kpiChargeLogistique])

        {{-- Indicateur 05 : Latence File d'Attente FIFO (T_file) --}}
        @include('PkgTableauBord::components.kpi-file-attente', ['type' => 'formateur', 'kpi' => $kpiFileAttente])

        {{-- Indicateur 06 : Prise en charge des blocages (T_intervention) --}}
        @include('PkgTableauBord::components.kpi-intervention', ['type' => 'formateur', 'kpi' => $kpiIntervention])

        {{-- Indicateur 07 : Cohérence & Intégrité Usage IA (Pearson r) --}}
        @include('PkgTableauBord::components.kpi-integrite-ia', ['type' => 'formateur', 'kpi' => $kpiIntegriteIA])

        {{-- Indicateur 08 : Couverture Pédagogique Nationale (ICP) --}}
        @include('PkgTableauBord::components.kpi-couverture-pedagogique', ['type' => 'formateur', 'kpi' => $kpiCouverture])

        {{-- Indicateur 09 : Complétude Docimologique 65/35 (Bordereaux) --}}
        @include('PkgTableauBord::components.kpi-docimologie', ['type' => 'formateur', 'kpi' => $kpiDocimologie])
    </div>
    @else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Indicateur 01 : Démarrage du sprint --}}
        @include('PkgTableauBord::components.kpi-amorcage', ['type' => 'formateur', 'kpi' => $kpiAmorcage])
        {{-- Indicateur 03 : Fréquence des Révisions --}}
        @include('PkgTableauBord::components.kpi-revision', ['type' => 'formateur', 'kpi' => $kpiRevision])
        {{-- Indicateur 04 : Charge Logistique Formateur --}}
        @include('PkgTableauBord::components.kpi-charge-logistique', ['type' => 'formateur', 'kpi' => $kpiChargeLogistique])
        {{-- Indicateur 06 : Prise en charge des blocages --}}
        @include('PkgTableauBord::components.kpi-intervention', ['type' => 'formateur', 'kpi' => $kpiIntervention])
    </div>
    @endif

    <!-- Indicateur 01 — Diagnostic d'Amorçage : Apprenants en Attente de Démarrage (T_amorçage) -->
    @include('PkgTableauBord::components.apprenants-non-amorces-panel', ['kpi' => $kpiAmorcage])

    <!-- Indicateur 03 — Top 3 Tâches Nécessitant un Réalignement Didactique (F_révision) -->
    @if(!empty($kpiRevision['top_taches']))
    <div class="bg-white p-5 sm:p-6 rounded-lg border border-slate-200 shadow-sm space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div>
                <h4 class="text-sm font-bold text-slate-900">Tâches Nécessitant un Réalignement Didactique (Indicateur 03)</h4>
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

    @if($isProjetSessionFormation ?? true)
    <!-- Indicateur 05 — Console Opérationnelle Interactive : File d'Attente FIFO (T_file) -->
    @include('PkgTableauBord::components.file-attente-console', ['kpi' => $kpiFileAttente])
    @endif

    <!-- Indicateur 06 — Flashbox Alerte Critique : Apprenants en Rupture Didactique (+10 min) (T_intervention) -->
    @include('PkgTableauBord::components.flashbox-alerte-blocages', ['kpi' => $kpiIntervention])

    <!-- Indicateur 07 — Suivi des Réalisations du Sprint & TRVS -->
    @if(($isProjetSessionFormation ?? true) && !empty($kpiIntegriteIA['apprenants']))
    <div class="bg-white p-5 sm:p-6 rounded-lg border border-slate-200 shadow-sm space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-100 pb-3">
            <div>
                <div class="flex items-center gap-2">
                    <h4 class="text-sm font-bold text-slate-900">Taux de Réalisation Validée du Sprint (Indicateur 07 — TRVS)</h4>
                    <span class="text-xs font-mono font-bold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded border border-indigo-200">
                        {{ $kpiIntegriteIA['trvs_formate'] ?? '80.0%' }}
                    </span>
                </div>
                <p class="text-xs text-slate-500">Pourcentage de projets dont l'état final est strictement <strong>Validé (DONE)</strong> au terme du sprint.</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold {{ $kpiIntegriteIA['badge_class'] ?? 'bg-emerald-50 text-emerald-700' }} px-2.5 py-1 rounded-lg">
                    {{ $kpiIntegriteIA['n_validees'] ?? 0 }} / {{ $kpiIntegriteIA['n_attendues'] ?? 0 }} validés
                </span>
                <a href="{{ route('admin.PkgTableauBord.indicateurs.trvs', array_merge(request()->query(), ['from' => 'formateur'])) }}"
                   class="text-xs font-bold text-indigo-600 hover:text-indigo-800 flex items-center gap-1 transition">
                    <span>Voir Détail</span>
                    <i class="fas fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500 font-semibold bg-slate-50/50">
                        <th class="py-2.5 px-4">Stagiaire</th>
                        <th class="py-2.5 px-4">Statut Projet</th>
                        <th class="py-2.5 px-4">Note Évaluée</th>
                        <th class="py-2.5 px-4 text-right">Diagnostic & Progression</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($kpiIntegriteIA['apprenants'] as $appr)
                    <tr class="hover:bg-slate-50/80 transition {{ $appr['est_valide'] ? 'bg-emerald-50/20' : '' }}">
                        <td class="py-2.5 px-4 font-bold text-slate-900 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full {{ $appr['est_valide'] ? 'bg-emerald-500' : ($appr['etat_code'] === 'TO_APPROVE' ? 'bg-amber-500' : 'bg-slate-400') }}"></span>
                            <span>{{ $appr['nom_complet'] }}</span>
                        </td>
                        <td class="py-2.5 px-4">
                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold {{ $appr['badge_class'] }}">
                                {{ $appr['etat_titre'] ?? $appr['badge_label'] }}
                            </span>
                        </td>
                        <td class="py-2.5 px-4 font-mono font-bold text-slate-700">
                            {{ $appr['note'] ? $appr['note'] . ' / 20' : '--' }}
                        </td>
                        <td class="py-2.5 px-4 text-right">
                            @if($appr['est_valide'])
                                <span class="text-emerald-700 font-semibold flex items-center justify-end gap-1">
                                    <i class="fas fa-circle-check text-emerald-600"></i>
                                    <span>Validé (DONE)</span>
                                </span>
                            @elseif($appr['etat_code'] === 'TO_APPROVE')
                                <span class="text-amber-700 font-semibold flex items-center justify-end gap-1">
                                    <i class="fas fa-clock text-amber-500"></i>
                                    <span>En attente validation</span>
                                </span>
                            @else
                                <span class="text-slate-500 flex items-center justify-end gap-1">
                                    <i class="fas fa-spinner text-slate-400"></i>
                                    <span>En cours de réalisation</span>
                                </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    @if($isProjetSessionFormation ?? true)
    <!-- Indicateur 08 — Diagnostic Didactique : Impasses & Points de Fragilité (< 50%) (ICP) -->
    @include('PkgTableauBord::components.impasses-pedagogiques-panel', ['kpi' => $kpiCouverture])

    <!-- Indicateur 09 — Bordereau Officiel & Contrôle Docimologique 65/35 -->
    @include('PkgTableauBord::components.bordereau-officiel-panel', ['kpi' => $kpiDocimologie])
    @endif

    <!-- Deux colonnes d'alertes : Retard amorçage & Remédiation requise -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        <!-- Liste des apprenants en retard d'amorçage si présents -->
        @if(!empty($kpiAmorcage['apprenants_en_retard']))
        <div class="bg-amber-50 border border-amber-200 rounded-lg p-5 shadow-2xs">
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
        @if(($isProjetSessionFormation ?? true) && !empty($kpiGating['apprenants_en_remediation']))
        <div class="bg-rose-50 border border-rose-200 rounded-lg p-5 shadow-2xs">
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

<!-- Télémétrie d'activité formateur avec verrou d'inactivité à 5 minutes -->
@include('PkgTableauBord::components.telemetrie-activite', ['typeActivite' => 'PILOTAGE_KANBAN'])
@endsection