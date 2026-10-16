{{-- Composant Panel : Stagiaires en Remédiation Prototype (Indicateur 02 — R_gating) --}}
@props([
    'kpi' => [],
    'type' => 'formateur'
])

@if(!empty($kpi['apprenants_en_remediation']) && count($kpi['apprenants_en_remediation']) > 0)
<section class="bg-rose-50 border border-rose-200 rounded-lg p-5 shadow-2xs space-y-3">
    <div class="flex items-center justify-between border-b border-rose-200/60 pb-3">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-rose-100 text-rose-700 flex items-center justify-center font-bold text-sm shrink-0 shadow-2xs">
                <i class="fas fa-user-clock text-rose-600"></i>
            </div>
            <div>
                <h4 class="text-xs font-bold text-rose-900 uppercase tracking-wider">Stagiaires en Remédiation (Live Coding non validé &bull; Indicateur 02)</h4>
                <p class="text-xs text-rose-700 mt-0.5">Ces apprenants n'ont pas validé au 1er passage et doivent consolider leurs prérequis.</p>
            </div>
        </div>
        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-800 border border-rose-300">
            {{ count($kpi['apprenants_en_remediation']) }} en remédiation
        </span>
    </div>

    <div class="flex flex-wrap gap-2 pt-1">
        @foreach($kpi['apprenants_en_remediation'] as $stagiaire)
        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium bg-white text-slate-800 border border-rose-200 shadow-2xs">
            <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
            <strong>{{ $stagiaire['nom_complet'] }}</strong>
            <span class="text-[11px] text-slate-400 font-mono">({{ $stagiaire['tentatives'] }} passage{{ $stagiaire['tentatives'] > 1 ? 's' : '' }})</span>
        </span>
        @endforeach
    </div>
</section>
@endif
