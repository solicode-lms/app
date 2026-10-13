@props([
    'kpi' => []
])

@php
    $impasses = $kpi['impasses'] ?? [];
    $nbImpasses = count($impasses);
@endphp

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mt-6">
    <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl {{ $nbImpasses > 0 ? 'bg-amber-100 text-amber-600' : 'bg-emerald-100 text-emerald-600' }} flex items-center justify-center font-bold text-sm">
                @if($nbImpasses > 0)
                    ⚠️
                @else
                    ✅
                @endif
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-900">Diagnostic des Impasses Didactiques (Indicateur 08)</h3>
                <p class="text-xs text-slate-500">Micro-compétences nationales validées par moins de 50% de la cohorte</p>
            </div>
        </div>

        <div>
            @if($nbImpasses > 0)
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                    {{ $nbImpasses }} compétence{{ $nbImpasses > 1 ? 's' : '' }} en tension
                </span>
            @else
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    Cohorte homogène
                </span>
            @endif
        </div>
    </div>

    @if($nbImpasses > 0)
        <div class="p-5">
            <div class="space-y-4">
                @foreach($impasses as $imp)
                    <div class="p-4 rounded-xl border {{ ($imp['severite'] ?? '') === 'critique' ? 'border-rose-200 bg-rose-50/30' : 'border-amber-200 bg-amber-50/30' }} flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-2">
                                <span class="font-mono text-xs font-bold px-2 py-0.5 rounded {{ ($imp['severite'] ?? '') === 'critique' ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ $imp['code'] ?? 'MC' }}
                                </span>
                                <h4 class="text-sm font-bold text-slate-800">{{ $imp['titre'] }}</h4>
                            </div>
                            
                            <div class="mt-2 flex items-center gap-4 text-xs text-slate-600">
                                <span>Validé par : <strong class="text-slate-900">{{ $imp['valides'] }} / {{ $imp['inscrits'] }} apprenants</strong></span>
                                <span>Taux d'assimilation : <strong class="{{ ($imp['severite'] ?? '') === 'critique' ? 'text-rose-600' : 'text-amber-600' }} font-mono font-bold">{{ $imp['taux_formate'] }}</strong></span>
                            </div>

                            <!-- Mini jauge -->
                            <div class="mt-2 w-full max-w-md bg-slate-200/70 rounded-full h-1.5 overflow-hidden">
                                <div class="{{ ($imp['severite'] ?? '') === 'critique' ? 'bg-rose-500' : 'bg-amber-500' }} h-1.5 rounded-full" style="width: {{ $imp['taux'] }}%"></div>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 self-end md:self-center">
                            <button type="button" class="px-3 py-1.5 rounded-lg text-xs font-bold bg-indigo-600 hover:bg-indigo-700 text-white transition shadow-sm">
                                🎯 Planifier Atelier Dédié
                            </button>
                            <button type="button" class="px-3 py-1.5 rounded-lg text-xs font-medium border border-slate-300 hover:bg-slate-50 text-slate-700 transition">
                                Mutualiser Trinômes
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="p-8 text-center">
            <p class="text-sm font-semibold text-emerald-800">Excellente dynamique d'acquisition !</p>
            <p class="text-xs text-slate-500 mt-1">Toutes les micro-compétences évaluées dépassent le seuil critique de 50% de validation dans la classe.</p>
        </div>
    @endif
</div>
