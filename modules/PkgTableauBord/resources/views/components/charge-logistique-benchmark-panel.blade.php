{{-- Composant Panel : Matrice Benchmark de la Charge Administrative des Formateurs (Indicateur 04 — C_logistique) --}}
@props([
    'kpi' => [],
    'type' => 'responsable'
])

@if(!empty($kpi['benchmark']) && count($kpi['benchmark']) > 0)
<section class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
    <div class="p-5 border-b border-slate-200 bg-slate-50/70 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2">
        <div>
            <h4 class="text-sm font-bold text-slate-900">Charge Administrative par Formateur (C<sub>logistique</sub> &bull; Indicateur 04)</h4>
            <p class="text-xs text-slate-500">Volume horaire hebdomadaire effectif consacré au micro-suivi (Semaine : {{ $kpi['semaine'] ?? '--' }})</p>
        </div>
        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $kpi['badge_class'] ?? 'bg-emerald-50 text-emerald-700' }}">
            Moyenne : {{ $kpi['charge_moyenne_formatee'] ?? '0 h/sem' }}
        </span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead>
                <tr class="border-b border-slate-200 text-slate-500 font-semibold bg-slate-50/50">
                    <th class="py-3 px-5">Formateur</th>
                    <th class="py-3 px-4">Temps Total (C<sub>logistique</sub>)</th>
                    <th class="py-3 px-4">Pilotage Kanban</th>
                    <th class="py-3 px-4">File FIFO</th>
                    <th class="py-3 px-4">Bordereaux Notes</th>
                    <th class="py-3 px-5 text-right">Diagnostic</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($kpi['benchmark'] as $fItem)
                <tr class="hover:bg-slate-50/80 transition">
                    <td class="py-3 px-5 font-bold text-slate-900">
                        {{ $fItem['formateur_nom'] }}
                        <span class="text-[10px] text-slate-400 block font-normal">{{ $fItem['matricule'] }}</span>
                    </td>
                    <td class="py-3 px-4 font-mono font-bold text-slate-900">
                        {{ $fItem['total_heures_formate'] }}
                        <span class="text-[10px] text-slate-400 font-normal">({{ $fItem['total_heures'] }}h)</span>
                    </td>
                    <td class="py-3 px-4 font-mono text-slate-600">{{ $fItem['details']['pilotage_formate'] ?? '0 min' }}</td>
                    <td class="py-3 px-4 font-mono text-slate-600">{{ $fItem['details']['file_formate'] ?? '0 min' }}</td>
                    <td class="py-3 px-4 font-mono text-slate-600">{{ $fItem['details']['bordereaux_formate'] ?? '0 min' }}</td>
                    <td class="py-3 px-5 text-right">
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $fItem['badge_class'] }}">
                            {{ $fItem['badge_label'] }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>
@endif
