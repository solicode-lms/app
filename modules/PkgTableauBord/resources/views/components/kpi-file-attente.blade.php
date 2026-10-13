@props([
    'type' => 'formateur',
    'kpi' => []
])

@if($type === 'formateur')
    <!-- Carte KPI Formateur : File d'Attente Live Coding ($T_file) -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm relative group hover:border-indigo-300 transition flex flex-col justify-between">
        <div>
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500">Temps d'attente moyen en file</span>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold {{ $kpi['badge_class'] ?? 'bg-slate-100 text-slate-700' }}">
                    {{ $kpi['badge_label'] ?? 'File fluide' }}
                </span>
            </div>

            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-3xl font-extrabold text-amber-600 tracking-tight font-mono">
                    {{ $kpi['delai_moyen_formate'] ?? '--' }}
                </span>
            </div>

            <p class="text-xs text-slate-500 mt-2">Délai moyen d'attente entre soumission et passage en soutenance.</p>
        </div>

        <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-600">
            <span>Apprenants en attente</span>
            <span class="font-bold text-amber-700 font-mono">{{ $kpi['total_en_attente'] ?? 0 }} en file active</span>
        </div>
    </div>
@elseif($type === 'responsable')
    <!-- Carte KPI Responsable : Latence Moyenne Globale File ($T_file) -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm relative group hover:border-indigo-300 transition flex flex-col justify-between">
        <div>
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500">Latence Moyenne File FIFO</span>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                    Indicateur 05
                </span>
            </div>

            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-3xl font-extrabold text-amber-600 tracking-tight font-mono">
                    {{ $kpi['delai_moyen_global_formate'] ?? '--' }}
                </span>
            </div>

            <p class="text-xs text-slate-500 mt-2">Temps moyen d'attente avant démarrage du Live Coding inter-promotions.</p>
        </div>

        <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-600">
            <span>Seuil cible fluidité</span>
            <span class="font-bold text-slate-800">&le; 30 min</span>
        </div>
    </div>
@endif
