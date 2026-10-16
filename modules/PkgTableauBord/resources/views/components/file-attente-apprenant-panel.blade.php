{{-- Composant Panel : Statut Opérationnel Immédiat & File d'Attente (Apprenant — Indicateur 05 — T_file) --}}
@props([
    'kpi' => [],
    'type' => 'apprenant'
])

@if(!empty($kpi['est_en_file']))
<section class="bg-white rounded-lg border border-slate-200 p-5 sm:p-6 shadow-sm relative overflow-hidden">
    <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl bg-sky-50 text-sky-600 border border-sky-200 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold {{ $kpi['badge_label'] ?? 'bg-sky-50 text-sky-700 border border-sky-200' }}">
                        {{ $kpi['badge_label'] ?? 'File active' }} (Indicateur 05 — T<sub>file</sub>)
                    </span>
                </div>
                <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 mt-1">
                    Votre rang de passage en direct : <span class="text-indigo-600">{{ $kpi['rang_formate'] ?? 'Rang #1' }}</span>
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    {{ $kpi['tache_titre'] ?? 'Évaluation' }} &bull; Temps d'attente : <strong class="text-slate-800">{{ $kpi['temps_attente_formate'] ?? '0 min' }}</strong>
                    @if(!empty($kpi['heure_passage_estimee']) && ($kpi['statut'] ?? '') === 'en_attente')
                    &bull; Passage formateur estimé à <strong class="text-slate-800">{{ $kpi['heure_passage_estimee'] }}</strong> (dans ~{{ $kpi['temps_estime_restant_minutes'] }} min).
                    @endif
                </p>
            </div>
        </div>

        <div class="bg-slate-50 p-4 rounded-xl border border-slate-200/80 flex items-center gap-4 shrink-0 w-full lg:w-auto">
            <div>
                <div class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Livrable à soutenir</div>
                <div class="text-xs font-bold text-slate-900">{{ $kpi['tache_titre'] ?? 'Session de validation' }}</div>
            </div>
            <span class="px-3.5 py-2 bg-indigo-50 text-indigo-700 text-xs font-bold rounded-lg border border-indigo-200">
                Préparez votre IDE
            </span>
        </div>
    </div>

    <div class="mt-4 pt-4 border-t border-slate-100 flex items-center gap-2 text-xs text-slate-600">
        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span><strong>Conseil méthodologique :</strong> Gardez votre code source ouvert sur la branche du prototype pour fluidifier le démarrage de l'évaluation Live Coding.</span>
    </div>
</section>
@endif
