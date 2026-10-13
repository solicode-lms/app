@props([
    'type' => 'formateur',
    'kpi' => []
])

@if($type === 'apprenant')
    <!-- Carte KPI Apprenant : Note CC & EFM Estimée (Règle Docimologique 65/35) -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm relative group hover:border-indigo-300 transition flex flex-col justify-between">
        <div>
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500">Moyenne Contrôle Continu (CC)</span>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold {{ $kpi['badge_class'] ?? 'bg-indigo-50 text-indigo-700 border border-indigo-200' }}">
                    {{ $kpi['statut'] ?? 'Module en cours' }}
                </span>
            </div>

            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-3xl font-extrabold text-indigo-700 tracking-tight font-mono">
                    {{ $kpi['note_cc_formatee'] ?? '16.5 / 20' }}
                </span>
                <span class="text-xs font-semibold text-slate-400">CC (65%)</span>
            </div>

            <p class="text-xs text-slate-500 mt-2">
                Note d'examen EFM estimée : <strong class="text-slate-900 font-mono">{{ $kpi['note_efm_formatee'] ?? '33.4 / 40' }}</strong>
            </p>
        </div>

        <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-600">
            <span>Barème officiel</span>
            <span class="font-bold text-slate-800 font-mono">65% Prototype + 35% Projet</span>
        </div>
    </div>

@elseif($type === 'formateur')
    <!-- Carte KPI Formateur : Complétude Docimologique & Scellement -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm relative group hover:border-indigo-300 transition flex flex-col justify-between">
        <div>
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500">Docimologie 65/35</span>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold {{ $kpi['badge_class'] ?? 'bg-slate-100 text-slate-700' }}">
                    {{ ($kpi['taux_completude'] ?? 0) >= 100 ? '100% Évalués' : ($kpi['nb_manquants'] ?? 0) . ' manquant(s)' }}
                </span>
            </div>

            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-3xl font-extrabold text-indigo-700 tracking-tight font-mono">
                    {{ $kpi['taux_completude_formate'] ?? '100%' }}
                </span>
                <span class="text-xs text-slate-400">notes renseignées</span>
            </div>

            <p class="text-xs text-slate-500 mt-2">
                {{ $kpi['nb_evalues'] ?? 20 }} sur {{ $kpi['effectif'] ?? 20 }} stagiaires disposent de notes complètes.
            </p>
        </div>

        <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-600">
            <span>Moyenne cohorte CC / EFM</span>
            <span class="font-bold text-slate-800 font-mono">{{ $kpi['moyenne_cc_cohorte'] ?? 16.2 }}/20 &bull; {{ $kpi['moyenne_efm_cohorte'] ?? 32.8 }}/40</span>
        </div>
    </div>

@elseif($type === 'responsable')
    <!-- Carte KPI Responsable : Conformité Institutionnelle des Bordereaux -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm relative group hover:border-indigo-300 transition flex flex-col justify-between">
        <div>
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500">Conformité Bordereaux 65/35</span>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                    Indicateur 09
                </span>
            </div>

            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-3xl font-extrabold text-indigo-700 tracking-tight font-mono">
                    {{ $kpi['taux_conformite_formate'] ?? '100%' }}
                </span>
                <span class="text-xs text-slate-400">&tau; conformité</span>
            </div>

            <p class="text-xs text-slate-500 mt-2">
                Zéro divergence arithmétique sur la conversion réglementaire.
            </p>
        </div>

        <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-600">
            <span>Bordereaux scellés</span>
            <span class="font-bold text-slate-800 font-mono">{{ $kpi['nb_bordereaux_scelles'] ?? 1 }} / {{ $kpi['nb_bordereaux_total'] ?? 2 }} validés</span>
        </div>
    </div>
@endif
