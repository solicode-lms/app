{{-- Composant Panel : Suivi des Réalisations du Sprint & TRVS (Indicateur 07 — TRVS) --}}
@props([
    'kpi' => [],
    'type' => 'formateur'
])

@if(!empty($kpi['apprenants']))
<section class="bg-white p-5 sm:p-6 rounded-lg border border-slate-200 shadow-sm space-y-4">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-100 pb-3">
        <div>
            <div class="flex items-center gap-2">
                <h4 class="text-sm font-bold text-slate-900">Taux de Réalisation Validée du Sprint (Indicateur 07 — TRVS)</h4>
                <span class="text-xs font-mono font-bold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded border border-indigo-200">
                    {{ $kpi['trvs_formate'] ?? '80.0%' }}
                </span>
            </div>
            <p class="text-xs text-slate-500">Pourcentage de projets dont l'état final est strictement <strong>Validé (DONE)</strong> au terme du sprint.</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="text-xs font-bold {{ $kpi['badge_class'] ?? 'bg-emerald-50 text-emerald-700' }} px-2.5 py-1 rounded-lg">
                {{ $kpi['n_validees'] ?? 0 }} / {{ $kpi['n_attendues'] ?? 0 }} validés
            </span>
            <a href="{{ route('admin.PkgTableauBord.indicateurs.trvs', array_merge(request()->query(), ['from' => $type])) }}"
               class="text-xs font-bold text-indigo-600 hover:text-indigo-800 flex items-center gap-1 transition">
                <span>Voir Détail</span>
                <i class="fas fa-arrow-right text-[10px]"></i>
            </a>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead>
                <tr class="border-b border-slate-200 text-slate-500 font-semibold bg-slate-50/50">
                    <th class="py-2.5 px-4">Stagiaire</th>
                    <th class="py-2.5 px-4">Statut Projet</th>
                    <th class="py-2.5 px-4">Note Évaluée</th>
                    <th class="py-2.5 px-4 text-right">Diagnostic & Progression</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($kpi['apprenants'] as $appr)
                <tr class="hover:bg-slate-50/80 transition {{ !empty($appr['est_valide']) ? 'bg-emerald-50/20' : '' }}">
                    <td class="py-2.5 px-4 font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full {{ !empty($appr['est_valide']) ? 'bg-emerald-500' : (($appr['etat_code'] ?? '') === 'TO_APPROVE' ? 'bg-amber-500' : 'bg-slate-400') }}"></span>
                        <span>{{ $appr['nom_complet'] ?? '-' }}</span>
                    </td>
                    <td class="py-2.5 px-4">
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold {{ $appr['badge_class'] ?? 'bg-slate-100 text-slate-700' }}">
                            {{ $appr['etat_titre'] ?? ($appr['badge_label'] ?? 'En cours') }}
                        </span>
                    </td>
                    <td class="py-2.5 px-4 font-mono font-bold text-slate-700">
                        {{ !empty($appr['note']) ? $appr['note'] . ' / 20' : '--' }}
                    </td>
                    <td class="py-2.5 px-4 text-right">
                        @if(!empty($appr['est_valide']))
                            <span class="text-emerald-700 font-semibold flex items-center justify-end gap-1">
                                <i class="fas fa-circle-check text-emerald-600"></i>
                                <span>Validé (DONE)</span>
                            </span>
                        @elseif(($appr['etat_code'] ?? '') === 'TO_APPROVE')
                            <span class="text-amber-700 font-semibold flex items-center justify-end gap-1">
                                <i class="fas fa-clock text-amber-500"></i>
                                <span>En attente validation</span>
                            </span>
                        @else
                            <span class="text-slate-500 flex items-center justify-end gap-1">
                                <i class="fas fa-spinner text-slate-400"></i>
                                <span>En cours de réalisation</span>
                            </span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>
@endif
