@props([
'kpi' => []
])

<section id="fifo" class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden space-y-0">
    <div class="p-5 border-b border-slate-200 bg-slate-50/70 flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center font-extrabold text-sm">
                {{ $kpi['total_file'] ?? 0 }}
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-900">File de Soutenance en Direct &bull; Prototype Niveau 2</h3>
                <p class="text-xs text-slate-500">Ordre d'arrivée strict FIFO (premier arrivé, premier soutenu)</p>
            </div>
        </div>

        @if(($kpi['total_en_attente'] ?? 0) >= 4)
        <button class="px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-xs transition flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            Lancer Session Mutualisée 4x4
        </button>
        @else
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200">
            <span class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></span>
            File Live Coding
        </span>
        @endif
    </div>

    <div class="divide-y divide-slate-200">
        <!-- Rang #1 : Apprenant actuellement en Live Coding -->
        @if(!empty($kpi['en_cours']))
        @php $enCours = $kpi['en_cours']; @endphp
        <div class="p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-indigo-50/40 border-l-4 border-indigo-600">
            <div class="flex items-center gap-4">
                <span class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-extrabold bg-indigo-600 text-white shadow-xs">#1</span>
                <img class="w-10 h-10 rounded-full object-cover border border-slate-200 shadow-xs" src="{{ $enCours['apprenant_avatar'] }}" alt="{{ $enCours['apprenant_nom'] }}">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-bold text-slate-900">{{ $enCours['apprenant_nom'] }}</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-indigo-100 text-indigo-800 border border-indigo-200">
                            {{ $enCours['statut_label'] }}
                        </span>
                    </div>
                    <div class="text-xs text-slate-500 mt-0.5">
                        {{ $enCours['tache_titre'] }} &bull; Essai #{{ ($enCours['nombre_tentatives'] ?? 0) + 1 }}
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-xs font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-lg border border-emerald-200">Soutenance en cours</span>
            </div>
        </div>
        @endif

        <!-- Rangs suivants : Apprenants en file d'attente FIFO -->
        @forelse($kpi['file_active'] ?? [] as $item)
        <div class="p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-slate-50 transition">
            <div class="flex items-center gap-4">
                <span class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-extrabold bg-slate-100 text-slate-600">
                    #{{ $item['rang'] }}
                </span>
                <img class="w-10 h-10 rounded-full object-cover border border-slate-200 shadow-xs" src="{{ $item['apprenant_avatar'] }}" alt="{{ $item['apprenant_nom'] }}">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-bold text-slate-900">{{ $item['apprenant_nom'] }}</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $item['statut_badge'] }}">
                            {{ $item['statut_label'] }}
                        </span>
                    </div>
                    <div class="text-xs text-slate-500 mt-0.5">
                        {{ $item['tache_titre'] }} &bull; Essai #{{ ($item['nombre_tentatives'] ?? 0) + 1 }}
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <span class="text-xs font-mono font-bold text-slate-700">{{ $item['temps_attente_formate'] }}</span>
                <button class="px-3.5 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg shadow-xs transition">
                    Appeler {{ explode(' ', $item['apprenant_nom'])[0] }}
                </button>
            </div>
        </div>
        @empty
        @if(empty($kpi['en_cours']))
        <div class="p-8 text-center text-slate-400">
            <svg class="w-8 h-8 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-xs font-semibold text-slate-500">Aucun apprenant en attente dans la file Live Coding.</p>
            <p class="text-[11px] text-slate-400 mt-0.5">Les prototypes soumis apparaîtront ici automatiquement par ordre d'arrivée strict.</p>
        </div>
        @endif
        @endforelse
    </div>
</section>