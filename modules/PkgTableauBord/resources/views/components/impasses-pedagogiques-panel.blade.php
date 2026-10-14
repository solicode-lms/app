@props([
'kpi' => []
])

@php
$impasses = $kpi['impasses'] ?? [];
$nbImpasses = count($impasses);
@endphp

<div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden mt-6">
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
                <p class="text-xs text-slate-500">Compétences nationales accusant un retard critique par rapport au calendrier idéal</p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            @if($nbImpasses > 0)
            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                {{ $nbImpasses }} compétence{{ $nbImpasses > 1 ? 's' : '' }} en tension
            </span>
            @else
            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                Trajectoire conforme
            </span>
            @endif
            <a href="{{ route('admin.PkgTableauBord.indicateurs.couverture', array_merge(request()->query(), ['from' => 'formateur'])) }}"
               class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-semibold bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 transition shadow-2xs">
                <span>Matrice Complète</span>
                <i class="fas fa-arrow-right text-[10px] text-slate-400"></i>
            </a>
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
                            {{ $imp['code'] ?? 'C' }}
                        </span>
                        <h4 class="text-sm font-bold text-slate-800">{{ $imp['titre'] }}</h4>
                        @if(!empty($imp['module_code']))
                        <span class="text-[10px] font-semibold text-slate-400">({{ $imp['module_code'] }})</span>
                        @endif
                    </div>

                    <div class="mt-2 flex items-center gap-4 text-xs text-slate-600 flex-wrap">
                        <span>Progression : <strong class="text-slate-900 font-mono">{{ $imp['progression_formate'] ?? $imp['taux_formate'] }}</strong></span>
                        @if(isset($imp['progression_ideal']))
                        <span>Idéale à date : <strong class="text-slate-700 font-mono">{{ $imp['progression_ideal_formate'] ?? ($imp['progression_ideal'] . '%') }}</strong></span>
                        <span>Rythme : <strong class="{{ ($imp['severite'] ?? '') === 'critique' ? 'text-rose-600' : 'text-amber-600' }} font-mono font-bold">{{ $imp['taux_rythme_formate'] ?? ($imp['taux_rythme'] . '%') }}</strong></span>
                        @if(isset($imp['delta_formate']))
                        <span class="font-mono text-xs font-bold text-rose-600">{{ $imp['delta_formate'] }}</span>
                        @endif
                        @else
                        <span>Validé par : <strong class="text-slate-900">{{ $imp['valides'] }} / {{ $imp['inscrits'] }} apprenants</strong></span>
                        @endif
                    </div>

                    <!-- Mini jauge comparative avec repère idéal -->
                    <div class="mt-2 w-full max-w-md bg-slate-200/70 rounded-full h-2 relative overflow-hidden">
                        <div class="{{ ($imp['severite'] ?? '') === 'critique' ? 'bg-rose-500' : 'bg-amber-500' }} h-2 rounded-full" style="width: {{ $imp['progression'] ?? $imp['taux'] }}%"></div>
                        @if(isset($imp['progression_ideal']) && $imp['progression_ideal'] > 0)
                        <div class="absolute top-0 bottom-0 w-0.5 bg-slate-900 z-10" style="left: {{ min(100, $imp['progression_ideal']) }}%" title="Repère Idéal : {{ $imp['progression_ideal'] }}%"></div>
                        @endif
                    </div>
                </div>

                <div class="flex items-center gap-2 self-end md:self-center">
                    <a href="{{ route('admin.PkgTableauBord.indicateurs.couverture', array_merge(request()->query(), ['from' => 'formateur'])) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 transition">
                        <i class="fas fa-list-check"></i>
                        <span>Inspecter dans la matrice</span>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="p-8 text-center">
        <p class="text-sm font-semibold text-emerald-800">Excellente dynamique d'acquisition !</p>
        <p class="text-xs text-slate-500 mt-1">Toutes les compétences actives progressent en conformité avec le calendrier pédagogique idéal.</p>
    </div>
    @endif
</div>