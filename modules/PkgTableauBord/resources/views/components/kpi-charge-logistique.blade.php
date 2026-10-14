@props([
'type' => 'formateur',
'kpi' => []
])

@if($type === 'formateur')
<!-- Carte KPI Formateur : Charge Administrative Hebdomadaire ($C_logistique) -->
<div class="bg-white p-5 rounded-lg border border-slate-200 shadow-sm relative group hover:border-indigo-300 transition flex flex-col justify-between">
    <div>
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold text-slate-500">Temps administratif</span>
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold {{ $kpi['badge_class'] ?? 'bg-slate-100 text-slate-700' }}">
                {{ $kpi['badge_label'] ?? 'Non évalué' }}
            </span>
        </div>

        <div class="mt-3 flex items-baseline justify-between">
            <span class="text-3xl font-extrabold text-slate-900 tracking-tight font-mono">
                {{ $kpi['total_heures_formate'] ?? '0h00' }}
                <span class="text-sm font-normal text-slate-400">/ {{ $kpi['cible_max_heures'] ?? 3 }}h</span>
            </span>
            <span class="text-xs font-bold text-slate-500 font-mono">{{ $kpi['pourcentage_cible'] ?? 0 }}%</span>
        </div>

        <!-- Jauge de progression -->
        <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden mt-2.5">
            <div class="{{ ($kpi['zone'] ?? 'optimal') === 'alerte' ? 'bg-rose-500' : (($kpi['zone'] ?? 'optimal') === 'vigilance' ? 'bg-amber-500' : 'bg-indigo-600') }} h-full rounded-full transition-all duration-500"
                style="width: {{ $kpi['pourcentage_cible'] ?? 0 }}%"></div>
        </div>

        <!-- Ventilation Didactique des Micro-Tâches -->
        <div class="mt-3.5 pt-3 border-t border-slate-100 grid grid-cols-3 gap-1 text-[11px] text-center">
            <div class="bg-slate-50 p-1.5 rounded-lg border border-slate-100">
                <span class="text-slate-400 block text-[9px] uppercase tracking-wider">Kanban</span>
                <strong class="text-slate-800 font-mono">{{ $kpi['details']['pilotage_formate'] ?? '0 min' }}</strong>
            </div>
            <div class="bg-slate-50 p-1.5 rounded-lg border border-slate-100">
                <span class="text-slate-400 block text-[9px] uppercase tracking-wider">File FIFO</span>
                <strong class="text-slate-800 font-mono">{{ $kpi['details']['file_formate'] ?? '0 min' }}</strong>
            </div>
            <div class="bg-slate-50 p-1.5 rounded-lg border border-slate-100">
                <span class="text-slate-400 block text-[9px] uppercase tracking-wider">Notes</span>
                <strong class="text-slate-800 font-mono">{{ $kpi['details']['bordereaux_formate'] ?? '0 min' }}</strong>
            </div>
        </div>
    </div>

    <div class="mt-3 pt-2 text-[10px] text-slate-400 flex items-center justify-between border-t border-slate-50">
        <span>Verrou d'inactivité : <strong>5 min</strong></span>
        <span class="text-emerald-600 font-semibold">&le; 2.5h recommandé</span>
    </div>
</div>
@elseif($type === 'responsable')
<!-- Carte KPI Responsable : Temps Administratif Moyen d'Équipe ($C_logistique) -->
<div class="bg-white p-5 rounded-lg border border-slate-200 shadow-sm relative group hover:border-indigo-300 transition flex flex-col justify-between">
    <div>
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold text-slate-500">Temps administratif moyen</span>
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                -{{ $kpi['taux_allegement_estime'] ?? 45 }}% de charge
            </span>
        </div>

        <div class="mt-3 flex items-baseline gap-2">
            <span class="text-3xl font-extrabold text-slate-900 tracking-tight font-mono">
                {{ $kpi['charge_moyenne_heures'] ?? 0 }} h<span class="text-base text-slate-400 font-normal">/sem</span>
            </span>
        </div>

        <p class="text-xs text-slate-500 mt-2">
            Moyenne hebdomadaire de micro-gestion administrative par formateur.
        </p>
    </div>

    <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-600">
        <span>Plafond fixé (&le; {{ $kpi['plafond_cible'] ?? 3 }}h)</span>
        <span class="font-bold text-slate-800">{{ $kpi['nombre_formateurs'] ?? 0 }} formateurs</span>
    </div>
</div>
@endif