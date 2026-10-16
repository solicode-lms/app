{{-- Composant Panel : Top 3 Tâches Nécessitant un Réalignement Didactique (Indicateur 03 — F_révision) --}}
@props([
    'kpi' => [],
    'type' => 'formateur'
])

@if(!empty($kpi['top_taches']))
<section class="bg-white p-5 sm:p-6 rounded-lg border border-slate-200 shadow-sm space-y-4">
    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
        <div>
            <h4 class="text-sm font-bold text-slate-900">Tâches Nécessitant un Réalignement Didactique (Indicateur 03 — F<sub>révision</sub>)</h4>
            <p class="text-xs text-slate-500">Tâches concentrant le plus grand nombre de rejets formateurs ({{ $kpi['frequence_formatee'] ?? '1.0' }})</p>
        </div>
        <span class="text-xs font-bold text-amber-700 bg-amber-50 px-2.5 py-1 rounded-lg border border-amber-200">Top 3 Révélateur</span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @foreach($kpi['top_taches'] as $tacheItem)
        <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200/80 space-y-1">
            <div class="flex items-center justify-between text-xs font-bold text-slate-900">
                <span class="truncate pr-2" title="{{ $tacheItem['titre'] }}">{{ $tacheItem['titre'] }}</span>
                <span class="text-rose-600 font-mono shrink-0">{{ $tacheItem['frequence_formatee'] }}</span>
            </div>
            <div class="text-[11px] text-slate-500">{{ $tacheItem['explication'] }}</div>
        </div>
        @endforeach
    </div>
</section>
@endif
