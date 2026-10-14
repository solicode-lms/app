@props([
    'type' => 'formateur',
    'kpi' => []
])

@if($type === 'apprenant')
    <!-- Carte KPI Apprenant : Couverture de Planification des UA (Indicateur 09 — TCP-UA) -->
    <div class="bg-white p-4 rounded-lg border border-slate-200/90 shadow-2xs hover:shadow-sm hover:border-indigo-300 transition duration-200 flex flex-col justify-between h-full relative group min-w-0">
        <div>
            <div class="flex items-start justify-between gap-1.5 min-w-0 mb-1.5">
                <div class="min-w-0 flex-1">
                    <span class="text-[11px] sm:text-xs font-bold text-slate-500 leading-tight block line-clamp-2" title="Couverture de Planification des UA (TCP-UA)">
                        Couverture Planification UA
                    </span>
                </div>
                <span class="shrink-0 inline-flex items-center gap-1 px-1.5 py-0.5 rounded-md text-[10px] font-bold {{ $kpi['badge_class'] ?? 'bg-indigo-50 text-indigo-700 border border-indigo-200' }}" title="{{ $kpi['statut'] ?? '' }}">
                    <span class="w-1.5 h-1.5 rounded-full bg-current opacity-75 shrink-0"></span>
                    <span class="truncate max-w-[95px] sm:max-w-[120px]">{{ $kpi['statut'] ?? 'En cours' }}</span>
                </span>
            </div>

            <div class="my-2 flex items-baseline gap-1.5">
                <span class="text-2xl sm:text-3xl font-black text-indigo-700 tracking-tight font-mono">
                    {{ $kpi['taux_formate'] ?? ($kpi['tcp_ua_formate'] ?? '45.3%') }}
                </span>
                <span class="text-[11px] text-slate-400 font-medium">TCP-UA</span>
            </div>

            <p class="text-[11px] text-slate-500 line-clamp-2 leading-relaxed mb-2" title="{{ $kpi['message'] ?? 'Unités d\'apprentissage planifiées dans les sessions de formation.' }}">
                {{ $kpi['n_couvertes'] ?? 24 }} / {{ $kpi['n_attendues'] ?? 53 }} UA planifiées dans vos sessions.
            </p>
        </div>

        <div class="pt-2 mt-auto border-t border-slate-100 flex flex-wrap items-center justify-between gap-x-2 gap-y-1 text-xs">
            <span class="text-slate-500 font-medium truncate flex items-center gap-1.5" title="Évaluations N2 sécurisées">
                <i class="fas fa-calendar-check text-[11px] text-emerald-500 shrink-0"></i>
                <span>UA sécurisées :</span>
            </span>
            <span class="font-bold text-emerald-700 font-mono text-[11px] shrink-0">
                {{ $kpi['n_couvertes'] ?? 24 }} UA
            </span>
        </div>
        <div class="pt-1.5 mt-1 border-t border-slate-50 flex items-center justify-between text-[11px]">
            <span class="text-[10px] text-slate-400 font-medium">Indicateur #09</span>
            <span class="text-[10px] text-indigo-600 font-bold font-mono">TCP-UA</span>
        </div>
    </div>

@elseif($type === 'formateur')
    <!-- Carte KPI Formateur : Taux de Couverture de Planification des UA (Indicateur 09 — TCP-UA) -->
    <a href="{{ route('admin.PkgTableauBord.indicateurs.tcp-ua', request()->query()) }}" 
       class="bg-white p-4 rounded-lg border border-slate-200/90 shadow-2xs hover:shadow-sm hover:border-indigo-400 transition duration-200 flex flex-col justify-between h-full relative group min-w-0 cursor-pointer block text-left">
        <div>
            <div class="flex items-start justify-between gap-1.5 min-w-0 mb-1.5">
                <div class="min-w-0 flex-1">
                    <span class="text-[11px] sm:text-xs font-bold text-slate-500 leading-tight block line-clamp-2 group-hover:text-indigo-600 transition" title="Taux de Couverture de Planification des UA (TCP-UA)">
                        Couverture Planification UA
                    </span>
                </div>
                <span class="shrink-0 inline-flex items-center gap-1 px-1.5 py-0.5 rounded-md text-[10px] font-bold {{ $kpi['badge_class'] ?? 'bg-slate-100 text-slate-700' }}" title="{{ $kpi['statut'] ?? '' }}">
                    <span class="w-1.5 h-1.5 rounded-full bg-current opacity-75 shrink-0"></span>
                    <span class="truncate max-w-[95px] sm:max-w-[120px]">{{ $kpi['statut'] ?? 'Planification' }}</span>
                </span>
            </div>

            <div class="my-2 flex items-baseline gap-1.5">
                <span class="text-2xl sm:text-3xl font-black text-indigo-700 tracking-tight font-mono group-hover:text-indigo-800">
                    {{ $kpi['tcp_ua_formate'] ?? ($kpi['taux_completude_formate'] ?? '45.3%') }}
                </span>
                <span class="text-[11px] text-slate-400 font-medium">TCP-UA</span>
            </div>

            <p class="text-[11px] text-slate-500 line-clamp-2 leading-relaxed mb-2" title="{{ $kpi['n_couvertes'] ?? 24 }} sur {{ $kpi['n_attendues'] ?? 53 }} UA couvertes par {{ $kpi['nb_sessions'] ?? 3 }} session(s).">
                <strong class="text-slate-700">{{ $kpi['n_couvertes'] ?? 24 }}/{{ $kpi['n_attendues'] ?? 53 }} UA</strong> couvertes ({{ $kpi['nb_sessions'] ?? 3 }} sessions).
            </p>
        </div>

        <div class="pt-2 mt-auto border-t border-slate-100 flex flex-wrap items-center justify-between gap-x-2 gap-y-1 text-xs">
            <span class="text-slate-500 font-medium truncate flex items-center gap-1.5" title="Unités d'Apprentissage non planifiées dans les sessions">
                <i class="fas fa-triangle-exclamation text-[11px] text-amber-500 shrink-0"></i>
                <span>UA non planifiées :</span>
            </span>
            <span class="font-bold text-amber-600 font-mono text-[11px] shrink-0">
                {{ $kpi['n_manquantes'] ?? 29 }} UA
            </span>
        </div>
        <div class="pt-2 mt-1.5 border-t border-slate-50 flex items-center justify-between text-[11px]">
            <span class="text-[10px] text-slate-400 font-medium flex items-center gap-1">
                <span>Indicateur #09</span>
                <i class="fas fa-arrow-right text-[8px] text-indigo-400 opacity-0 group-hover:opacity-100 transition-opacity"></i>
            </span>
            <span class="text-[10px] text-indigo-600 font-bold font-mono">Sessions UA</span>
        </div>
    </a>

