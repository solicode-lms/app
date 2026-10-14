@props([
'kpi' => []
])

@if(!empty($kpi['afficher_flashbox']) && !empty($kpi['apprenants_en_attente']))
<!-- Flashbox Alerte Critique : Apprenants en Rupture Didactique (+10 min) -->
<div class="bg-rose-50 border-2 border-rose-300/80 rounded-lg p-5 sm:p-6 shadow-sm space-y-4">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-rose-200/60 pb-3">
        <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-xl bg-rose-100 border border-rose-200 flex items-center justify-center shrink-0">
                <i class="fas fa-triangle-exclamation text-rose-600 text-lg"></i>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h4 class="text-sm font-bold text-rose-900">Apprenants sans livrable post-séance (+{{ $kpi['seuil_minutes'] ?? 10 }} min)</h4>
                    <span class="px-2 py-0.5 rounded-full text-[11px] font-extrabold bg-rose-600 text-white font-mono animate-pulse">
                        {{ count($kpi['apprenants_en_attente']) }} en rupture
                    </span>
                </div>
                <p class="text-xs text-rose-700 mt-0.5">
                    Délai critique d'autonomie post-Live Coding dépassé. Accompagnement didactique prioritaire requis.
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2 shrink-0">
            <button type="button"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold bg-rose-600 hover:bg-rose-700 text-white shadow-2xs transition">
                <i class="fas fa-users-rays"></i> Assigner Trinôme d'Entraide
            </button>
            <button type="button"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold bg-white border border-rose-300 hover:bg-rose-100 text-rose-800 shadow-2xs transition">
                <i class="fas fa-chalkboard-user"></i> Ouvrir Atelier de Soutien
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
        @foreach($kpi['apprenants_en_attente'] as $bloque)
        <div class="bg-white p-3.5 rounded-xl border border-rose-200 shadow-2xs flex items-center justify-between gap-3 hover:border-rose-400 transition">
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-9 h-9 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center font-bold text-slate-700 text-xs shrink-0 overflow-hidden">
                    @if(!empty($bloque['avatar']))
                    <img src="{{ $bloque['avatar'] }}" alt="{{ $bloque['nom_complet'] }}" class="w-full h-full object-cover">
                    @else
                    {{ strtoupper(substr($bloque['prenom'] ?? 'A', 0, 1) . substr($bloque['nom'] ?? 'P', 0, 1)) }}
                    @endif
                </div>
                <div class="min-w-0">
                    <div class="text-xs font-bold text-slate-900 truncate" title="{{ $bloque['nom_complet'] }}">
                        {{ $bloque['nom_complet'] }}
                    </div>
                    <div class="text-[11px] text-slate-500 truncate" title="{{ $bloque['tache_titre'] }}">
                        {{ $bloque['tache_titre'] }}
                    </div>
                </div>
            </div>

            <div class="flex flex-col items-end shrink-0">
                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-black bg-rose-100 text-rose-700 font-mono">
                    {{ $bloque['depassement_formate'] }}
                </span>
                <span class="text-[10px] text-slate-400 mt-0.5">dépassement</span>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif