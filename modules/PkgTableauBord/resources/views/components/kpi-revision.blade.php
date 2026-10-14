@props([
    'type' => 'apprenant',
    'kpi' => []
])

@if($type === 'apprenant')
    <!-- KPI 3 Apprenant : Qualité du premier rendu (F_révision) -->
    <div class="bg-white p-3.5 sm:p-4.5 rounded-lg border border-slate-200/90 shadow-2xs hover:shadow-sm hover:border-indigo-300 transition duration-200 flex flex-col justify-between h-full relative group min-w-0">
        <div>
            <div class="flex items-start justify-between gap-1.5 min-w-0 mb-1.5">
                <div class="min-w-0 flex-1">
                    <span class="text-[11px] sm:text-xs font-bold text-slate-500 leading-tight block line-clamp-2" title="Qualité du premier rendu (F_révision)">
                        Qualité du 1er rendu
                    </span>
                </div>
                <span class="shrink-0 inline-flex items-center gap-1 px-1.5 py-0.5 rounded-md text-[10px] font-bold {{ $kpi['statut_badge'] ?? 'bg-slate-50 text-slate-700 border border-slate-200' }}" title="{{ $kpi['statut'] ?? '' }}">
                    <span class="w-1.5 h-1.5 rounded-full bg-current opacity-75 shrink-0"></span>
                    <span class="truncate max-w-[95px] sm:max-w-[120px]">{{ $kpi['statut'] ?? 'En attente' }}</span>
                </span>
            </div>

            <div class="my-2 flex items-baseline gap-1.5">
                <span class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight font-mono">{{ $kpi['taux_premier_jet_formate'] ?? '100%' }}</span>
                <span class="text-[11px] text-slate-400 font-medium">réussite directe</span>
            </div>

            <p class="text-[11px] text-slate-400 line-clamp-2 leading-relaxed mb-2" title="{{ $kpi['description'] ?? '' }}">
                {{ $kpi['description'] ?? 'Mesure de la stabilité du code et de l\'autonomie dès le premier rendu soumis.' }}
            </p>
        </div>

        <div class="pt-2 mt-auto border-t border-slate-100 flex flex-wrap items-center justify-between gap-x-2 gap-y-1 text-xs">
            <span class="text-slate-500 font-medium truncate flex items-center gap-1.5" title="Nombre moyen de reprises par tâche">
                <i class="fas fa-arrows-rotate text-[11px] text-slate-400 shrink-0"></i>
                <span>Reprises : <strong class="text-slate-800 font-mono">{{ $kpi['frequence_formatee'] ?? '0.0' }}</strong></span>
            </span>
            <span class="font-bold text-emerald-700 text-xs shrink-0 font-mono">
                {{ $kpi['taches_sans_reprise'] ?? 0 }}/{{ $kpi['total_taches'] ?? 0 }} direct
            </span>
        </div>
    </div>

