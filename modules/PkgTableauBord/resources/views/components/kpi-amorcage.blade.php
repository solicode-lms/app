@props([
    'type' => 'apprenant',
    'kpi' => []
])

@if($type === 'apprenant')
    <!-- KPI 1 Apprenant : Démarrage du sprint -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm relative group hover:border-indigo-300 transition">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold text-slate-500">Mon délai de démarrage</span>
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold {{ $kpi['statut_badge'] ?? 'bg-emerald-50 text-emerald-700 border border-emerald-200' }}">
                {{ $kpi['statut'] ?? 'Dans les temps' }}
            </span>
        </div>
        <div class="mt-3 flex items-baseline gap-2">
            <span class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $kpi['delai_formate'] ?? '1h15' }}</span>
        </div>
        <p class="text-xs text-slate-500 mt-2">{{ $kpi['description'] ?? 'Délai entre le lancement du sprint et votre premier rendu soumis.' }}</p>
        <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-600">
            <span>Moyenne classe : {{ $kpi['moyenne_classe_formate'] ?? '1h45' }}</span>
            <span class="font-bold text-emerald-700">{{ $kpi['comparatif_texte'] ?? 'Vous êtes dans les temps' }}</span>
        </div>
    </div>
@else
    <!-- KPI 3 Formateur : Temps moyen de mise au travail -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm relative group hover:border-indigo-300 transition">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold text-slate-500">Temps moyen de mise au travail</span>
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold {{ $kpi['statut_badge'] ?? 'bg-emerald-50 text-emerald-700 border border-emerald-200' }}">
                {{ $kpi['statut'] ?? 'Optimal (<= 2h)' }}
            </span>
        </div>
        <div class="mt-3 flex items-baseline gap-2">
            <span class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $kpi['temps_moyen_formate'] ?? '1h45' }}</span>
        </div>
        <p class="text-xs text-slate-500 mt-2">{{ $kpi['description'] ?? 'Délai moyen d\'activation de la première tâche du sprint.' }}</p>
        <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-600">
            <span>Stagiaires amorcés</span>
            <span class="font-bold text-slate-800">{{ $kpi['ratio_texte'] ?? '17 / 20 (3 en attente)' }}</span>
        </div>
    </div>
@endif
