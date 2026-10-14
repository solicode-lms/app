@props([
    'kpi' => []
])

@php
    $lignes = $kpi['lignes_apprenants'] ?? [];
    $estScelle = $kpi['est_scelle'] ?? false;
    $peutSceller = $kpi['peut_sceller'] ?? false;
@endphp

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mt-6">
    <div class="p-5 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-3 bg-slate-50/50">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl {{ $estScelle ? 'bg-emerald-100 text-emerald-600' : 'bg-indigo-100 text-indigo-600' }} flex items-center justify-center font-bold text-sm">
                @if($estScelle)
                    🔒
                @else
                    📜
                @endif
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h3 class="text-sm font-bold text-slate-900">Bordereau Officiel de Conversion Docimologique 65/35</h3>
                    @if($estScelle)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                            Certifié & Scellé
                        </span>
                    @else
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold bg-slate-200 text-slate-700">
                            {{ $kpi['statut'] ?? 'En cours' }}
                        </span>
                    @endif
                </div>
                <p class="text-xs text-slate-500">Calcul officiel : $\text{Note EFM (sur 40)} = 2 \times (0{,}65 \times \text{CC} + 0{,}35 \times N3)$ &bull; Classe : {{ $kpi['groupe_nom'] ?? 'Promotion' }}</p>
            </div>
        </div>

        <div class="flex items-center gap-2 self-end md:self-center">
            @if($estScelle)
                <button type="button" class="px-3 py-1.5 rounded-lg text-xs font-bold bg-emerald-600 hover:bg-emerald-700 text-white transition shadow-sm flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Exporter Bordereau PDF Signé
                </button>
            @elseif($peutSceller)
                <button type="button" class="px-3.5 py-1.5 rounded-lg text-xs font-bold bg-indigo-600 hover:bg-indigo-700 text-white transition shadow-sm flex items-center gap-1.5">
                    <span>🔏</span>
                    Sceller & Transmettre le Bordereau
                </button>
            @else
                <span class="text-xs font-semibold text-rose-600 bg-rose-50 px-2.5 py-1 rounded-lg border border-rose-200">
                    {{ $kpi['nb_manquants'] ?? 0 }} note(s) manquante(s) avant scellement
                </span>
            @endif
        </div>
    </div>

    @if(!empty($kpi['hash_signature']))
        <div class="px-5 py-2.5 bg-slate-900 text-slate-300 text-xs font-mono flex flex-wrap items-center justify-between gap-2 border-b border-slate-800">
            <div class="flex items-center gap-2">
                <span class="text-emerald-400 font-bold">SHA-256 :</span>
                <span class="text-slate-400 truncate max-w-xs sm:max-w-md">{{ $kpi['hash_signature'] }}</span>
            </div>
            @if(!empty($kpi['date_scellement']))
                <span class="text-slate-400">Scellé le : {{ $kpi['date_scellement'] }}</span>
            @endif
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead>
                <tr class="border-b border-slate-200 text-slate-500 font-semibold bg-slate-50/50">
                    <th class="py-2.5 px-4">Stagiaire</th>
                    <th class="py-2.5 px-4 font-mono">Note CC (sur 20)</th>
                    <th class="py-2.5 px-4 font-mono">Note Projet N3 (sur 20)</th>
                    <th class="py-2.5 px-4 text-center">Pondération 65/35</th>
                    <th class="py-2.5 px-4 font-mono font-bold text-indigo-700">Note EFM (sur 40)</th>
                    <th class="py-2.5 px-4 text-right">Statut Évaluatif</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($lignes as $ligne)
                    <tr class="hover:bg-slate-50/80 transition {{ !$ligne['est_complet'] ? 'bg-rose-50/30' : '' }}">
                        <td class="py-2.5 px-4 font-bold text-slate-900">{{ $ligne['nom_complet'] }}</td>
                        <td class="py-2.5 px-4 font-mono font-bold text-slate-700">
                            {{ $ligne['note_cc'] !== null ? $ligne['note_cc'] . ' / 20' : '--' }}
                        </td>
                        <td class="py-2.5 px-4 font-mono font-bold text-indigo-700">
                            {{ $ligne['note_n3'] !== null ? $ligne['note_n3'] . ' / 20' : '--' }}
                        </td>
                        <td class="py-2.5 px-4 text-center text-slate-500 font-mono text-[11px]">
                            @if($ligne['est_complet'])
                                0.65&times;{{ $ligne['note_cc'] }} + 0.35&times;{{ $ligne['note_n3'] }}
                            @else
                                <span class="text-slate-400 italic">En attente</span>
                            @endif
                        </td>
                        <td class="py-2.5 px-4 font-mono font-extrabold text-indigo-700 text-sm">
                            {{ $ligne['note_efm'] !== null ? $ligne['note_efm'] . ' / 40' : '--' }}
                        </td>
                        <td class="py-2.5 px-4 text-right">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $ligne['badge_class'] }}">
                                {{ $ligne['statut_badge'] }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-6 text-center text-slate-400 italic">Aucun apprenant trouvé pour ce groupe.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-4 bg-slate-50/70 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-600">
        <div class="flex items-center gap-2">
            <span>Moyennes officielles de promotion :</span>
            <span class="font-bold text-slate-800">CC = {{ $kpi['moyenne_cc_cohorte'] ?? '--' }} / 20</span>
            &bull;
            <span class="font-bold text-indigo-700">EFM = {{ $kpi['moyenne_efm_cohorte'] ?? '--' }} / 40</span>
        </div>
        <div class="flex items-center gap-2">
            <span class="text-slate-400">Total : {{ count($lignes) }} stagiaires</span>
        </div>
    </div>
</div>
