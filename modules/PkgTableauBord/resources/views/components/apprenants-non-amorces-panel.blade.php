@props([
    'kpi' => []
])

@php
    $apprenantsNonAmorces = $kpi['apprenants_en_retard'] ?? [];
    $nbEnAttente = $kpi['nb_en_attente'] ?? count($apprenantsNonAmorces);
@endphp

@if(!empty($apprenantsNonAmorces) && count($apprenantsNonAmorces) > 0)
<!-- Panneau Didactique : Apprenants Non Amorcés (Indicateur 01 — T_amorçage) -->
<section id="apprenants-non-amorces" class="bg-white rounded-lg border border-amber-200/90 p-5 sm:p-6 shadow-sm space-y-4">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-amber-100 pb-3">
        <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-lg bg-amber-100 border border-amber-200 text-amber-700 flex items-center justify-center shrink-0 shadow-2xs">
                <i class="fas fa-user-clock text-base"></i>
            </div>
            <div>
                <div class="flex items-center gap-2 flex-wrap">
                    <h4 class="text-sm font-bold text-slate-900">Apprenants en attente de mise au travail (non amorcés)</h4>
                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[11px] font-extrabold bg-amber-100 text-amber-800 border border-amber-200 font-mono">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                        {{ $nbEnAttente }} en attente
                    </span>
                </div>
                <p class="text-xs text-slate-500 mt-0.5">
                    Stagiaires n'ayant pas encore validé ou soumis leur première tâche post-lancement du sprint.
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2 shrink-0">
            <span class="text-xs text-slate-500 font-medium">
                Seuil didactique optimal : <strong class="text-slate-800 font-mono">&le; {{ $kpi['seuil_optimal_formate'] ?? '2h' }}</strong>
            </span>
        </div>
    </div>

    <!-- Grille des apprenants non amorcés -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3">
        @foreach($apprenantsNonAmorces as $apprenant)
        <div class="bg-slate-50 hover:bg-amber-50/40 p-3 sm:p-3.5 rounded-lg border border-slate-200/80 hover:border-amber-300 transition duration-150 flex items-center justify-between gap-3 shadow-2xs group">
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-9 h-9 rounded-full bg-amber-100/80 border border-amber-200 text-amber-800 flex items-center justify-center font-bold text-xs shrink-0 overflow-hidden shadow-2xs">
                    @if(!empty($apprenant['avatar']))
                        <img src="{{ $apprenant['avatar'] }}" alt="{{ $apprenant['nom_complet'] }}" class="w-full h-full object-cover">
                    @else
                        {{ strtoupper(substr($apprenant['prenom'] ?? $apprenant['nom_complet'] ?? 'A', 0, 1) . substr($apprenant['nom'] ?? '', 0, 1)) }}
                    @endif
                </div>
                <div class="min-w-0">
                    <div class="text-xs font-bold text-slate-900 truncate group-hover:text-amber-900 transition" title="{{ $apprenant['nom_complet'] }}">
                        {{ $apprenant['nom_complet'] }}
                    </div>
                    <div class="text-[11px] text-slate-400 font-mono truncate">
                        Matricule : {{ $apprenant['matricule'] ?? 'Non assigné' }}
                    </div>
                </div>
            </div>

            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-white text-amber-700 border border-amber-200 shrink-0 shadow-2xs">
                Non démarré
            </span>
        </div>
        @endforeach
    </div>
</section>
@endif
