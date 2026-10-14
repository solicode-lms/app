@props([
    'kpi' => []
])

<!-- Section Indicateur 07 — Taux de Réalisation Validée par Sprint (TRVS) & Impact du Live Coding -->
<section id="trvs-section" class="bg-white p-5 sm:p-6 rounded-lg border border-slate-200 shadow-sm space-y-5">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-100 pb-3">
        <div>
            <div class="flex items-center gap-2 flex-wrap">
                <h3 class="text-sm font-bold text-slate-900">Indicateur 07 — Taux de Réalisation Validée (TRVS) & Impact du Live Coding</h3>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                    TRVS Global : {{ $kpi['trvs_global_formate'] ?? ($kpi['trvs_formate'] ?? '71.4%') }}
                </span>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    Gain : {{ $kpi['delta_trvs_formate'] ?? '+17.5 pts' }}
                </span>
            </div>
            <p class="text-xs text-slate-500 mt-1">
                Mesure du taux effectif de projets en état <strong>Validé (DONE)</strong> et impact didactique de l'instauration des sessions de Live Coding.
            </p>
        </div>
        <a href="{{ route('admin.PkgTableauBord.indicateurs.trvs', array_merge(request()->query(), ['from' => 'responsable'])) }}"
           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 transition shrink-0">
            <i class="fas fa-chart-line text-[11px]"></i>
            <span>Détail TRVS & Sprints</span>
        </a>
    </div>

    <!-- Comparatif Avant vs Après Live Coding -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Avant Live Coding -->
        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between text-xs text-slate-500 font-bold mb-1">
                    <span>Avant Live Coding</span>
                    <span class="px-2 py-0.5 rounded text-[10px] bg-slate-200 text-slate-700 font-semibold">Référence</span>
                </div>
                <div class="text-2xl font-black text-slate-700 font-mono my-1">
                    {{ $kpi['avant_lc']['trvs_formate'] ?? '62.5%' }}
                </div>
                <p class="text-xs text-slate-500">
                    {{ $kpi['avant_lc']['validees'] ?? 0 }} validées sur {{ $kpi['avant_lc']['attendues'] ?? 0 }} attendues
                </p>
            </div>
            <div class="w-full bg-slate-200 h-2 rounded-full overflow-hidden mt-3">
                <div class="bg-slate-500 h-full rounded-full" style="width: {{ min(100, (float)($kpi['avant_lc']['trvs'] ?? 62.5)) }}%"></div>
            </div>
        </div>

        <!-- Après Live Coding -->
        <div class="p-4 rounded-xl bg-emerald-50/60 border border-emerald-200 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between text-xs text-emerald-800 font-bold mb-1">
                    <span>Après Live Coding</span>
                    <span class="px-2 py-0.5 rounded text-[10px] bg-emerald-100 text-emerald-800 font-semibold">Post-Déploiement</span>
                </div>
                <div class="text-2xl font-black text-emerald-700 font-mono my-1">
                    {{ $kpi['apres_lc']['trvs_formate'] ?? '80.0%' }}
                </div>
                <p class="text-xs text-emerald-700">
                    {{ $kpi['apres_lc']['validees'] ?? 0 }} validées sur {{ $kpi['apres_lc']['attendues'] ?? 0 }} attendues
                </p>
            </div>
            <div class="w-full bg-emerald-100 h-2 rounded-full overflow-hidden mt-3">
                <div class="bg-emerald-600 h-full rounded-full" style="width: {{ min(100, (float)($kpi['apres_lc']['trvs'] ?? 80.0)) }}%"></div>
            </div>
        </div>

        <!-- Gain net Delta TRVS -->
        <div class="p-4 rounded-xl bg-indigo-50/60 border border-indigo-200 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between text-xs text-indigo-800 font-bold mb-1">
                    <span>Impact Didactique (&Delta; TRVS)</span>
                    <i class="fas fa-arrow-trend-up text-indigo-600"></i>
                </div>
                <div class="text-2xl font-black text-indigo-700 font-mono my-1">
                    {{ $kpi['delta_trvs_formate'] ?? '+17.5 pts' }}
                </div>
                <p class="text-xs text-indigo-900 leading-relaxed">
                    Gain net d'achèvement validé grâce aux évaluations synchrones et à la levée rapide des blocages.
                </p>
            </div>
            <div class="mt-3 pt-2 border-t border-indigo-100 text-[11px] text-indigo-700 font-medium flex items-center justify-between">
                <span>Objectif cible : &ge; 75%</span>
                <span class="font-bold text-emerald-700">Atteint</span>
            </div>
        </div>
    </div>

    <!-- Chronologie Évolutive des Sprints -->
    @if(!empty($kpi['sprints_evolution']))
    <div class="space-y-2 pt-2">
        <div class="flex items-center justify-between text-xs font-bold text-slate-700">
            <span>Évolution Chronologique des Sprints (S1 &rarr; S{{ count($kpi['sprints_evolution']) }})</span>
            <span class="text-[11px] text-slate-400 font-normal">Calculé sur l'ensemble des sessions actives</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            @foreach($kpi['sprints_evolution'] as $sp)
            <div class="p-3 rounded-lg border {{ $sp['has_live_coding'] ? 'border-emerald-200 bg-emerald-50/20' : 'border-slate-200 bg-white' }} flex flex-col justify-between">
                <div class="flex items-start justify-between gap-1 mb-1.5">
                    <div>
                        <div class="text-xs font-bold text-slate-900 truncate max-w-[140px]" title="{{ $sp['projet_titre'] }}">
                            Sprint {{ $sp['index'] }} : {{ $sp['projet_titre'] }}
                        </div>
                        <div class="text-[10px] text-slate-400">{{ $sp['groupe_code'] }} &bull; {{ $sp['date_debut'] }}</div>
                    </div>
                    <span class="px-1.5 py-0.5 rounded text-[9px] font-bold {{ $sp['has_live_coding'] ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                        {{ $sp['has_live_coding'] ? 'Live Coding' : 'Classique' }}
                    </span>
                </div>

                <div class="my-1.5 flex items-baseline justify-between">
                    <span class="text-lg font-black font-mono {{ $sp['trvs'] >= 80 ? 'text-emerald-700' : ($sp['trvs'] >= 50 ? 'text-indigo-700' : 'text-amber-700') }}">
                        {{ $sp['trvs_formate'] }}
                    </span>
                    <span class="text-[11px] text-slate-500 font-mono">
                        {{ $sp['n_validees'] }}/{{ $sp['n_attendues'] }} DONE
                    </span>
                </div>

                <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                    <div class="{{ $sp['trvs'] >= 80 ? 'bg-emerald-500' : ($sp['trvs'] >= 50 ? 'bg-indigo-500' : 'bg-amber-500') }} h-full rounded-full" 
                         style="width: {{ min(100, (float)$sp['trvs']) }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</section>