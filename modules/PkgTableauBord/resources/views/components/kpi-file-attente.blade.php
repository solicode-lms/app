@props([
    'type' => 'formateur',
    'kpi' => []
])

@if($type === 'formateur')
    <!-- Carte KPI Formateur : File d'Attente Live Coding (T_file) -->
    <div class="bg-white p-3.5 sm:p-4.5 rounded-lg border border-slate-200/90 shadow-2xs hover:shadow-sm hover:border-indigo-300 transition duration-200 flex flex-col justify-between h-full relative group min-w-0">
        <div>
            <div class="flex items-start justify-between gap-1.5 min-w-0 mb-1.5">
                <div class="min-w-0 flex-1">
                    <span class="text-[11px] sm:text-xs font-bold text-slate-500 leading-tight block line-clamp-2" title="Temps d'attente moyen en file (T_file)">
                        Temps d'attente file
                    </span>
                </div>
                <span class="shrink-0 inline-flex items-center gap-1 px-1.5 py-0.5 rounded-md text-[10px] font-bold {{ $kpi['badge_class'] ?? 'bg-slate-100 text-slate-700' }}" title="{{ $kpi['badge_label'] ?? '' }}">
                    <span class="w-1.5 h-1.5 rounded-full bg-current opacity-75 shrink-0"></span>
                    <span class="truncate max-w-[95px] sm:max-w-[120px]">{{ $kpi['badge_label'] ?? 'File fluide' }}</span>
                </span>
            </div>

            <div class="my-2 flex items-baseline gap-1.5">
                <span class="text-2xl sm:text-3xl font-black text-amber-600 tracking-tight font-mono">
                    {{ $kpi['delai_moyen_formate'] ?? '--' }}
                </span>
                <span class="text-[11px] text-slate-400 font-medium">délai moyen</span>
            </div>

            <p class="text-[11px] text-slate-400 line-clamp-2 leading-relaxed mb-2" title="{{ $kpi['description'] ?? 'Délai moyen d\'attente entre soumission et passage en soutenance.' }}">
                {{ $kpi['description'] ?? 'Délai moyen d\'attente entre soumission et passage en soutenance.' }}
            </p>
        </div>

        <div class="pt-2 mt-auto border-t border-slate-100 flex flex-wrap items-center justify-between gap-x-2 gap-y-1 text-xs">
            <span class="text-slate-500 font-medium truncate flex items-center gap-1.5" title="Nombre d'apprenants en attente de passage">
                <i class="fas fa-users-line text-[11px] text-amber-500 shrink-0"></i>
                <span>En attente :</span>
            </span>
            <span class="font-bold text-amber-700 font-mono text-xs shrink-0">
                {{ $kpi['total_en_attente'] ?? 0 }} en file
            </span>
        </div>
    </div>

@elseif($type === 'responsable')
    <!-- Carte KPI Responsable : Latence Moyenne Globale File (T_file) -->
    <div class="bg-white p-3.5 sm:p-4.5 rounded-lg border border-slate-200/90 shadow-2xs hover:shadow-sm hover:border-indigo-300 transition duration-200 flex flex-col justify-between h-full relative group min-w-0">
        <div>
            <div class="flex items-start justify-between gap-1.5 min-w-0 mb-1.5">
                <div class="min-w-0 flex-1">
                    <span class="text-[11px] sm:text-xs font-bold text-slate-500 leading-tight block line-clamp-2" title="Latence Moyenne File FIFO Macro (T_file)">
                        Latence File FIFO
                    </span>
                </div>
                <span class="shrink-0 inline-flex items-center gap-1 px-1.5 py-0.5 rounded-md text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                    Indicateur 05
                </span>
            </div>

            <div class="my-2 flex items-baseline gap-1.5">
                <span class="text-2xl sm:text-3xl font-black text-amber-600 tracking-tight font-mono">
                    {{ $kpi['delai_moyen_global_formate'] ?? '--' }}
                </span>
                <span class="text-[11px] text-slate-400 font-medium">latence globale</span>
            </div>

            <p class="text-[11px] text-slate-400 line-clamp-2 leading-relaxed mb-2" title="Temps moyen d'attente avant démarrage du Live Coding inter-promotions.">
                Temps moyen d'attente avant démarrage du Live Coding inter-promotions.
            </p>
        </div>

        <div class="pt-2 mt-auto border-t border-slate-100 flex flex-wrap items-center justify-between gap-x-2 gap-y-1 text-xs">
            <span class="text-slate-500 font-medium truncate flex items-center gap-1.5" title="Seuil cible de fluidité de la file">
                <i class="fas fa-bullseye text-[11px] text-slate-400 shrink-0"></i>
                <span>Seuil cible</span>
            </span>
            <span class="font-bold text-slate-800 font-mono text-xs shrink-0">
                &le; 30 min
            </span>
        </div>
    </div>
@endif