@elseif($type === 'responsable')
    <!-- Carte KPI Responsable : Taux Global Macro de Couverture de Planification (Indicateur 09 — TCP-UA) -->
    <a href="{{ route('admin.PkgTableauBord.indicateurs.tcp-ua', request()->query()) }}" 
       class="bg-white p-4 rounded-lg border border-slate-200/90 shadow-2xs hover:shadow-sm hover:border-indigo-400 transition duration-200 flex flex-col justify-between h-full relative group min-w-0 cursor-pointer block text-left">
        <div>
            <div class="flex items-start justify-between gap-1.5 min-w-0 mb-1.5">
                <div class="min-w-0 flex-1">
                    <span class="text-[11px] sm:text-xs font-bold text-slate-500 leading-tight block line-clamp-2 group-hover:text-indigo-600 transition" title="Couverture Globale de Planification des UA (TCP-UA)">
                        Couverture Globale UA (TCP-UA)
                    </span>
                </div>
                <span class="shrink-0 inline-flex items-center gap-1 px-1.5 py-0.5 rounded-md text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                    Indicateur 09
                </span>
            </div>

            <div class="my-2 flex items-baseline gap-1.5">
                <span class="text-2xl sm:text-3xl font-black text-indigo-700 tracking-tight font-mono group-hover:text-indigo-800">
                    {{ $kpi['tcp_ua_macro_formate'] ?? ($kpi['taux_conformite_global'] ? $kpi['taux_conformite_global'] . '%' : '55.3%') }}
                </span>
                <span class="text-[11px] text-slate-400 font-medium">macro</span>
            </div>

            <p class="text-[11px] text-slate-500 line-clamp-2 leading-relaxed mb-2" title="{{ $kpi['total_couvertes'] ?? 42 }} UA couvertes sur {{ $kpi['total_attendues'] ?? 76 }} attendues au niveau établissement.">
                <strong class="text-slate-700">{{ $kpi['total_couvertes'] ?? 42 }} / {{ $kpi['total_attendues'] ?? 76 }} UA</strong> couvertes au niveau établissement.
            </p>
        </div>

        <div class="pt-2 mt-auto border-t border-slate-100 flex flex-wrap items-center justify-between gap-x-2 gap-y-1 text-xs">
            <span class="text-slate-500 font-medium truncate flex items-center gap-1.5" title="Sessions de formation actives déployées">
                <i class="fas fa-calendar-days text-[11px] text-slate-400 shrink-0"></i>
                <span>Sessions actives :</span>
            </span>
            <span class="font-bold text-slate-800 font-mono text-xs shrink-0">
                {{ $kpi['total_sessions'] ?? 6 }} sessions
            </span>
        </div>
        <div class="pt-2 mt-1.5 border-t border-slate-50 flex items-center justify-between text-[11px]">
            <span class="text-[10px] text-slate-400 font-medium flex items-center gap-1">
                <span>Cible &ge; 80%</span>
                <i class="fas fa-arrow-right text-[8px] text-indigo-400 opacity-0 group-hover:opacity-100 transition-opacity"></i>
            </span>
            <span class="text-[10px] text-indigo-600 font-bold font-mono">Détail TCP-UA</span>
        </div>
    </a>
@endif