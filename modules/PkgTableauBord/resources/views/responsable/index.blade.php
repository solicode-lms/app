@extends('layouts.admin-v2')

@section('title', 'Tableau de Bord Responsable — Solicode LMS V2')
@section('page_title', 'Tableau de Bord de Gouvernance Pédagogique')
@section('page_subtitle', 'Indicateurs macroscopiques et benchmark comparatif des cohortes')

@section('content')
    <!-- En-tête contextuel Responsable -->
    <section class="bg-white rounded-2xl border border-slate-200 p-5 sm:p-6 shadow-sm">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                    Direction Pédagogique
                </span>
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

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
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
        </div>

        <!-- Matrice Comparative / Benchmark Réel des Promotions -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-200 bg-slate-50/70">
                <h4 class="text-sm font-bold text-slate-900">Matrice Comparative Inter-Promotions</h4>
                <p class="text-xs text-slate-500">Benchmark des cohortes pour l'harmonisation du rythme pédagogique ($T_{\text{amorçage}}$ & $R_{\text{gating}}$)</p>
            </div>

            @php
                $gatingByAff = collect($kpiGating['benchmark'] ?? [])->keyBy('affectation_id');
            @endphp

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-500 font-semibold bg-slate-50/50">
                            <th class="py-3 px-5">Groupe / Promotion</th>
                            <th class="py-3 px-4">Délai Moyen ($T_{\text{amorçage}}$)</th>
                            <th class="py-3 px-4">Taux d'Amorçage</th>
                            <th class="py-3 px-4">Validation Prototype ($R_{\text{gating}}$)</th>
                            <th class="py-3 px-5 text-right">Statut</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($kpiAmorcage['benchmark'] ?? [] as $item)
                            @php
                                $gatingItem = $gatingByAff->get($item['affectation_id']);
                                $tauxGating = $gatingItem['taux_gating'] ?? null;
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
                                <td class="py-3 px-5 text-right">
                                    @php
                                        $estConforme = $item['taux_amorce'] >= 75 && (is_null($tauxGating) || $tauxGating >= 75);
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
                                <td colspan="5" class="py-6 text-center text-slate-400">Aucune donnée de promotion disponible.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
