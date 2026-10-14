@props([
    'type' => 'formateur',
    'kpi' => []
])

@if($type === 'formateur')
    <!-- Carte KPI Formateur : Charge Administrative Hebdomadaire (C_logistique) -->
    <div class="bg-white p-4 rounded-lg border border-slate-200/90 shadow-2xs hover:shadow-sm hover:border-indigo-300 transition duration-200 flex flex-col justify-between h-full relative group min-w-0">
        <div>
            <div class="flex items-start justify-between gap-1.5 min-w-0 mb-1.5">
                <div class="min-w-0 flex-1">
                    <a href="{{ route('admin.PkgTableauBord.indicateurs.charge-logistique', array_merge(request()->query(), ['from' => 'formateur'])) }}" 
                       class="text-[11px] sm:text-xs font-bold text-slate-700 hover:text-indigo-600 leading-tight block line-clamp-2 transition" 
                       title="Temps administratif hebdomadaire (C_logistique) — Cliquer pour le détail">
                        <span>Temps administratif (C<sub>logistique</sub>)</span>
                        <i class="fas fa-arrow-up-right-from-square text-[9px] ms-0.5 text-slate-400 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                    </a>
                </div>
                <span class="shrink-0 inline-flex items-center gap-1 px-1.5 py-0.5 rounded-md text-[10px] font-bold {{ $kpi['badge_class'] ?? 'bg-slate-100 text-slate-700' }}" title="{{ $kpi['badge_label'] ?? '' }}">
                    <span class="w-1.5 h-1.5 rounded-full bg-current opacity-75 shrink-0"></span>
                    <span class="truncate max-w-[95px] sm:max-w-[120px]">{{ $kpi['badge_label'] ?? 'Non évalué' }}</span>
                </span>
            </div>

            <div class="my-2 flex items-baseline justify-between gap-1.5">
                <div class="flex items-baseline gap-1">
                    <span class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight font-mono">
                        {{ $kpi['total_heures_formate'] ?? '0h00' }}
                    </span>
                    <span class="text-[11px] text-slate-400 font-medium">/ {{ $kpi['cible_max_heures'] ?? 3 }}h</span>
                </div>
                <span class="text-xs font-bold text-slate-500 font-mono">{{ $kpi['pourcentage_cible'] ?? 0 }}%</span>
            </div>

            <!-- Jauge de progression -->
            <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden mb-2.5">
                <div class="{{ ($kpi['zone'] ?? 'optimal') === 'alerte' ? 'bg-rose-500' : (($kpi['zone'] ?? 'optimal') === 'vigilance' ? 'bg-amber-500' : 'bg-indigo-600') }} h-full rounded-full transition-all duration-500"
                     style="width: {{ min(100, $kpi['pourcentage_cible'] ?? 0) }}%"></div>
            </div>

            <!-- Ventilation Didactique des Micro-Tâches -->
            <div class="pt-2 border-t border-slate-100 grid grid-cols-3 gap-1 text-[11px] text-center">
                <div class="bg-slate-50 p-1.5 rounded-md border border-slate-100">
                    <span class="text-slate-400 block text-[9px] uppercase tracking-wider font-semibold">Kanban</span>
                    <strong class="text-slate-800 font-mono text-[11px]">{{ $kpi['details']['pilotage_formate'] ?? '0 min' }}</strong>
                </div>
                <div class="bg-slate-50 p-1.5 rounded-md border border-slate-100">
                    <span class="text-slate-400 block text-[9px] uppercase tracking-wider font-semibold">File FIFO</span>
                    <strong class="text-slate-800 font-mono text-[11px]">{{ $kpi['details']['file_formate'] ?? '0 min' }}</strong>
                </div>
                <div class="bg-slate-50 p-1.5 rounded-md border border-slate-100">
                    <span class="text-slate-400 block text-[9px] uppercase tracking-wider font-semibold">Notes</span>
                    <strong class="text-slate-800 font-mono text-[11px]">{{ $kpi['details']['bordereaux_formate'] ?? '0 min' }}</strong>
                </div>
            </div>
        </div>

        <div>
            <div class="pt-2 mt-auto border-t border-slate-100 flex flex-wrap items-center justify-between gap-x-2 gap-y-1 text-xs">
                <span class="text-slate-500 font-medium truncate flex items-center gap-1.5" title="Verrou d'inactivité automatique après 5 minutes">
                    <i class="fas fa-lock text-[10px] text-slate-400 shrink-0"></i>
                    <span>Verrou : <strong class="text-slate-800">5 min</strong></span>
                </span>
                <span class="text-emerald-700 font-semibold text-[11px] shrink-0">
                    &le; 2.5h cible
                </span>
            </div>
            <div class="pt-2 mt-1.5 border-t border-slate-50 flex items-center justify-between text-[11px]">
                <span class="text-[10px] text-slate-400 font-medium">Indicateur #04</span>
                <a href="{{ route('admin.PkgTableauBord.indicateurs.charge-logistique', array_merge(request()->query(), ['from' => 'formateur'])) }}" 
                   class="text-indigo-600 hover:text-indigo-800 font-bold flex items-center gap-1 transition"
                   title="Consulter l'analyse détaillée de la charge logistique">
                    <span>Analyse détaillée</span>
                    <i class="fas fa-arrow-right text-[9px] group-hover:translate-x-0.5 transition-transform"></i>
                </a>
            </div>
        </div>
    </div>

