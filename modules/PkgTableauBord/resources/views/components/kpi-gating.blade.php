@props([
    'type' => 'apprenant',
    'kpi' => []
])

@if($type === 'apprenant')
    <!-- KPI 2 Apprenant : Validation du Prototype & Gating -->
    <div class="bg-white p-4 rounded-lg border border-slate-200/90 shadow-2xs hover:shadow-sm hover:border-indigo-300 transition duration-200 flex flex-col justify-between h-full relative group min-w-0">
        <div>
            <div class="flex items-start justify-between gap-1.5 min-w-0 mb-1.5">
                <div class="min-w-0 flex-1">
                    <span class="text-[11px] sm:text-xs font-bold text-slate-500 leading-tight block line-clamp-2" title="Validation du Prototype & Gating">
                        Validation Prototype (Gating)
                    </span>
                </div>
                <span class="shrink-0 inline-flex items-center gap-1 px-1.5 py-0.5 rounded-md text-[10px] font-bold {{ $kpi['statut_badge'] ?? 'bg-slate-50 text-slate-700 border border-slate-200' }}" title="{{ $kpi['statut'] ?? '' }}">
                    <span class="w-1.5 h-1.5 rounded-full bg-current opacity-75 shrink-0"></span>
                    <span class="truncate max-w-[95px] sm:max-w-[120px]">{{ $kpi['statut'] ?? 'Non évalué' }}</span>
                </span>
            </div>

            <div class="my-2 flex items-baseline gap-1.5">
                @if(($kpi['is_debloque'] ?? false))
                    <span class="text-xl sm:text-2xl font-black text-emerald-600 tracking-tight font-mono flex items-center gap-1.5">
                        <i class="fas fa-unlock-alt text-base text-emerald-500 shrink-0"></i>
                        <span>Projet Débloqué</span>
                    </span>
                @else
                    <span class="text-xl sm:text-2xl font-black text-amber-600 tracking-tight font-mono flex items-center gap-1.5">
                        <i class="fas fa-lock text-base text-amber-500 shrink-0"></i>
                        <span>{{ ($kpi['tentatives'] ?? 0) > 0 ? 'En remédiation' : 'En attente' }}</span>
                    </span>
                @endif
            </div>

            <p class="text-[11px] text-slate-400 line-clamp-2 leading-relaxed mb-2" title="{{ $kpi['description'] ?? '' }}">
                {{ $kpi['description'] ?? 'Validation autonome des fondamentaux lors de l\'épreuve de Live Coding Niveau 2.' }}
            </p>
        </div>

        <div class="pt-2 mt-auto border-t border-slate-100 flex flex-wrap items-center justify-between gap-x-2 gap-y-1 text-xs">
            <span class="text-slate-500 font-medium truncate flex items-center gap-1.5" title="Nombre de tentatives d'évaluation">
                <i class="fas fa-stopwatch text-[11px] text-slate-400 shrink-0"></i>
                <span>Passage(s) : <strong class="text-slate-800 font-mono">{{ $kpi['tentatives'] ?? 0 }}</strong></span>
            </span>
            <span class="px-1.5 py-0.5 rounded text-[10px] font-bold {{ $kpi['acces_badge'] ?? 'bg-slate-50 text-slate-700 border border-slate-200' }} shrink-0">
                {{ $kpi['acces_projet'] ?? 'Non défini' }}
            </span>
        </div>
        <div class="pt-1.5 mt-1 border-t border-slate-50 flex items-center justify-between text-[11px]">
            <span class="text-[10px] text-slate-400 font-medium">Indicateur #02</span>
            <span class="text-[10px] text-indigo-600 font-bold font-mono">R<sub>gating</sub></span>
        </div>
    </div>

