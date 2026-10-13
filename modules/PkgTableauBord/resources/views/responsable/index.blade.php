@extends('layouts.admin-v2')

@section('title', 'Tableau de Bord Responsable — Solicode LMS V2')
@section('page_title', 'Tableau de Bord de Gouvernance Pédagogique')
@section('page_subtitle', 'Indicateurs macroscopiques et benchmark comparatif des cohortes')

@section('actions')
    @include('PkgTableauBord::components.session-selector', ['sessions' => $sessions ?? collect(), 'sessionActive' => $sessionFormationActive ?? null])
@endsection

@section('content')
    <!-- En-tête contextuel Responsable -->
    <section class="bg-white rounded-2xl border border-slate-200 p-5 sm:p-6 shadow-sm">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                        Direction Pédagogique
                    </span>
                    @if(isset($projetActif) || isset($sessionFormationActive))
                        @php $currentActive = $projetActif ?? $sessionFormationActive; @endphp
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-100/70 text-indigo-800 border border-indigo-300">
                            Projet : {{ $currentActive->titre }}
                        </span>
                    @endif
                </div>
                <h2 class="text-xl font-bold text-slate-900 mt-1">
                    Gouvernance & Cohérence des Formations
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    Suivi macroscopique du rythme d'amorce des projets par filière et détection des écarts de progression.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200">
                    <i class="fas fa-graduation-cap"></i>
                    Toutes promotions actives
                </span>
            </div>
        </div>
    </section>

    <!-- Indicateurs de Gouvernance & Benchmark Macro -->
    <section class="space-y-4">
        <div>
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">Indicateurs Stratégiques & Benchmark Inter-Promotions</h3>
            <p class="text-xs text-slate-400">Suivi global du rythme d'amorce des projets ($T_{\text{amorçage}}$) et du taux de validation directe du prototype ($R_{\text{gating}}$).</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4">
            <!-- Carte Macroscopique Globale Amorçage (Indicateur 01) -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm relative group hover:border-indigo-300 transition">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500">Délai moyen global d'amorçage</span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                        Indicateur 01
                    </span>
                </div>
                <div class="mt-3 flex items-baseline gap-2">
                    <span class="text-3xl font-extrabold text-indigo-700 tracking-tight">
                        {{ $kpiAmorcage['delai_moyen_global_formate'] ?? '--' }}
                    </span>
                </div>
                <p class="text-xs text-slate-500 mt-2">Délai moyen calculé sur toutes les promotions actives récentes.</p>
                <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-600">
                    <span>Promotions analysées</span>
                    <span class="font-bold text-slate-800">{{ count($kpiAmorcage['benchmark'] ?? []) }} groupes</span>
                </div>
            </div>

            <!-- Carte Macroscopique Globale Gating (Indicateur 02) -->
            @include('PkgTableauBord::components.kpi-gating', ['type' => 'responsable', 'kpi' => $kpiGating])

            <!-- Carte Macroscopique Globale Révision (Indicateur 03) -->
            @include('PkgTableauBord::components.kpi-revision', ['type' => 'responsable', 'kpi' => $kpiRevision])

            <!-- Carte Macroscopique Globale Charge Administrative (Indicateur 04) -->
            @include('PkgTableauBord::components.kpi-charge-logistique', ['type' => 'responsable', 'kpi' => $kpiChargeLogistique])

            <!-- Carte Macroscopique Globale Latence File FIFO (Indicateur 05) -->
            @include('PkgTableauBord::components.kpi-file-attente', ['type' => 'responsable', 'kpi' => $kpiFileAttente])
        </div>

        <!-- Matrice Comparative / Benchmark Réel des Promotions -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-200 bg-slate-50/70">
                <h4 class="text-sm font-bold text-slate-900">Matrice Comparative Inter-Promotions</h4>
                <p class="text-xs text-slate-500">Benchmark des cohortes pour l'harmonisation du rythme pédagogique ($T_{\text{amorçage}}$, $R_{\text{gating}}$, $F_{\text{révision}}$ & $T_{\text{file}}$)</p>
            </div>

            @php
                $gatingByAff = collect($kpiGating['benchmark'] ?? [])->keyBy('affectation_id');
                $revisionByAff = collect($kpiRevision['benchmark'] ?? [])->keyBy('affectation_id');
                $fileByAff = collect($kpiFileAttente['benchmark'] ?? [])->keyBy('affectation_id');
            @endphp

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-500 font-semibold bg-slate-50/50">
                            <th class="py-3 px-5">Groupe / Promotion</th>
                            <th class="py-3 px-4">Délai Moyen ($T_{\text{amorçage}}$)</th>
                            <th class="py-3 px-4">Taux d'Amorçage</th>
                            <th class="py-3 px-4">Validation Prototype ($R_{\text{gating}}$)</th>
                            <th class="py-3 px-4">Stabilité & Reprises ($F_{\text{révision}}$)</th>
                            <th class="py-3 px-4">Attente File ($T_{\text{file}}$)</th>
                            <th class="py-3 px-5 text-right">Statut</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($kpiAmorcage['benchmark'] ?? [] as $item)
                            @php
                                $gatingItem = $gatingByAff->get($item['affectation_id']);
                                $tauxGating = $gatingItem['taux_gating'] ?? null;
                                $revItem = $revisionByAff->get($item['affectation_id']);
                                $fileItem = $fileByAff->get($item['affectation_id']);
                            @endphp
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-3 px-5 font-bold text-slate-900">{{ $item['groupe_code'] }} ({{ $item['groupe_nom'] }})</td>
                                <td class="py-3 px-4 font-mono font-bold text-indigo-700">{{ $item['temps_moyen_formate'] }}</td>
                                <td class="py-3 px-4">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $item['taux_amorce'] >= 75 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                                        {{ $item['taux_amorce'] }}% amorcés ({{ $item['nb_amorces'] }}/{{ $item['total_apprenants'] }})
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    @if(!is_null($tauxGating))
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $tauxGating >= 75 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                                            {{ $gatingItem['taux_gating_formate'] }} ({{ $gatingItem['valides_premier_essai'] }}/{{ $gatingItem['total_evalues'] }})
                                        </span>
                                    @else
                                        <span class="text-slate-400 italic">Non évalué</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 font-mono">
                                    @if($revItem)
                                        <span class="font-bold text-slate-800">{{ $revItem['frequence_formatee'] }}</span>
                                        <span class="text-[10px] text-slate-400 block">{{ $revItem['taux_premier_jet_formate'] }} 1er jet</span>
                                    @else
                                        <span class="text-slate-400">--</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 font-mono">
                                    @if($fileItem)
                                        <span class="font-bold text-slate-800">{{ $fileItem['delai_moyen_formate'] }}</span>
                                        <span class="text-[10px] text-slate-400 block">{{ $fileItem['total_en_attente'] }} en file</span>
                                    @else
                                        <span class="text-slate-400">--</span>
                                    @endif
                                </td>
                                <td class="py-3 px-5 text-right">
                                    @php
                                        $estConforme = $item['taux_amorce'] >= 75 
                                            && (is_null($tauxGating) || $tauxGating >= 75)
                                            && (!$revItem || $revItem['frequence_revision'] <= 1.2);
                                    @endphp
                                    @if($estConforme)
                                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">Conforme</span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-300">À réguler</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-6 text-center text-slate-400">Aucune donnée de promotion disponible.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Matrice Benchmark de la Charge Administrative des Formateurs ($C_logistique) -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-200 bg-slate-50/70 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2">
                <div>
                    <h4 class="text-sm font-bold text-slate-900">Charge Administrative par Formateur ($C_{\text{logistique}}$)</h4>
                    <p class="text-xs text-slate-500">Volume horaire hebdomadaire effectif consacré au micro-suivi (Semaine : {{ $kpiChargeLogistique['semaine'] ?? '--' }})</p>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $kpiChargeLogistique['badge_class'] ?? 'bg-emerald-50 text-emerald-700' }}">
                    Moyenne : {{ $kpiChargeLogistique['charge_moyenne_formatee'] ?? '0 h/sem' }}
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-500 font-semibold bg-slate-50/50">
                            <th class="py-3 px-5">Formateur</th>
                            <th class="py-3 px-4">Temps Total ($C_{\text{logistique}}$)</th>
                            <th class="py-3 px-4">Pilotage Kanban</th>
                            <th class="py-3 px-4">File FIFO</th>
                            <th class="py-3 px-4">Bordereaux Notes</th>
                            <th class="py-3 px-5 text-right">Diagnostic</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($kpiChargeLogistique['benchmark'] ?? [] as $fItem)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-3 px-5 font-bold text-slate-900">
                                    {{ $fItem['formateur_nom'] }}
                                    <span class="text-[10px] text-slate-400 block font-normal">{{ $fItem['matricule'] }}</span>
                                </td>
                                <td class="py-3 px-4 font-mono font-bold text-slate-900">
                                    {{ $fItem['total_heures_formate'] }}
                                    <span class="text-[10px] text-slate-400 font-normal">({{ $fItem['total_heures'] }}h)</span>
                                </td>
                                <td class="py-3 px-4 font-mono text-slate-600">{{ $fItem['details']['pilotage_formate'] ?? '0 min' }}</td>
                                <td class="py-3 px-4 font-mono text-slate-600">{{ $fItem['details']['file_formate'] ?? '0 min' }}</td>
                                <td class="py-3 px-4 font-mono text-slate-600">{{ $fItem['details']['bordereaux_formate'] ?? '0 min' }}</td>
                                <td class="py-3 px-5 text-right">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $fItem['badge_class'] }}">
                                        {{ $fItem['badge_label'] }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-6 text-center text-slate-400">Aucune donnée d'activité enregistrée pour cette semaine.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
