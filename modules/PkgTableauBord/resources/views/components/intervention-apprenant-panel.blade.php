{{-- Composant Panel : Message Bienveillant d'Accompagnement Didactique (Apprenant — Indicateur 06 — T_intervention) --}}
@props([
    'kpi' => [],
    'type' => 'apprenant'
])

@if(!empty($kpi['besoin_aide']))
<section class="bg-amber-50 border-2 border-amber-300 rounded-lg p-5 sm:p-6 shadow-sm">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="flex items-start gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-amber-100 border border-amber-200 flex items-center justify-center shrink-0">
                <i class="fas fa-handshake-angle text-amber-600 text-lg"></i>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h4 class="text-sm font-bold text-amber-900">Besoin d'un coup de pouce sur votre code ? (Indicateur 06 — T<sub>intervention</sub>)</h4>
                    <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-amber-200 text-amber-800 font-mono">
                        +{{ $kpi['minutes_depassement'] ?? 10 }} min post-séance
                    </span>
                </div>
                <p class="text-xs text-amber-800 mt-1">
                    {{ $kpi['message'] ?? 'Votre formateur est informé de votre difficulté pour vous accompagner.' }}
                </p>
            </div>
        </div>
        <div class="flex items-center gap-2 shrink-0">
            <span class="px-3 py-1.5 rounded-xl text-xs font-bold bg-white text-amber-900 border border-amber-300 shadow-2xs">
                {{ $kpi['tache_titre'] ?? 'Tutoriel' }}
            </span>
        </div>
    </div>
</section>
@elseif(!empty($kpi['message']))
<section class="bg-indigo-50 border border-indigo-200 rounded-lg p-4 shadow-sm flex items-center gap-3 text-xs text-indigo-900">
    <i class="fas fa-circle-check text-indigo-600 text-base"></i>
    <span><strong>Indicateur 06 :</strong> {{ $kpi['message'] }}</span>
</section>
@endif
