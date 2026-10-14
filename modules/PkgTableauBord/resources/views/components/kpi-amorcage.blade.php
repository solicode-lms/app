@props([
    'type' => 'apprenant',
    'kpi' => []
])

@if($type === 'apprenant')
    <!-- KPI 1 Apprenant : Démarrage du sprint -->
    <div class="bg-white p-3.5 sm:p-4.5 rounded-lg border border-slate-200/90 shadow-2xs hover:shadow-sm hover:border-indigo-300 transition duration-200 flex flex-col justify-between h-full relative group min-w-0">
        <div>
            <div class="flex items-start justify-between gap-1.5 min-w-0 mb-1.5">
                <div class="min-w-0 flex-1">
                    <span class="text-[11px] sm:text-xs font-bold text-slate-500 leading-tight block line-clamp-2" title="Mon délai de démarrage">
                        Délai de démarrage
                    </span>
                </div>
                <span class="shrink-0 inline-flex items-center gap-1 px-1.5 py-0.5 rounded-md text-[10px] font-bold {{ $kpi['statut_badge'] ?? 'bg-slate-50 text-slate-700 border border-slate-200' }}" title="{{ $kpi['statut'] ?? '' }}">
                    <span class="w-1.5 h-1.5 rounded-full bg-current opacity-75 shrink-0"></span>
                    <span class="truncate max-w-[95px] sm:max-w-[120px]">{{ $kpi['statut'] ?? 'En attente' }}</span>
                </span>
            </div>

            <div class="my-2 flex items-baseline gap-1.5">
                <span class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight font-mono">{{ $kpi['delai_formate'] ?? '--' }}</span>
                <span class="text-[11px] text-slate-400 font-medium">post-lancement</span>
            </div>

            <p class="text-[11px] text-slate-400 line-clamp-2 leading-relaxed mb-2" title="{{ $kpi['description'] ?? '' }}">
                {{ $kpi['description'] ?? 'Délai entre le lancement du sprint et votre premier rendu soumis.' }}
            </p>
        </div>

        <div class="pt-2 mt-auto border-t border-slate-100 flex flex-wrap items-center justify-between gap-x-2 gap-y-1 text-xs">
            <span class="text-slate-500 font-medium truncate flex items-center gap-1.5" title="Moyenne du groupe sur ce projet">
                <i class="fas fa-users text-[10px] text-slate-400 shrink-0"></i>
                <span>Moyenne groupe : <strong class="text-slate-800 font-mono">{{ $kpi['moyenne_classe_formate'] ?? '--' }}</strong></span>
            </span>
            <span class="font-bold text-slate-700 text-xs shrink-0">
                {{ $kpi['comparatif_texte'] ?? '--' }}
            </span>
        </div>
    </div>
