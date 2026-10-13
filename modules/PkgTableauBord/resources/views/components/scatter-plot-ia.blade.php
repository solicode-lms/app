@props([
    'kpi' => []
])

<!-- Section Scatter Plot SVG & Diagnostic d'Intégrité IA (Indicateur 07 — Pearson r) -->
<section id="scatter" class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-100 pb-3">
        <div>
            <div class="flex items-center gap-2">
                <h3 class="text-sm font-bold text-slate-900">Authenticité Évaluative & Corrélation Intégrité IA ({{ $kpi['r_global_formate'] ?? 'r = 0.78' }})</h3>
                <span class="px-2 py-0.5 rounded-full text-[11px] font-bold {{ $kpi['badge_class'] ?? 'bg-indigo-50 text-indigo-700' }}">
                    {{ $kpi['badge_label'] ?? 'Alignement Robuste' }}
                </span>
            </div>
            <p class="text-xs text-slate-500">Confrontation des notes de Prototype surveillé ($N2$) et de Projet Fil Rouge assisté par IA ($N3$).</p>
        </div>
        <span class="text-xs font-mono font-bold text-indigo-700 bg-indigo-50 px-2.5 py-1 rounded-lg border border-indigo-200 shrink-0">
            Axe 3 — Institution (H3)
        </span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-center">
        <!-- Scatter Plot SVG Interactif -->
        <div class="lg:col-span-2 relative bg-slate-50 p-4 rounded-xl border border-slate-200">
            <svg viewBox="0 0 500 280" class="w-full h-64">
                <!-- Axes X et Y -->
                <line x1="50" y1="20" x2="50" y2="240" stroke="#cbd5e1" stroke-width="1.5" />
                <line x1="50" y1="240" x2="480" y2="240" stroke="#cbd5e1" stroke-width="1.5" />
                
                <!-- Repères médians -->
                <line x1="50" y1="130" x2="480" y2="130" stroke="#f1f5f9" stroke-width="1" stroke-dasharray="2 2" />
                <line x1="265" y1="20" x2="265" y2="240" stroke="#f1f5f9" stroke-width="1" stroke-dasharray="2 2" />

                <!-- Diagonale idéale y = x (de (50, 240) à (450, 30)) -->
                <line x1="50" y1="240" x2="450" y2="30" stroke="#94a3b8" stroke-dasharray="4" stroke-width="1.5" />

                <!-- Zone d'anomalie rouge (surperformance IA suspecte, delta > 5) -->
                <polygon points="50,240 50,110 250,30 450,30" fill="#fee2e2" opacity="0.4" />

                <!-- Nuage de points des apprenants -->
                @if(!empty($kpi['points_scatter']))
                    @foreach($kpi['points_scatter'] as $pt)
                        <circle cx="{{ $pt['cx'] }}" cy="{{ $pt['cy'] }}" r="{{ $pt['r'] }}" 
                                fill="{{ $pt['fill'] }}" 
                                @if($pt['stroke'] !== 'none') stroke="{{ $pt['stroke'] }}" stroke-width="{{ $pt['stroke_width'] }}" @endif>
                            <title>{{ $pt['tooltip'] }}</title>
                        </circle>
                    @endforeach
                @else
                    <!-- Points de secours visuels conformes à la maquette -->
                    <circle cx="100" cy="205" r="5" fill="#4f46e5"><title>Karim: Direct=6/20, Projet=7.5/20 (Conforme)</title></circle>
                    <circle cx="160" cy="170" r="5" fill="#4f46e5"><title>Salma: Direct=10/20, Projet=11/20 (Conforme)</title></circle>
                    <circle cx="210" cy="140" r="5" fill="#4f46e5"><title>Mehdi: Direct=13/20, Projet=14/20 (Conforme)</title></circle>
                    <circle cx="270" cy="115" r="5" fill="#4f46e5"><title>Hamza: Direct=15/20, Projet=16/20 (Conforme)</title></circle>
                    <circle cx="330" cy="85" r="5" fill="#10b981"><title>Nour: Direct=17.5/20, Projet=18/20 (Optimal)</title></circle>
                    <circle cx="390" cy="55" r="5" fill="#10b981"><title>Omar: Direct=19/20, Projet=19.5/20 (Optimal)</title></circle>
                    <circle cx="240" cy="125" r="5" fill="#4f46e5"><title>Khadija: Direct=14/20, Projet=15/20 (Conforme)</title></circle>
                    <circle cx="310" cy="95" r="5" fill="#10b981"><title>Zineb: Direct=16.5/20, Projet=17/20 (Optimal)</title></circle>
                    <circle cx="140" cy="65" r="7" fill="#ef4444" stroke="#991b1b" stroke-width="2">
                        <title>Alerte IA #1: Yassir (Direct=8/20 vs Projet=18/20, Écart = +10.0 pts)</title>
                    </circle>
                    <circle cx="190" cy="50" r="7" fill="#ef4444" stroke="#991b1b" stroke-width="2">
                        <title>Alerte IA #2: Bilal (Direct=11/20 vs Projet=18.5/20, Écart = +7.5 pts)</title>
                    </circle>
                @endif

                <!-- Libellés des axes -->
                <text x="210" y="265" font-size="11" fill="#475569" font-weight="700">Note Prototype en direct (sur 20)</text>
                <text x="5" y="145" font-size="11" fill="#475569" font-weight="700" transform="rotate(-90 25,145)">Note Projet Libre (sur 20)</text>
                <text x="280" y="50" font-size="10" fill="#dc2626" font-weight="bold">Zone d'écart important (&Delta; &gt; +5 pts)</text>
            </svg>
        </div>

        <!-- Volet Latéral de Diagnostic -->
        <div class="bg-slate-50 p-5 rounded-xl border border-slate-200/80 space-y-4">
            <div>
                <div class="text-[11px] font-bold uppercase tracking-wider text-indigo-700">Diagnostic d'Intégrité</div>
                <h4 class="text-sm font-bold text-slate-900 mt-1">
                    {{ count($kpi['cas_suspects'] ?? []) }} Cas d'Écart Important Détecté{{ count($kpi['cas_suspects'] ?? []) > 1 ? 's' : '' }}
                </h4>
                <p class="text-xs text-slate-600 mt-2 leading-relaxed">
                    L'indice de cohérence globale <strong>{{ $kpi['r_global_formate'] ?? 'r = 0.78' }}</strong> atteste du degré d'authenticité. Les cas ci-dessous présentent un écart nécessitant un audit en soutenance :
                </p>
            </div>

            <div class="space-y-2">
                @forelse($kpi['cas_suspects'] ?? [] as $cas)
                    <div class="p-2.5 bg-rose-50 rounded-lg border border-rose-200 text-xs">
                        <div class="font-bold text-rose-900">{{ $cas['nom_complet'] }}</div>
                        <div class="text-slate-600 mt-0.5">
                            Direct : <strong>{{ $cas['note_n2'] }}</strong> &bull; Projet : <strong>{{ $cas['note_n3'] }}</strong> 
                            (Écart : <strong class="text-rose-700">{{ $cas['delta_formate'] ?? ('+' . $cas['delta'] . ' pts') }}</strong>)
                        </div>
                    </div>
                @empty
                    <div class="p-3 bg-emerald-50 rounded-lg border border-emerald-200 text-xs text-emerald-800 font-semibold flex items-center gap-2">
                        <i class="fas fa-check-circle text-emerald-600"></i>
                        <span>Aucun écart anormal détecté. Alignement docimologique exemplaire.</span>
                    </div>
                @endforelse
            </div>

            @if(!empty($kpi['cas_suspects']))
                <button type="button" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-xs transition flex items-center justify-center gap-2">
                    <i class="fas fa-user-shield"></i>
                    <span>Convoquer Jury de Vérification Individuelle</span>
                </button>
            @endif
        </div>
    </div>
</section>
