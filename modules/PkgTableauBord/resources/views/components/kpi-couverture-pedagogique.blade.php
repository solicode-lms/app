@props([
    'type' => 'formateur',
    'kpi' => []
])

@if($type === 'apprenant')
    <!-- Carte KPI Apprenant : Progression dans le Référentiel National (ICP) -->
    <div class="bg-white p-4 rounded-lg border border-slate-200/90 shadow-2xs hover:shadow-sm hover:border-emerald-300 transition duration-200 flex flex-col justify-between h-full relative group min-w-0">
        <div>
            <div class="flex items-start justify-between gap-1.5 min-w-0 mb-1.5">
                <div class="min-w-0 flex-1">
                    <span class="text-[11px] sm:text-xs font-bold text-slate-500 leading-tight block line-clamp-2" title="Progression dans le Référentiel National (ICP)">
                        Progression Référentiel (ICP)
                    </span>
                </div>
                <span class="shrink-0 inline-flex items-center gap-1 px-1.5 py-0.5 rounded-md text-[10px] font-bold {{ $kpi['badge_class'] ?? 'bg-emerald-50 text-emerald-700 border border-emerald-200' }}" title="{{ $kpi['statut'] ?? '' }}">
                    <span class="w-1.5 h-1.5 rounded-full bg-current opacity-75 shrink-0"></span>
                    <span class="truncate max-w-[95px] sm:max-w-[120px]">{{ $kpi['statut'] ?? 'Sur la trajectoire' }}</span>
                </span>
            </div>

            <div class="my-2 flex items-baseline gap-1.5">
                <span class="text-2xl sm:text-3xl font-black text-emerald-600 tracking-tight font-mono">
                    {{ $kpi['taux_formate'] ?? '85%' }}
                </span>
                <span class="text-[11px] text-slate-400 font-medium">validé</span>
            </div>

            <!-- Barre de progression -->
            <div class="w-full bg-slate-100 rounded-full h-1.5 mb-2 overflow-hidden" title="Progression : {{ $kpi['taux'] ?? 85 }}%">
                <div class="bg-emerald-500 h-1.5 rounded-full transition-all duration-500" style="width: {{ min(100, $kpi['taux'] ?? 85) }}%"></div>
            </div>

            <p class="text-[11px] text-slate-400 line-clamp-2 leading-relaxed mb-2" title="{{ $kpi['validees'] ?? 17 }} sur {{ $kpi['total'] ?? 20 }} compétences nationales acquises.">
                {{ $kpi['validees'] ?? 17 }} sur {{ $kpi['total'] ?? 20 }} compétences nationales acquises.
            </p>
        </div>

        <div class="pt-2 mt-auto border-t border-slate-100 flex flex-wrap items-center justify-between gap-x-2 gap-y-1 text-xs">
            <span class="text-slate-500 font-medium truncate flex items-center gap-1.5" title="Nombre de compétences restant à valider">
                <i class="fas fa-list-check text-[11px] text-slate-400 shrink-0"></i>
                <span>Reste à valider :</span>
            </span>
            <span class="font-bold text-slate-800 font-mono text-xs shrink-0">
                {{ $kpi['non_validees'] ?? 3 }} comp.
            </span>
        </div>
        <div class="pt-1.5 mt-1 border-t border-slate-50 flex items-center justify-between text-[11px]">
            <span class="text-[10px] text-slate-400 font-medium">Indicateur #08</span>
            <span class="text-[10px] text-emerald-600 font-bold font-mono">ICP</span>
        </div>
    </div>