@elseif($type === 'responsable')
    <!-- KPI 1 Responsable : Délai moyen macroscopique d'amorçage -->
    <div class="bg-white p-3.5 sm:p-4.5 rounded-lg border border-slate-200/90 shadow-2xs hover:shadow-sm hover:border-indigo-300 transition duration-200 flex flex-col justify-between h-full relative group min-w-0">
        <div>
            <div class="flex items-start justify-between gap-1.5 min-w-0 mb-1.5">
                <div class="min-w-0 flex-1">
                    <a href="{{ route('admin.PkgTableauBord.indicateurs.amorcage', request()->query()) }}" 
                       class="text-[11px] sm:text-xs font-bold text-slate-700 hover:text-indigo-600 leading-tight block line-clamp-2 transition" 
                       title="Délai moyen d'amorçage macroscopique — Cliquer pour le détail">
                        <span>Amorçage Global (T<sub>amorçage</sub>)</span>
                        <i class="fas fa-arrow-up-right-from-square text-[9px] ms-0.5 text-slate-400 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                    </a>
                </div>
                <span class="shrink-0 inline-flex items-center gap-1 px-1.5 py-0.5 rounded-md text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                    Indicateur 01
                </span>
            </div>

            <div class="my-2 flex items-baseline gap-1.5">
                <span class="text-2xl sm:text-3xl font-black text-indigo-700 tracking-tight font-mono">
                    {{ $kpi['delai_moyen_global_formate'] ?? $kpi['temps_moyen_formate'] ?? '--' }}
                </span>
                <span class="text-[11px] text-slate-400 font-medium">délai moyen (présents)</span>
            </div>

            @if(isset($kpi['taux_amorce_pourcentage']))
                <div class="w-full bg-slate-100 rounded-full h-1.5 my-2 overflow-hidden" title="Taux d'amorçage global : {{ $kpi['taux_amorce_pourcentage'] }}%">
                    <div class="h-1.5 rounded-full transition-all duration-500 {{ ($kpi['taux_amorce_pourcentage'] ?? 0) >= 80 ? 'bg-emerald-500' : (($kpi['taux_amorce_pourcentage'] ?? 0) >= 50 ? 'bg-indigo-500' : 'bg-amber-500') }}" 
                         style="width: {{ min(100, max(0, $kpi['taux_amorce_pourcentage'] ?? 0)) }}%"></div>
                </div>
            @endif

            <p class="text-[11px] text-slate-400 line-clamp-2 leading-relaxed mb-2" title="Délai moyen calculé sur les {{ count($kpi['benchmark'] ?? []) }} sessions de formation actives récentes.">
                Délai moyen calculé sur les {{ count($kpi['benchmark'] ?? []) }} sessions de formation actives récentes (hors retards par absence).
            </p>
        </div>

        <div>
            <div class="pt-2 border-t border-slate-100 flex flex-wrap items-center justify-between gap-x-2 gap-y-1 text-xs">
                <span class="text-slate-500 font-medium truncate flex items-center gap-1.5" title="Sessions de formation analysées (affectations N2)">
                    <i class="fas fa-chalkboard-user text-[11px] text-slate-400 shrink-0"></i>
                    <span>Sessions : <strong class="text-slate-800 font-mono">{{ count($kpi['benchmark'] ?? []) }}</strong></span>
                </span>
                <div class="flex items-center gap-1.5 flex-wrap shrink-0">
                    @if(isset($kpi['taux_amorce_pourcentage']))
                        <span class="font-bold text-slate-900 font-mono text-xs">
                            {{ $kpi['taux_amorce_pourcentage'] }}% <span class="text-slate-400 font-normal">amorcées</span>
                        </span>
                    @endif
                    @if(!empty($kpi['nb_demarrage_avec_absence']) && $kpi['nb_demarrage_avec_absence'] > 0)
                        <a href="{{ route('admin.PkgTableauBord.indicateurs.amorcage', request()->query()) }}" class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold bg-purple-50 text-purple-700 border border-purple-200 hover:bg-purple-100 transition" title="{{ $kpi['nb_demarrage_avec_absence'] }} réalisation(s) démarrées après la séance de lancement (exclues de la moyenne)">
                            {{ $kpi['nb_demarrage_avec_absence'] }} avec absence
                        </a>
                    @endif
                    @if(!empty($kpi['nb_en_attente']) && $kpi['nb_en_attente'] > 0)
                        <a href="#apprenants-non-amorces" class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold bg-amber-50 text-amber-700 border border-amber-200 hover:bg-amber-100 hover:border-amber-300 transition" title="{{ $kpi['nb_en_attente'] }} réalisation(s) n'ont pas encore commencé — Cliquer pour voir la liste">
                            {{ $kpi['nb_en_attente'] }} en attente
                        </a>
                    @endif
                </div>
            </div>

            <div class="pt-2 mt-1.5 border-t border-slate-50 flex items-center justify-between text-[11px]">
                <span class="text-[10px] text-slate-400 font-medium">
                    {{ $kpi['total_apprenants'] ?? 0 }} réalisations
                </span>
                <a href="{{ route('admin.PkgTableauBord.indicateurs.amorcage', request()->query()) }}" 
                   class="text-indigo-600 hover:text-indigo-800 font-bold flex items-center gap-1 transition"
                   title="Consulter l'analyse détaillée et la dispersion temporelle">
                    <span>Analyse détaillée</span>
                    <i class="fas fa-arrow-right text-[9px] group-hover:translate-x-0.5 transition-transform"></i>
                </a>
            </div>
        </div>
    </div>