@elseif($type === 'responsable')
    <!-- Carte KPI Responsable : Temps Administratif Moyen d'Équipe (C_logistique) -->
    <div class="bg-white p-4 rounded-lg border border-slate-200/90 shadow-2xs hover:shadow-sm hover:border-indigo-300 transition duration-200 flex flex-col justify-between h-full relative group min-w-0">
        <div>
            <div class="flex items-start justify-between gap-1.5 min-w-0 mb-1.5">
                <div class="min-w-0 flex-1">
                    <a href="{{ route('admin.PkgTableauBord.indicateurs.charge-logistique', array_merge(request()->query(), ['from' => 'responsable'])) }}" 
                       class="text-[11px] sm:text-xs font-bold text-slate-700 hover:text-indigo-600 leading-tight block line-clamp-2 transition" 
                       title="Temps administratif moyen (C_logistique) — Cliquer pour le détail">
                        <span>Temps administratif moyen (C<sub>logistique</sub>)</span>
                        <i class="fas fa-arrow-up-right-from-square text-[9px] ms-0.5 text-slate-400 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                    </a>
                </div>
                <span class="shrink-0 inline-flex items-center gap-1 px-1.5 py-0.5 rounded-md text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                    Indicateur 04
                </span>
            </div>

            <div class="my-2 flex items-baseline gap-1.5">
                <span class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight font-mono">
                    {{ $kpi['charge_moyenne_heures'] ?? 0 }} h
                </span>
                <span class="text-[11px] text-slate-400 font-medium">/ sem / formateur</span>
            </div>

            <p class="text-[11px] text-slate-400 line-clamp-2 leading-relaxed mb-2" title="Moyenne hebdomadaire de micro-gestion administrative par formateur.">
                Moyenne hebdomadaire de micro-gestion administrative par formateur.
            </p>
        </div>

        <div>
            <div class="pt-2 mt-auto border-t border-slate-100 flex flex-wrap items-center justify-between gap-x-2 gap-y-1 text-xs">
                <span class="text-slate-500 font-medium truncate flex items-center gap-1.5" title="Plafond cible fixé pour l'équipe">
                    <i class="fas fa-bullseye text-[11px] text-slate-400 shrink-0"></i>
                    <span>Plafond : &le; {{ $kpi['plafond_cible'] ?? 3 }}h</span>
                </span>
                <span class="font-bold text-slate-800 font-mono text-xs shrink-0">
                    {{ $kpi['nombre_formateurs'] ?? 0 }} formateur{{ ($kpi['nombre_formateurs'] ?? 0) > 1 ? 's' : '' }}
                </span>
            </div>
            <div class="pt-2 mt-1.5 border-t border-slate-50 flex items-center justify-between text-[11px]">
                <span class="text-[10px] text-slate-400 font-medium">Indicateur #04</span>
                <a href="{{ route('admin.PkgTableauBord.indicateurs.charge-logistique', array_merge(request()->query(), ['from' => 'responsable'])) }}" 
                   class="text-indigo-600 hover:text-indigo-800 font-bold flex items-center gap-1 transition"
                   title="Consulter l'analyse macro détaillée">
                    <span>Analyse détaillée</span>
                    <i class="fas fa-arrow-right text-[9px] group-hover:translate-x-0.5 transition-transform"></i>
                </a>
            </div>
        </div>
    </div>
@endif