@elseif($type === 'formateur')
    <!-- KPI 2 Formateur : Taux de validation au 1er passage (R_gating) -->
    <div class="bg-white p-4 rounded-lg border border-slate-200/90 shadow-2xs hover:shadow-sm hover:border-indigo-300 transition duration-200 flex flex-col justify-between h-full relative group min-w-0">
        <div>
            <div class="flex items-start justify-between gap-1.5 min-w-0 mb-1.5">
                <div class="min-w-0 flex-1">
                    <a href="{{ route('admin.PkgTableauBord.indicateurs.gating', array_merge(request()->query(), ['from' => 'formateur'])) }}" 
                       class="text-[11px] sm:text-xs font-bold text-slate-700 hover:text-indigo-600 leading-tight block line-clamp-2 transition" 
                       title="Validation Prototype au 1er essai (R_gating) — Cliquer pour le détail">
                        <span>Validation Prototype (R<sub>gating</sub>)</span>
                        <i class="fas fa-arrow-up-right-from-square text-[9px] ms-0.5 text-slate-400 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                    </a>
                </div>
                <span class="shrink-0 inline-flex items-center gap-1 px-1.5 py-0.5 rounded-md text-[10px] font-bold {{ $kpi['statut_badge'] ?? 'bg-slate-50 text-slate-700 border border-slate-200' }}" title="{{ $kpi['statut'] ?? '' }}">
                    <span class="w-1.5 h-1.5 rounded-full bg-current opacity-75 shrink-0"></span>
                    <span class="truncate max-w-[95px] sm:max-w-[120px]">{{ $kpi['statut'] ?? 'En attente' }}</span>
                </span>
            </div>

            <div class="my-2 flex items-baseline gap-1.5">
                <span class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight font-mono">{{ $kpi['taux_gating_formate'] ?? '0%' }}</span>
                <span class="text-[11px] text-slate-400 font-medium">validés au 1er essai</span>
            </div>

            <p class="text-[11px] text-slate-400 line-clamp-2 leading-relaxed mb-2" title="{{ $kpi['description'] ?? '' }}">
                {{ $kpi['description'] ?? 'Taux d\'assimilation autonome des prérequis techniques dès la première tentative.' }}
            </p>
        </div>

        <div>
            <div class="pt-2 border-t border-slate-100 flex flex-wrap items-center justify-between gap-x-2 gap-y-1 text-xs">
                <span class="text-slate-500 font-medium truncate flex items-center gap-1.5" title="Apprenants validés / total évalués">
                    <i class="fas fa-users text-[11px] text-slate-400 shrink-0"></i>
                    <span class="font-mono text-slate-700">{{ $kpi['ratio_texte'] ?? '0 / 0' }}</span>
                </span>
                <div class="shrink-0">
                    @if(($kpi['en_remediation'] ?? 0) > 0)
                        <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                            {{ $kpi['en_remediation'] }} en remédiation
                        </span>
                    @else
                        <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            Aucun retard
                        </span>
                    @endif
                </div>
            </div>

            <div class="pt-2 mt-1.5 border-t border-slate-50 flex items-center justify-between text-[11px]">
                <span class="text-[10px] text-slate-400 font-medium">Indicateur #02</span>
                <a href="{{ route('admin.PkgTableauBord.indicateurs.gating', array_merge(request()->query(), ['from' => 'formateur'])) }}" 
                   class="text-indigo-600 hover:text-indigo-800 font-bold flex items-center gap-1 transition"
                   title="Consulter l'analyse détaillée du gating">
                    <span>Analyse détaillée</span>
                    <i class="fas fa-arrow-right text-[9px] group-hover:translate-x-0.5 transition-transform"></i>
                </a>
            </div>
        </div>
    </div>

@elseif($type === 'responsable')
    <!-- KPI 2 Responsable : Taux macroscopique global de validation directe -->
    <div class="bg-white p-4 rounded-lg border border-slate-200/90 shadow-2xs hover:shadow-sm hover:border-indigo-300 transition duration-200 flex flex-col justify-between h-full relative group min-w-0">
        <div>
            <div class="flex items-start justify-between gap-1.5 min-w-0 mb-1.5">
                <div class="min-w-0 flex-1">
                    <a href="{{ route('admin.PkgTableauBord.indicateurs.gating', request()->query()) }}" 
                       class="text-[11px] sm:text-xs font-bold text-slate-700 hover:text-indigo-600 leading-tight block line-clamp-2 transition" 
                       title="Taux Macro Validation 1er Essai (R_gating) — Cliquer pour le détail">
                        <span>Validation Macro (R<sub>gating</sub>)</span>
                        <i class="fas fa-arrow-up-right-from-square text-[9px] ms-0.5 text-slate-400 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                    </a>
                </div>
                <span class="shrink-0 inline-flex items-center gap-1 px-1.5 py-0.5 rounded-md text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                    Indicateur 02
                </span>
            </div>

            <div class="my-2 flex items-baseline gap-1.5">
                <span class="text-2xl sm:text-3xl font-black text-indigo-700 tracking-tight font-mono">
                    {{ $kpi['taux_gating_global_formate'] ?? '--' }}
                </span>
                <span class="text-[11px] text-slate-400 font-medium">validation globale</span>
            </div>

            <p class="text-[11px] text-slate-400 line-clamp-2 leading-relaxed mb-2" title="Moyenne de validation directe au Live Coding Niveau 2 sur l'ensemble des groupes.">
                Moyenne de validation directe au Live Coding Niveau 2 sur l'ensemble des groupes.
            </p>
        </div>

        <div>
            <div class="pt-2 border-t border-slate-100 flex flex-wrap items-center justify-between gap-x-2 gap-y-1 text-xs">
                <span class="text-slate-500 font-medium truncate flex items-center gap-1.5" title="Total des apprenants évalués au niveau macro">
                    <i class="fas fa-graduation-cap text-[11px] text-slate-400 shrink-0"></i>
                    <span>Évalués : <strong class="text-slate-800 font-mono">{{ $kpi['total_evalues_global'] ?? 0 }}</strong></span>
                </span>
                <span class="font-bold text-slate-900 font-mono text-xs shrink-0">
                    {{ $kpi['valides_premier_essai_global'] ?? 0 }} validés
                </span>
            </div>

            <div class="pt-2 mt-1.5 border-t border-slate-50 flex items-center justify-between text-[11px]">
                <span class="text-[10px] text-slate-400 font-medium">Validation 1er essai</span>
                <a href="{{ route('admin.PkgTableauBord.indicateurs.gating', request()->query()) }}" 
                   class="text-indigo-600 hover:text-indigo-800 font-bold flex items-center gap-1 transition"
                   title="Consulter l'analyse détaillée du gating macro">
                    <span>Analyse détaillée</span>
                    <i class="fas fa-arrow-right text-[9px] group-hover:translate-x-0.5 transition-transform"></i>
                </a>
            </div>
        </div>
    </div>
@endif