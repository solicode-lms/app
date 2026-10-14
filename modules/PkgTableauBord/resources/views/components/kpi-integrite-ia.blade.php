@props([
'type' => 'formateur',
'kpi' => []
])

@if($type === 'formateur')
<!-- Carte KPI Formateur : Cohérence Docimologique Usage IA ($r) -->
<div class="bg-white p-5 rounded-lg border border-slate-200 shadow-sm relative group hover:border-indigo-300 transition flex flex-col justify-between">
    <div>
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold text-slate-500">Cohérence Usage IA</span>
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold {{ $kpi['badge_class'] ?? 'bg-slate-100 text-slate-700' }}">
                {{ $kpi['badge_label'] ?? 'Alignement Robuste' }}
            </span>
        </div>

        <div class="mt-3 flex items-baseline gap-2">
            <span class="text-3xl font-extrabold text-indigo-700 tracking-tight font-mono">
                {{ $kpi['r_formate'] ?? 'r = 0.78' }}
            </span>
        </div>

        <p class="text-xs text-slate-500 mt-2">Corrélation Prototype sous surveillance ($N2$) vs Projet assisté par IA ($N3$).</p>
    </div>

    <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-600">
        <span>Écart suspect (&Delta; &gt; 5 pts)</span>
        <span class="font-bold {{ ($kpi['nb_anomalies'] ?? 0) > 0 ? 'text-rose-600' : 'text-emerald-700' }} font-mono">
            {{ $kpi['nb_anomalies'] ?? 0 }} apprenant{{ ($kpi['nb_anomalies'] ?? 0) > 1 ? 's' : '' }}
        </span>
    </div>
</div>
@elseif($type === 'responsable')
<!-- Carte KPI Responsable : Indice Global d'Intégrité IA Macro ($r) -->
<div class="bg-white p-5 rounded-lg border border-slate-200 shadow-sm relative group hover:border-indigo-300 transition flex flex-col justify-between">
    <div>
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold text-slate-500">Indice d'Intégrité IA</span>
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                Indicateur 07
            </span>
        </div>

        <div class="mt-3 flex items-baseline gap-2">
            <span class="text-3xl font-extrabold text-indigo-700 tracking-tight font-mono">
                {{ $kpi['r_global_formate'] ?? 'r = 0.78' }}
            </span>
        </div>

        <p class="text-xs text-slate-500 mt-2">Concordance docimologique nationale Prototype direct ($N2$) vs Projet ($N3$).</p>
    </div>

    <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-600">
        <span>Cible de robustesse</span>
        <span class="font-bold text-slate-800 font-mono">r &ge; 0.70</span>
    </div>
</div>
@endif