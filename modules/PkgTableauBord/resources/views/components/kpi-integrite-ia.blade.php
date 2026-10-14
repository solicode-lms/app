@props([
    'type' => 'formateur',
    'kpi' => []
])

@if($type === 'formateur')
    <!-- Carte KPI Formateur : Cohérence Docimologique Usage IA (r) -->
    <div class="bg-white p-3.5 sm:p-4.5 rounded-lg border border-slate-200/90 shadow-2xs hover:shadow-sm hover:border-indigo-300 transition duration-200 flex flex-col justify-between h-full relative group min-w-0">
        <div>
            <div class="flex items-start justify-between gap-1.5 min-w-0 mb-1.5">
                <div class="min-w-0 flex-1">
                    <span class="text-[11px] sm:text-xs font-bold text-slate-500 leading-tight block line-clamp-2" title="Cohérence Docimologique & Usage de l'IA (Pearson r)">
                        Cohérence Usage IA
                    </span>
                </div>
                <span class="shrink-0 inline-flex items-center gap-1 px-1.5 py-0.5 rounded-md text-[10px] font-bold {{ $kpi['badge_class'] ?? 'bg-slate-100 text-slate-700' }}" title="{{ $kpi['badge_label'] ?? '' }}">
                    <span class="w-1.5 h-1.5 rounded-full bg-current opacity-75 shrink-0"></span>
                    <span class="truncate max-w-[95px] sm:max-w-[120px]">{{ $kpi['badge_label'] ?? 'Alignement' }}</span>
                </span>
            </div>

            <div class="my-2 flex items-baseline gap-1.5">
                <span class="text-2xl sm:text-3xl font-black text-indigo-700 tracking-tight font-mono">
                    {{ $kpi['r_formate'] ?? 'r = 0.78' }}
                </span>
                <span class="text-[11px] text-slate-400 font-medium">Pearson r</span>
            </div>

            <p class="text-[11px] text-slate-400 line-clamp-2 leading-relaxed mb-2" title="{{ $kpi['description'] ?? 'Corrélation Prototype sous surveillance (N2) vs Projet assisté par IA (N3).' }}">
                {{ $kpi['description'] ?? 'Corrélation Prototype sous surveillance (N2) vs Projet assisté par IA (N3).' }}
            </p>
        </div>

        <div class="pt-2 mt-auto border-t border-slate-100 flex flex-wrap items-center justify-between gap-x-2 gap-y-1 text-xs">
            <span class="text-slate-500 font-medium truncate flex items-center gap-1.5" title="Nombre d'apprenants présentant un écart de note suspect (&Delta; > 5 points)">
                <i class="fas fa-triangle-exclamation text-[11px] text-amber-500 shrink-0"></i>
                <span>Écart suspect (&Delta; &gt; 5) :</span>
            </span>
            <span class="font-bold {{ ($kpi['nb_anomalies'] ?? 0) > 0 ? 'text-rose-600' : 'text-emerald-700' }} font-mono text-xs shrink-0">
                {{ $kpi['nb_anomalies'] ?? 0 }} cas
            </span>
        </div>
    </div>

@elseif($type === 'responsable')
    <!-- Carte KPI Responsable : Indice Global d'Intégrité IA Macro (r) -->
    <div class="bg-white p-3.5 sm:p-4.5 rounded-lg border border-slate-200/90 shadow-2xs hover:shadow-sm hover:border-indigo-300 transition duration-200 flex flex-col justify-between h-full relative group min-w-0">
        <div>
            <div class="flex items-start justify-between gap-1.5 min-w-0 mb-1.5">
                <div class="min-w-0 flex-1">
                    <span class="text-[11px] sm:text-xs font-bold text-slate-500 leading-tight block line-clamp-2" title="Indice Global d'Intégrité IA Macro (Pearson r)">
                        Indice d'Intégrité IA
                    </span>
                </div>
                <span class="shrink-0 inline-flex items-center gap-1 px-1.5 py-0.5 rounded-md text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                    Indicateur 07
                </span>
            </div>

            <div class="my-2 flex items-baseline gap-1.5">
                <span class="text-2xl sm:text-3xl font-black text-indigo-700 tracking-tight font-mono">
                    {{ $kpi['r_global_formate'] ?? 'r = 0.78' }}
                </span>
                <span class="text-[11px] text-slate-400 font-medium">concordance macro</span>
            </div>

            <p class="text-[11px] text-slate-400 line-clamp-2 leading-relaxed mb-2" title="Concordance docimologique nationale Prototype direct (N2) vs Projet (N3).">
                Concordance docimologique nationale Prototype direct (N2) vs Projet (N3).
            </p>
        </div>

        <div class="pt-2 mt-auto border-t border-slate-100 flex flex-wrap items-center justify-between gap-x-2 gap-y-1 text-xs">
            <span class="text-slate-500 font-medium truncate flex items-center gap-1.5" title="Seuil cible de robustesse méthodologique">
                <i class="fas fa-scale-balanced text-[11px] text-slate-400 shrink-0"></i>
                <span>Cible robustesse</span>
            </span>
            <span class="font-bold text-slate-800 font-mono text-xs shrink-0">
                r &ge; 0.70
            </span>
        </div>
    </div>
@endif