@elseif($type === 'formateur')
    <!-- Carte KPI Formateur : Couverture Pédagogique (ICP Cohorte) -->
    <div class="bg-white p-4 rounded-lg border border-slate-200/90 shadow-2xs hover:shadow-sm hover:border-emerald-300 transition duration-200 flex flex-col justify-between h-full relative group min-w-0">
        <div>
            <div class="flex items-start justify-between gap-1.5 min-w-0 mb-1.5">
                <div class="min-w-0 flex-1">
                    <span class="text-[11px] sm:text-xs font-bold text-slate-500 leading-tight block line-clamp-2" title="Couverture Pédagogique (Indicateur de Couverture Pédagogique - ICP)">
                        Couverture Référentiel (ICP)
                    </span>
                </div>
                <span class="shrink-0 inline-flex items-center gap-1 px-1.5 py-0.5 rounded-md text-[10px] font-bold {{ $kpi['badge_class'] ?? 'bg-slate-100 text-slate-700' }}" title="{{ $kpi['statut'] ?? '' }}">
                    <span class="w-1.5 h-1.5 rounded-full bg-current opacity-75 shrink-0"></span>
                    <span class="truncate max-w-[95px] sm:max-w-[120px]">{{ $kpi['statut'] ?? 'Acquisition' }}</span>
                </span>
            </div>

            <div class="my-2 flex items-baseline gap-1.5">
                <span class="text-2xl sm:text-3xl font-black text-emerald-600 tracking-tight font-mono">
                    {{ $kpi['taux_icp_formate'] ?? '84%' }}
                </span>
                <span class="text-[11px] text-slate-400 font-medium">programme national</span>
            </div>

            <!-- Barre de progression cohorte -->
            <div class="w-full bg-slate-100 rounded-full h-1.5 mb-2 overflow-hidden" title="ICP cohorte : {{ $kpi['taux_icp'] ?? 84 }}%">
                <div class="bg-emerald-500 h-1.5 rounded-full transition-all duration-500" style="width: {{ min(100, $kpi['taux_icp'] ?? 84) }}%"></div>
            </div>

            <p class="text-[11px] text-slate-400 line-clamp-2 leading-relaxed mb-2" title="{{ $kpi['description'] ?? 'Progression collective sur l\'ensemble du référentiel national.' }}">
                {{ $kpi['description'] ?? 'Progression collective sur l\'ensemble du référentiel national.' }}
            </p>
        </div>

        <div class="pt-2 mt-auto border-t border-slate-100 flex flex-wrap items-center justify-between gap-x-2 gap-y-1 text-xs">
            <span class="text-slate-500 font-medium truncate flex items-center gap-1.5" title="Nombre de compétences sous le seuil critique de 50%">
                <i class="fas fa-triangle-exclamation text-[11px] text-amber-500 shrink-0"></i>
                <span>Impasses (&lt; 50%) :</span>
            </span>
            <span class="font-bold {{ ($kpi['nb_impasses'] ?? 0) > 0 ? 'text-amber-600' : 'text-emerald-700' }} font-mono text-xs shrink-0">
                {{ $kpi['nb_impasses'] ?? 0 }} comp.
            </span>
        </div>
        <div class="pt-2 mt-1.5 border-t border-slate-50 flex items-center justify-between text-[11px]">
            <span class="text-[10px] text-slate-400 font-medium">Indicateur #08</span>
            <span class="text-[10px] text-emerald-600 font-bold font-mono">ICP</span>
        </div>
    </div>

@elseif($type === 'responsable')
    <!-- Carte KPI Responsable : Taux de Couverture Macro du Référentiel National (ICP) -->
    <div class="bg-white p-4 rounded-lg border border-slate-200/90 shadow-2xs hover:shadow-sm hover:border-emerald-300 transition duration-200 flex flex-col justify-between h-full relative group min-w-0">
        <div>
            <div class="flex items-start justify-between gap-1.5 min-w-0 mb-1.5">
                <div class="min-w-0 flex-1">
                    <span class="text-[11px] sm:text-xs font-bold text-slate-500 leading-tight block line-clamp-2" title="Couverture Référentiel National Macro (ICP)">
                        Couverture Référentiel (ICP)
                    </span>
                </div>
                <span class="shrink-0 inline-flex items-center gap-1 px-1.5 py-0.5 rounded-md text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    Indicateur 08
                </span>
            </div>

            <div class="my-2 flex items-baseline gap-1.5">
                <span class="text-2xl sm:text-3xl font-black text-emerald-600 tracking-tight font-mono">
                    {{ $kpi['taux_macro_formate'] ?? '91.2%' }}
                </span>
                <span class="text-[11px] text-slate-400 font-medium">macro établissement</span>
            </div>

            <!-- Barre de progression macro -->
            <div class="w-full bg-slate-100 rounded-full h-1.5 mb-2 overflow-hidden" title="Couverture macro : {{ $kpi['taux_macro'] ?? 91.2 }}%">
                <div class="bg-emerald-500 h-1.5 rounded-full transition-all duration-500" style="width: {{ min(100, $kpi['taux_macro'] ?? 91.2) }}%"></div>
            </div>

            <p class="text-[11px] text-slate-400 line-clamp-2 leading-relaxed mb-2" title="Taux d'assimilation national cumulé sur l'ensemble des filières et promotions.">
                Taux d'assimilation national cumulé sur l'ensemble des filières et promotions.
            </p>
        </div>

        <div class="pt-2 mt-auto border-t border-slate-100 flex flex-wrap items-center justify-between gap-x-2 gap-y-1 text-xs">
            <span class="text-slate-500 font-medium truncate flex items-center gap-1.5" title="Seuil cible national de validation">
                <i class="fas fa-bullseye text-[11px] text-slate-400 shrink-0"></i>
                <span>Cible nationale</span>
            </span>
            <span class="font-bold text-slate-800 font-mono text-xs shrink-0">
                &ge; 85% ({{ ($kpi['ecart_cible'] ?? 0) >= 0 ? '+' : '' }}{{ $kpi['ecart_cible'] ?? 6.2 }} pts)
            </span>
        </div>
    </div>
@endif