@props([
    'kpi' => []
])

@php
    $sessions = $kpi['sessions'] ?? [];
    $uasManquantes = $kpi['uas_manquantes'] ?? [];
    $tcpUa = $kpi['tcp_ua'] ?? ($kpi['taux_completude'] ?? 45.3);
    $nCouvertes = $kpi['n_couvertes'] ?? 24;
    $nAttendues = $kpi['n_attendues'] ?? 53;
    $nManquantes = $kpi['n_manquantes'] ?? 29;
@endphp

<div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden mt-6">
    <div class="p-5 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-3 bg-slate-50/50">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-sm">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h3 class="text-sm font-bold text-slate-900">Planification des Unités d'Apprentissage (Indicateur 09 — TCP-UA)</h3>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold {{ $kpi['badge_class'] ?? 'bg-indigo-50 text-indigo-700 border border-indigo-200' }}">
                        {{ $kpi['tcp_ua_formate'] ?? "{$tcpUa}%" }}
                    </span>
                </div>
                <p class="text-xs text-slate-500">
                    <strong class="text-slate-700">{{ $nCouvertes }} / {{ $nAttendues }} UA couvertes</strong> par les sessions de formation &bull; Classe : {{ $kpi['groupe_code'] ?? 'Promotion' }}
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2 self-end md:self-center">
            <a href="{{ route('admin.PkgTableauBord.indicateurs.tcp-ua', request()->query()) }}" 
               class="px-3.5 py-1.5 rounded-lg text-xs font-bold bg-indigo-600 hover:bg-indigo-700 text-white transition shadow-sm flex items-center gap-1.5">
                <i class="fas fa-table-list text-xs"></i>
                <span>Matrice Complète des UA (TCP-UA)</span>
            </a>
        </div>
    </div>

    <!-- Synthèse des Sessions Déployées et UAs Mobilisées -->
    <div class="p-5 grid grid-cols-1 md:grid-cols-3 gap-4 border-b border-slate-100">
        @forelse(array_slice($sessions, 0, 3) as $s)
        <div class="p-3 bg-slate-50 rounded-lg border border-slate-200/80">
            <div class="flex items-center justify-between gap-2 mb-1">
                <span class="text-[11px] font-mono font-bold text-indigo-700 bg-white px-2 py-0.5 rounded border border-slate-200">
                    {{ $s['code'] }}
                </span>
                <span class="text-xs font-mono font-bold text-emerald-700">
                    {{ $s['nb_uas'] }} UA
                </span>
            </div>
            <h4 class="text-xs font-bold text-slate-800 truncate" title="{{ $s['titre'] }}">{{ $s['titre'] }}</h4>
            <div class="text-[11px] text-slate-400 mt-1 flex items-center gap-1">
                <i class="fas fa-calendar text-[10px]"></i>
                <span>{{ $s['date_debut'] }} &rarr; {{ $s['date_fin'] }}</span>
            </div>
        </div>
        @empty
        <div class="col-span-3 text-center text-xs text-slate-400 py-2">
            Aucune session de formation active enregistrée.
        </div>
        @endforelse
    </div>

    <!-- Aperçu des UA en Attente de Planification -->
    @if(!empty($uasManquantes))
    <div class="p-5 bg-white">
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-2">
                <i class="fas fa-triangle-exclamation text-amber-500 text-xs"></i>
                <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">
                    Unités d'Apprentissage sans Session Planifiée ({{ count($uasManquantes) }} UA)
                </h4>
            </div>
            <a href="{{ route('admin.PkgTableauBord.indicateurs.tcp-ua', request()->query()) }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 flex items-center gap-1">
                <span>Voir toutes les {{ count($uasManquantes) }} manquantes</span>
                <i class="fas fa-arrow-right text-[10px]"></i>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500 font-semibold bg-slate-50/50">
                        <th class="py-2 px-3">Code UA</th>
                        <th class="py-2 px-3">Intitulé</th>
                        <th class="py-2 px-3">Module</th>
                        <th class="py-2 px-3">Compétence</th>
                        <th class="py-2 px-3 text-right">Statut</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach(array_slice($uasManquantes, 0, 4) as $mu)
                    <tr class="hover:bg-amber-50/30 transition">
                        <td class="py-2 px-3 font-mono font-bold text-amber-800">{{ $mu['code'] }}</td>
                        <td class="py-2 px-3 font-medium text-slate-800 truncate max-w-[250px]" title="{{ $mu['titre'] }}">{{ $mu['titre'] }}</td>
                        <td class="py-2 px-3 text-slate-600">{{ $mu['module_code'] }}</td>
                        <td class="py-2 px-3 text-indigo-700 font-mono">{{ $mu['competence_code'] }}</td>
                        <td class="py-2 px-3 text-right">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                Non planifiée
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <div class="p-3 bg-slate-50 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-slate-500">
        <div class="flex items-center gap-2">
            <span>Règle d'anticipation :</span>
            <span class="text-slate-700">Toute UA non planifiée risque de bloquer la note CC correspondante en fin d'année.</span>
        </div>
        <div class="flex items-center gap-2">
            <span class="text-slate-400">Objectif SoliLMS : &ge; 80%</span>
        </div>
    </div>
</div>