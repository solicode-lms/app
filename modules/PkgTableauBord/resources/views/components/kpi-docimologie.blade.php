@props([
    'type' => 'formateur',
    'kpi' => []
])

@if($type === 'apprenant')
    <!-- Carte KPI Apprenant : Note CC & EFM Estimée (Règle Docimologique 65/35) -->
    <div class="bg-white p-3.5 sm:p-4.5 rounded-lg border border-slate-200/90 shadow-2xs hover:shadow-sm hover:border-indigo-300 transition duration-200 flex flex-col justify-between h-full relative group min-w-0">
        <div>
            <div class="flex items-start justify-between gap-1.5 min-w-0 mb-1.5">
                <div class="min-w-0 flex-1">
                    <span class="text-[11px] sm:text-xs font-bold text-slate-500 leading-tight block line-clamp-2" title="Moyenne Contrôle Continu (CC) & Estimation EFM">
                        Moyenne Contrôle Continu
                    </span>
                </div>
                <span class="shrink-0 inline-flex items-center gap-1 px-1.5 py-0.5 rounded-md text-[10px] font-bold {{ $kpi['badge_class'] ?? 'bg-indigo-50 text-indigo-700 border border-indigo-200' }}" title="{{ $kpi['statut'] ?? '' }}">
                    <span class="w-1.5 h-1.5 rounded-full bg-current opacity-75 shrink-0"></span>
                    <span class="truncate max-w-[95px] sm:max-w-[120px]">{{ $kpi['statut'] ?? 'En cours' }}</span>
                </span>
            </div>

            <div class="my-2 flex items-baseline gap-1.5">
                <span class="text-2xl sm:text-3xl font-black text-indigo-700 tracking-tight font-mono">
                    {{ $kpi['note_cc_formatee'] ?? '16.5 / 20' }}
                </span>
                <span class="text-[11px] text-slate-400 font-medium">CC (65%)</span>
            </div>

            <p class="text-[11px] text-slate-400 line-clamp-2 leading-relaxed mb-2" title="Note d'examen EFM estimée : {{ $kpi['note_efm_formatee'] ?? '33.4 / 40' }}">
                Note d'examen EFM estimée : <strong class="text-slate-800 font-mono">{{ $kpi['note_efm_formatee'] ?? '33.4 / 40' }}</strong>
            </p>
        </div>

        <div class="pt-2 mt-auto border-t border-slate-100 flex flex-wrap items-center justify-between gap-x-2 gap-y-1 text-xs">
            <span class="text-slate-500 font-medium truncate flex items-center gap-1.5" title="Barème officiel de pondération docimologique">
                <i class="fas fa-scale-balanced text-[11px] text-slate-400 shrink-0"></i>
                <span>Barème officiel :</span>
            </span>
            <span class="font-bold text-slate-800 font-mono text-[11px] shrink-0">
                65% N2 + 35% N3
            </span>
        </div>
    </div>

