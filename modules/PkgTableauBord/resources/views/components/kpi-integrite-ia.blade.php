@props([
    'type' => 'formateur',
    'kpi' => []
])

@if($type === 'formateur')
    <!-- Carte KPI Formateur : Taux de Réalisation Validée par Sprint (TRVS) -->
    <div class="bg-white p-4 rounded-lg border border-slate-200/90 shadow-2xs hover:shadow-sm hover:border-indigo-300 transition duration-200 flex flex-col justify-between h-full relative group min-w-0">
        <div>
            <div class="flex items-start justify-between gap-1.5 min-w-0 mb-1.5">
                <div class="min-w-0 flex-1">
                    <span class="text-[11px] sm:text-xs font-bold text-slate-500 leading-tight block line-clamp-2" title="Taux de Réalisation Validée par Sprint (TRVS = N_validées / N_attendues)">
                        Taux Réalisation (TRVS)
                    </span>
                </div>
                <span class="shrink-0 inline-flex items-center gap-1 px-1.5 py-0.5 rounded-md text-[10px] font-bold {{ $kpi['badge_class'] ?? 'bg-emerald-50 text-emerald-700 border border-emerald-200' }}" title="{{ $kpi['badge_label'] ?? '' }}">
                    <span class="w-1.5 h-1.5 rounded-full bg-current opacity-75 shrink-0"></span>
                    <span class="truncate max-w-[95px] sm:max-w-[120px]">{{ $kpi['badge_label'] ?? 'Performance' }}</span>
                </span>
            </div>

            <div class="my-2 flex items-baseline gap-1.5">
                <span class="text-2xl sm:text-3xl font-black text-indigo-700 tracking-tight font-mono">
                    {{ $kpi['trvs_formate'] ?? ($kpi['r_formate'] ?? '80.0%') }}
                </span>
                <span class="text-[11px] text-slate-400 font-medium">validés (DONE)</span>
            </div>

            <p class="text-[11px] text-slate-400 line-clamp-2 leading-relaxed mb-2" title="{{ $kpi['message'] ?? 'Part des projets validés en fin de sprint.' }}">
                {{ $kpi['message'] ?? ($kpi['n_validees'] ?? 0) . ' sur ' . ($kpi['n_attendues'] ?? 0) . ' projets validés au terme du sprint.' }}
            </p>
        </div>

        <div>
            <div class="pt-2 mt-auto border-t border-slate-100 flex flex-wrap items-center justify-between gap-x-2 gap-y-1 text-xs">
                <span class="text-slate-500 font-medium truncate flex items-center gap-1.5" title="Projets validés vs attendus">
                    <i class="fas fa-circle-check text-[11px] text-emerald-600 shrink-0"></i>
                    <span>Validés / Total :</span>
                </span>
                <span class="font-bold text-slate-800 font-mono text-xs shrink-0">
                    {{ $kpi['n_validees'] ?? 0 }} / {{ $kpi['n_attendues'] ?? 0 }}
                </span>
            </div>
            <div class="pt-2 mt-1.5 border-t border-slate-50 flex items-center justify-between text-[11px]">
                <span class="text-[10px] text-slate-400 font-medium">Indicateur #07</span>
                <a href="{{ route('admin.PkgTableauBord.indicateurs.trvs', array_merge(request()->query(), ['from' => 'formateur'])) }}" 
                   class="text-indigo-600 hover:text-indigo-800 font-bold flex items-center gap-1 transition"
                   title="Consulter le détail TRVS et l'impact Live Coding">
                    <span>Détail TRVS</span>
                    <i class="fas fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>
    </div>

@elseif($type === 'responsable')
    <!-- Carte KPI Responsable : Taux Global TRVS Macro & Impact Live Coding -->
    <div class="bg-white p-4 rounded-lg border border-slate-200/90 shadow-2xs hover:shadow-sm hover:border-indigo-300 transition duration-200 flex flex-col justify-between h-full relative group min-w-0">
        <div>
            <div class="flex items-start justify-between gap-1.5 min-w-0 mb-1.5">
                <div class="min-w-0 flex-1">
                    <span class="text-[11px] sm:text-xs font-bold text-slate-500 leading-tight block line-clamp-2" title="Taux de Réalisation Validée Macro & Impact du Live Coding">
                        Taux Réalisation Macro
                    </span>
                </div>
                <span class="shrink-0 inline-flex items-center gap-1 px-1.5 py-0.5 rounded-md text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                    &Delta; {{ $kpi['delta_trvs_formate'] ?? '+17.5 pts' }}
                </span>
            </div>

            <div class="my-2 flex items-baseline gap-1.5">
                <span class="text-2xl sm:text-3xl font-black text-indigo-700 tracking-tight font-mono">
                    {{ $kpi['trvs_global_formate'] ?? ($kpi['r_global_formate'] ?? '71.4%') }}
                </span>
                <span class="text-[11px] text-slate-400 font-medium">TRVS national</span>
            </div>

            <p class="text-[11px] text-slate-400 line-clamp-2 leading-relaxed mb-2" title="Impact Live Coding : {{ $kpi['avant_lc']['trvs_formate'] ?? '62.5%' }} avant LC &rarr; {{ $kpi['apres_lc']['trvs_formate'] ?? '80.0%' }} après LC.">
                Avant LC : <strong class="text-slate-600">{{ $kpi['avant_lc']['trvs_formate'] ?? '62.5%' }}</strong> &rarr; Post-LC : <strong class="text-emerald-700">{{ $kpi['apres_lc']['trvs_formate'] ?? '80.0%' }}</strong>
            </p>
        </div>

        <div>
            <div class="pt-2 mt-auto border-t border-slate-100 flex flex-wrap items-center justify-between gap-x-2 gap-y-1 text-xs">
                <span class="text-slate-500 font-medium truncate flex items-center gap-1.5" title="Impact net du Live Coding sur le taux de validation">
                    <i class="fas fa-bolt text-[11px] text-amber-500 shrink-0"></i>
                    <span>Gain net Live Coding :</span>
                </span>
                <span class="font-bold text-emerald-700 font-mono text-xs shrink-0">
                    {{ $kpi['delta_trvs_formate'] ?? '+17.5 pts' }}
                </span>
            </div>
            <div class="pt-2 mt-1.5 border-t border-slate-50 flex items-center justify-between text-[11px]">
                <span class="text-[10px] text-slate-400 font-medium">Indicateur #07</span>
                <a href="{{ route('admin.PkgTableauBord.indicateurs.trvs', array_merge(request()->query(), ['from' => 'responsable'])) }}" 
                   class="text-indigo-600 hover:text-indigo-800 font-bold flex items-center gap-1 transition"
                   title="Consulter le comparatif Avant/Après Live Coding et la courbe chronologique">
                    <span>Analyse TRVS</span>
                    <i class="fas fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>
    </div>
@endif