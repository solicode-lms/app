@props([
    'type' => 'formateur',
    'kpi' => []
])

@if($type === 'formateur')
    <!-- Carte KPI Formateur : Taux d'Intervention et de Remédiation (T_intervention) -->
    <div class="bg-white p-4 rounded-lg border border-slate-200/90 shadow-2xs hover:shadow-sm hover:border-indigo-300 transition duration-200 flex flex-col justify-between h-full relative group min-w-0">
        <div>
            <div class="flex items-start justify-between gap-1.5 min-w-0 mb-1.5">
                <div class="min-w-0 flex-1">
                    <span class="text-[11px] sm:text-xs font-bold text-slate-500 leading-tight block line-clamp-2" title="Prise en charge des blocages (T_intervention)">
                        Prise en charge blocages
                    </span>
                </div>
                <span class="shrink-0 inline-flex items-center gap-1 px-1.5 py-0.5 rounded-md text-[10px] font-bold {{ $kpi['badge_class'] ?? 'bg-slate-100 text-slate-700' }}" title="{{ $kpi['badge_label'] ?? '' }}">
                    <span class="w-1.5 h-1.5 rounded-full bg-current opacity-75 shrink-0"></span>
                    <span class="truncate max-w-[95px] sm:max-w-[120px]">{{ $kpi['badge_label'] ?? 'Excellence' }}</span>
                </span>
            </div>

            <div class="my-2 flex items-baseline gap-1.5">
                <span class="text-2xl sm:text-3xl font-black text-emerald-600 tracking-tight font-mono">
                    {{ $kpi['taux_intervention_formate'] ?? '100%' }}
                </span>
                <span class="text-[11px] text-slate-400 font-medium">couverture</span>
            </div>

            <p class="text-[11px] text-slate-400 line-clamp-2 leading-relaxed mb-2" title="{{ $kpi['description'] ?? 'Taux de remédiation des blocages post-Live Coding (+10 min).' }}">
                {{ $kpi['description'] ?? 'Taux de remédiation des blocages post-Live Coding (+' . ($kpi['seuil_minutes'] ?? 10) . ' min).' }}
            </p>
        </div>

        <div class="pt-2 mt-auto border-t border-slate-100 flex flex-wrap items-center justify-between gap-x-2 gap-y-1 text-xs">
            <span class="text-slate-500 font-medium truncate flex items-center gap-1.5" title="Nombre d'apprenants en attente d'intervention de remédiation">
                <i class="fas fa-hand-holding-hand text-[11px] text-slate-400 shrink-0"></i>
                <span>En attente :</span>
            </span>
            <span class="font-bold {{ ($kpi['en_attente_soutien'] ?? 0) > 0 ? 'text-rose-600' : 'text-emerald-700' }} font-mono text-xs shrink-0">
                {{ $kpi['en_attente_soutien'] ?? 0 }} apprenant{{ ($kpi['en_attente_soutien'] ?? 0) > 1 ? 's' : '' }}
            </span>
        </div>
        <div class="pt-2 mt-1.5 border-t border-slate-50 flex items-center justify-between text-[11px]">
            <span class="text-[10px] text-slate-400 font-medium">Indicateur #06</span>
            <span class="text-[10px] text-emerald-600 font-bold font-mono">T<sub>intervention</sub></span>
        </div>
    </div>

@elseif($type === 'responsable')
    <!-- Carte KPI Responsable : Taux Moyen de Remédiation Macro (T_intervention) -->
    <div class="bg-white p-4 rounded-lg border border-slate-200/90 shadow-2xs hover:shadow-sm hover:border-indigo-300 transition duration-200 flex flex-col justify-between h-full relative group min-w-0">
        <div>
            <div class="flex items-start justify-between gap-1.5 min-w-0 mb-1.5">
                <div class="min-w-0 flex-1">
                    <span class="text-[11px] sm:text-xs font-bold text-slate-500 leading-tight block line-clamp-2" title="Taux Moyen Remédiation Macro (T_intervention)">
                        Taux Moyen Remédiation
                    </span>
                </div>
                <span class="shrink-0 inline-flex items-center gap-1 px-1.5 py-0.5 rounded-md text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    Indicateur 06
                </span>
            </div>

            <div class="my-2 flex items-baseline gap-1.5">
                <span class="text-2xl sm:text-3xl font-black text-emerald-600 tracking-tight font-mono">
                    {{ $kpi['taux_moyen_global_formate'] ?? '100%' }}
                </span>
                <span class="text-[11px] text-slate-400 font-medium">taux moyen macro</span>
            </div>

            <p class="text-[11px] text-slate-400 line-clamp-2 leading-relaxed mb-2" title="Couverture didactique des décrochages post-séance inter-promotions.">
                Couverture didactique des décrochages post-séance inter-promotions.
            </p>
        </div>

        <div class="pt-2 mt-auto border-t border-slate-100 flex flex-wrap items-center justify-between gap-x-2 gap-y-1 text-xs">
            <span class="text-slate-500 font-medium truncate flex items-center gap-1.5" title="Objectif cible de réactivité pédagogique">
                <i class="fas fa-bullseye text-[11px] text-slate-400 shrink-0"></i>
                <span>Cible réactivité</span>
            </span>
            <span class="font-bold text-slate-800 font-mono text-xs shrink-0">
                &ge; 85%
            </span>
        </div>
    </div>
@endif