@elseif($type === 'formateur')
    <!-- Carte KPI Formateur : Complétude Docimologique & Scellement -->
    <div class="bg-white p-3.5 sm:p-4.5 rounded-lg border border-slate-200/90 shadow-2xs hover:shadow-sm hover:border-indigo-300 transition duration-200 flex flex-col justify-between h-full relative group min-w-0">
        <div>
            <div class="flex items-start justify-between gap-1.5 min-w-0 mb-1.5">
                <div class="min-w-0 flex-1">
                    <span class="text-[11px] sm:text-xs font-bold text-slate-500 leading-tight block line-clamp-2" title="Complétude Docimologique 65/35">
                        Docimologie 65/35
                    </span>
                </div>
                <span class="shrink-0 inline-flex items-center gap-1 px-1.5 py-0.5 rounded-md text-[10px] font-bold {{ $kpi['badge_class'] ?? 'bg-slate-100 text-slate-700' }}" title="{{ ($kpi['taux_completude'] ?? 0) >= 100 ? '100% Évalués' : ($kpi['nb_manquants'] ?? 0) . ' manquant(s)' }}">
                    <span class="w-1.5 h-1.5 rounded-full bg-current opacity-75 shrink-0"></span>
                    <span class="truncate max-w-[95px] sm:max-w-[120px]">{{ ($kpi['taux_completude'] ?? 0) >= 100 ? 'Complet' : ($kpi['nb_manquants'] ?? 0) . ' manquant' }}</span>
                </span>
            </div>

            <div class="my-2 flex items-baseline gap-1.5">
                <span class="text-2xl sm:text-3xl font-black text-indigo-700 tracking-tight font-mono">
                    {{ $kpi['taux_completude_formate'] ?? '100%' }}
                </span>
                <span class="text-[11px] text-slate-400 font-medium">complétude</span>
            </div>

            <p class="text-[11px] text-slate-400 line-clamp-2 leading-relaxed mb-2" title="{{ $kpi['nb_evalues'] ?? 20 }} sur {{ $kpi['effectif'] ?? 20 }} stagiaires disposent de notes complètes.">
                {{ $kpi['nb_evalues'] ?? 20 }} sur {{ $kpi['effectif'] ?? 20 }} stagiaires avec notes complètes.
            </p>
        </div>

        <div class="pt-2 mt-auto border-t border-slate-100 flex flex-wrap items-center justify-between gap-x-2 gap-y-1 text-xs">
            <span class="text-slate-500 font-medium truncate flex items-center gap-1.5" title="Moyenne groupe CC (sur 20) & EFM (sur 40)">
                <i class="fas fa-chart-simple text-[11px] text-slate-400 shrink-0"></i>
                <span>Moyenne groupe :</span>
            </span>
            <span class="font-bold text-slate-800 font-mono text-[11px] shrink-0">
                {{ $kpi['moyenne_cc_cohorte'] ?? 16.2 }}/20 &bull; {{ $kpi['moyenne_efm_cohorte'] ?? 32.8 }}/40
            </span>
        </div>
    </div>

@elseif($type === 'responsable')
    <!-- Carte KPI Responsable : Conformité Institutionnelle des Bordereaux -->
    <div class="bg-white p-3.5 sm:p-4.5 rounded-lg border border-slate-200/90 shadow-2xs hover:shadow-sm hover:border-indigo-300 transition duration-200 flex flex-col justify-between h-full relative group min-w-0">
        <div>
            <div class="flex items-start justify-between gap-1.5 min-w-0 mb-1.5">
                <div class="min-w-0 flex-1">
                    <span class="text-[11px] sm:text-xs font-bold text-slate-500 leading-tight block line-clamp-2" title="Conformité Institutionnelle des Bordereaux 65/35">
                        Conformité Bordereaux 65/35
                    </span>
                </div>
                <span class="shrink-0 inline-flex items-center gap-1 px-1.5 py-0.5 rounded-md text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                    Indicateur 09
                </span>
            </div>

            <div class="my-2 flex items-baseline gap-1.5">
                <span class="text-2xl sm:text-3xl font-black text-indigo-700 tracking-tight font-mono">
                    {{ $kpi['taux_conformite_formate'] ?? '100%' }}
                </span>
                <span class="text-[11px] text-slate-400 font-medium">&tau; conformité</span>
            </div>

            <p class="text-[11px] text-slate-400 line-clamp-2 leading-relaxed mb-2" title="Zéro divergence arithmétique sur la conversion réglementaire.">
                Zéro divergence arithmétique sur la conversion réglementaire.
            </p>
        </div>

        <div class="pt-2 mt-auto border-t border-slate-100 flex flex-wrap items-center justify-between gap-x-2 gap-y-1 text-xs">
            <span class="text-slate-500 font-medium truncate flex items-center gap-1.5" title="Bordereaux scellés et archivés">
                <i class="fas fa-stamp text-[11px] text-slate-400 shrink-0"></i>
                <span>Bordereaux scellés :</span>
            </span>
            <span class="font-bold text-slate-800 font-mono text-xs shrink-0">
                {{ $kpi['nb_bordereaux_scelles'] ?? 1 }} / {{ $kpi['nb_bordereaux_total'] ?? 2 }}
            </span>
        </div>
    </div>
@endif