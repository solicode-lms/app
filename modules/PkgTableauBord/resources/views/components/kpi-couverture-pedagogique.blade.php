@props([
    'type' => 'formateur',
    'kpi' => []
])

@if($type === 'apprenant')
    <!-- Carte KPI Apprenant : Progression dans le Référentiel National (ICP) -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm relative group hover:border-emerald-300 transition flex flex-col justify-between">
        <div>
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500">Progression Référentiel National</span>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold {{ $kpi['badge_class'] ?? 'bg-emerald-50 text-emerald-700 border border-emerald-200' }}">
                    {{ $kpi['statut'] ?? 'Sur la trajectoire' }}
                </span>
            </div>

            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-3xl font-extrabold text-emerald-600 tracking-tight font-mono">
                    {{ $kpi['taux_formate'] ?? '85%' }}
                </span>
                <span class="text-xs font-semibold text-slate-400">validé</span>
            </div>

            <p class="text-xs text-slate-500 mt-2">
                {{ $kpi['validees'] ?? 17 }} sur {{ $kpi['total'] ?? 20 }} compétences nationales acquises.
            </p>

            <!-- Barre de progression -->
            <div class="mt-3 w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                <div class="bg-emerald-500 h-2 rounded-full transition-all duration-500" style="width: {{ min(100, $kpi['taux'] ?? 85) }}%"></div>
            </div>
        </div>

        <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-600">
            <span>Reste à valider</span>
            <span class="font-bold text-slate-800 font-mono">{{ $kpi['non_validees'] ?? 3 }} compétence{{ ($kpi['non_validees'] ?? 3) > 1 ? 's' : '' }}</span>
        </div>
    </div>

@elseif($type === 'formateur')
    <!-- Carte KPI Formateur : Couverture Pédagogique (ICP Cohorte) -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm relative group hover:border-emerald-300 transition flex flex-col justify-between">
        <div>
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500">Couverture Pédagogique (ICP)</span>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold {{ $kpi['badge_class'] ?? 'bg-slate-100 text-slate-700' }}">
                    {{ $kpi['statut'] ?? 'Acquisition groupe' }}
                </span>
            </div>

            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-3xl font-extrabold text-emerald-600 tracking-tight font-mono">
                    {{ $kpi['taux_icp_formate'] ?? '84%' }}
                </span>
                <span class="text-xs text-slate-400">programme national</span>
            </div>

            <p class="text-xs text-slate-500 mt-2">
                Progression collective sur l'ensemble du référentiel hors découpage module.
            </p>

            <!-- Barre de progression cohorte -->
            <div class="mt-3 w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                <div class="bg-emerald-500 h-2 rounded-full transition-all duration-500" style="width: {{ min(100, $kpi['taux_icp'] ?? 84) }}%"></div>
            </div>
        </div>

        <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-600">
            <span>Impasses didactiques (&lt; 50%)</span>
            <span class="font-bold {{ ($kpi['nb_impasses'] ?? 0) > 0 ? 'text-amber-600' : 'text-emerald-700' }} font-mono">
                {{ $kpi['nb_impasses'] ?? 0 }} compétence{{ ($kpi['nb_impasses'] ?? 0) > 1 ? 's' : '' }}
            </span>
        </div>
    </div>

@elseif($type === 'responsable')
    <!-- Carte KPI Responsable : Taux de Couverture Macro du Référentiel National (ICP) -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm relative group hover:border-emerald-300 transition flex flex-col justify-between">
        <div>
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500">Couverture Référentiel National (ICP)</span>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    Indicateur 08
                </span>
            </div>

            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-3xl font-extrabold text-emerald-600 tracking-tight font-mono">
                    {{ $kpi['taux_macro_formate'] ?? '91.2%' }}
                </span>
                <span class="text-xs text-slate-400">macro établissement</span>
            </div>

            <p class="text-xs text-slate-500 mt-2">
                Taux d'assimilation national cumulé sur l'ensemble des filières et promotions.
            </p>

            <!-- Barre de progression macro -->
            <div class="mt-3 w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                <div class="bg-emerald-500 h-2 rounded-full transition-all duration-500" style="width: {{ min(100, $kpi['taux_macro'] ?? 91.2) }}%"></div>
            </div>
        </div>

        <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-600">
            <span>Seuil cible national</span>
            <span class="font-bold text-slate-800 font-mono">&ge; 85% ({{ ($kpi['ecart_cible'] ?? 0) >= 0 ? '+' : '' }}{{ $kpi['ecart_cible'] ?? 6.2 }} pts)</span>
        </div>
    </div>
@endif