@else
    <!-- KPI 1 Formateur : Temps moyen de mise au travail -->
    <div class="bg-white p-3.5 sm:p-4.5 rounded-lg border border-slate-200/90 shadow-2xs hover:shadow-sm hover:border-indigo-300 transition duration-200 flex flex-col justify-between h-full relative group min-w-0">
        <div>
            <div class="flex items-start justify-between gap-1.5 min-w-0 mb-1.5">
                <div class="min-w-0 flex-1">
                    <a href="{{ route('admin.PkgTableauBord.indicateurs.amorcage', request()->query()) }}" 
                       class="text-[11px] sm:text-xs font-bold text-slate-700 hover:text-indigo-600 leading-tight block line-clamp-2 transition" 
                       title="Temps moyen de mise au travail — Cliquer pour le détail">
                        <span>Mise au travail</span>
                        <i class="fas fa-arrow-up-right-from-square text-[9px] ms-0.5 text-slate-400 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                    </a>
                </div>
                <span class="shrink-0 inline-flex items-center gap-1 px-1.5 py-0.5 rounded-md text-[10px] font-bold {{ $kpi['statut_badge'] ?? 'bg-slate-50 text-slate-700 border border-slate-200' }}" title="{{ $kpi['statut'] ?? '' }}">
                    <span class="w-1.5 h-1.5 rounded-full bg-current opacity-75 shrink-0"></span>
                    <span class="truncate max-w-[95px] sm:max-w-[120px]">{{ $kpi['statut'] ?? 'En attente' }}</span>
                </span>
            </div>

            <div class="my-2 flex items-baseline gap-1.5">
                <span class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight font-mono">{{ $kpi['temps_moyen_formate'] ?? '--' }}</span>
                <span class="text-[11px] text-slate-400 font-medium">moyenne (présents)</span>
            </div>

            @if(isset($kpi['taux_amorce_pourcentage']))
                <div class="w-full bg-slate-100 rounded-full h-1.5 my-2 overflow-hidden" title="Taux d'amorçage : {{ $kpi['taux_amorce_pourcentage'] }}%">
                    <div class="h-1.5 rounded-full transition-all duration-500 {{ ($kpi['taux_amorce_pourcentage'] ?? 0) >= 80 ? 'bg-emerald-500' : (($kpi['taux_amorce_pourcentage'] ?? 0) >= 50 ? 'bg-indigo-500' : 'bg-amber-500') }}" 
                         style="width: {{ min(100, max(0, $kpi['taux_amorce_pourcentage'] ?? 0)) }}%"></div>
                </div>
            @endif

            <p class="text-[11px] text-slate-400 line-clamp-2 leading-relaxed mb-2" title="{{ $kpi['description'] ?? '' }}">
                {{ $kpi['description'] ?? 'Délai moyen d\'activation de la première tâche du sprint (apprenants présents).' }}
            </p>
        </div>

        <div>
            <div class="pt-2 border-t border-slate-100 flex flex-wrap items-center justify-between gap-x-2 gap-y-1 text-xs">
                <span class="text-slate-500 font-medium truncate flex items-center gap-1.5" title="Nombre d'apprenants ayant activé leur première tâche en séance">
                    <i class="fas fa-user-check text-[11px] text-emerald-600 shrink-0"></i>
                    <span>Apprenants amorcés</span>
                </span>
                <div class="flex items-center gap-1.5 flex-wrap shrink-0">
                    <span class="font-bold text-slate-900 font-mono text-xs">
                        {{ $kpi['nb_amorces'] ?? 0 }}<span class="text-slate-400 font-normal">/{{ $kpi['total_apprenants'] ?? 0 }}</span>
                        <span class="text-slate-500 font-normal text-[11px]">présents</span>
                    </span>
                    @if(!empty($kpi['nb_demarrage_avec_absence']) && $kpi['nb_demarrage_avec_absence'] > 0)
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold bg-purple-50 text-purple-700 border border-purple-200" title="{{ $kpi['nb_demarrage_avec_absence'] }} réalisation(s) démarrées après la séance de lancement (avec absence)">
                            {{ $kpi['nb_demarrage_avec_absence'] }} avec absence
                        </span>
                    @endif
                    @if(!empty($kpi['nb_en_attente']) && $kpi['nb_en_attente'] > 0)
                        <a href="#apprenants-non-amorces" class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold bg-amber-50 text-amber-700 border border-amber-200 hover:bg-amber-100 hover:border-amber-300 transition" title="{{ $kpi['nb_en_attente'] }} réalisation(s) n'ont pas encore commencé — Cliquer pour voir la liste">
                            {{ $kpi['nb_en_attente'] }} en attente
                        </a>
                    @else
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200" title="100% des apprenants ont démarré le projet">
                            Tous au travail
                        </span>
                    @endif
                </div>
            </div>

            <div class="pt-2 mt-1.5 border-t border-slate-50 flex items-center justify-between text-[11px]">
                <span class="text-[10px] text-slate-400 font-medium">Indicateur #01</span>
                <a href="{{ route('admin.PkgTableauBord.indicateurs.amorcage', request()->query()) }}" 
                   class="text-indigo-600 hover:text-indigo-800 font-bold flex items-center gap-1 transition"
                   title="Consulter l'analyse détaillée et la dispersion temporelle">
                    <span>Analyse détaillée</span>
                    <i class="fas fa-arrow-right text-[9px] group-hover:translate-x-0.5 transition-transform"></i>
                </a>
            </div>
        </div>
    </div>
@endif