@elseif($type === 'formateur')
    <!-- KPI 3 Formateur : Fréquence Moyenne des Boucles de Révision (F_révision) -->
    <div class="bg-white p-3.5 sm:p-4.5 rounded-lg border border-slate-200/90 shadow-2xs hover:shadow-sm hover:border-indigo-300 transition duration-200 flex flex-col justify-between h-full relative group min-w-0">
        <div>
            <div class="flex items-start justify-between gap-1.5 min-w-0 mb-1.5">
                <div class="min-w-0 flex-1">
                    <span class="text-[11px] sm:text-xs font-bold text-slate-500 leading-tight block line-clamp-2" title="Fréquence Moyenne des Révisions (F_révision)">
                        Fréquence Révisions (F<sub>révision</sub>)
                    </span>
                </div>
                <span class="shrink-0 inline-flex items-center gap-1 px-1.5 py-0.5 rounded-md text-[10px] font-bold {{ $kpi['statut_badge'] ?? 'bg-slate-50 text-slate-700 border border-slate-200' }}" title="{{ $kpi['statut'] ?? '' }}">
                    <span class="w-1.5 h-1.5 rounded-full bg-current opacity-75 shrink-0"></span>
                    <span class="truncate max-w-[95px] sm:max-w-[120px]">{{ $kpi['statut'] ?? 'En attente' }}</span>
                </span>
            </div>

            <div class="my-2 flex items-baseline gap-1.5">
                <span class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight font-mono">{{ $kpi['frequence_formatee'] ?? '0.0 reprise' }}</span>
                <span class="text-[11px] text-slate-400 font-medium">/ tâche (moy.)</span>
            </div>

            <p class="text-[11px] text-slate-400 line-clamp-2 leading-relaxed mb-2" title="{{ $kpi['description'] ?? '' }}">
                {{ $kpi['description'] ?? 'Suivi de la stabilité du code et détection des consignes pédagogiques ambiguës.' }}
            </p>
        </div>

        <div class="pt-2 mt-auto border-t border-slate-100 flex flex-wrap items-center justify-between gap-x-2 gap-y-1 text-xs">
            <span class="text-slate-500 font-medium truncate flex items-center gap-1.5" title="Proportion des tâches validées sans aucune reprise">
                <i class="fas fa-circle-check text-[11px] text-emerald-600 shrink-0"></i>
                <span class="truncate">{{ $kpi['taux_premier_jet_formate'] ?? '100% direct' }}</span>
            </span>
            <span class="font-bold text-slate-800 font-mono text-xs shrink-0">
                {{ $kpi['total_rejets'] ?? 0 }} rejet{{ ($kpi['total_rejets'] ?? 0) > 1 ? 's' : '' }}
            </span>
        </div>
    </div>

@elseif($type === 'responsable')
    <!-- KPI 3 Responsable : Fréquence macroscopique moyenne -->
    <div class="bg-white p-3.5 sm:p-4.5 rounded-lg border border-slate-200/90 shadow-2xs hover:shadow-sm hover:border-indigo-300 transition duration-200 flex flex-col justify-between h-full relative group min-w-0">
        <div>
            <div class="flex items-start justify-between gap-1.5 min-w-0 mb-1.5">
                <div class="min-w-0 flex-1">
                    <span class="text-[11px] sm:text-xs font-bold text-slate-500 leading-tight block line-clamp-2" title="Fréquence Macro des Révisions (F_révision)">
                        Fréquence Macro (F<sub>révision</sub>)
                    </span>
                </div>
                <span class="shrink-0 inline-flex items-center gap-1 px-1.5 py-0.5 rounded-md text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                    Indicateur 03
                </span>
            </div>

            <div class="my-2 flex items-baseline gap-1.5">
                <span class="text-2xl sm:text-3xl font-black text-indigo-700 tracking-tight font-mono">
                    {{ $kpi['frequence_globale_formatee'] ?? '--' }}
                </span>
                <span class="text-[11px] text-slate-400 font-medium">reprises / tâche</span>
            </div>

            <p class="text-[11px] text-slate-400 line-clamp-2 leading-relaxed mb-2" title="Moyenne de reprises par tâche sur l'ensemble des promotions et modules actifs.">
                Moyenne de reprises par tâche sur l'ensemble des promotions et modules actifs.
            </p>
        </div>

        <div class="pt-2 mt-auto border-t border-slate-100 flex flex-wrap items-center justify-between gap-x-2 gap-y-1 text-xs">
            <span class="text-slate-500 font-medium truncate flex items-center gap-1.5" title="Seuil didactique cible acceptable">
                <i class="fas fa-bullseye text-[11px] text-slate-400 shrink-0"></i>
                <span>Seuil didactique</span>
            </span>
            <span class="font-bold text-slate-800 font-mono text-xs shrink-0">
                &le; 1.2 reprise
            </span>
        </div>
    </div>
@endif