@props([
    'type' => 'formateur',
    'kpi' => []
])

@if($type === 'formateur')
    <!-- Carte KPI Formateur : Taux d'Intervention et de Remédiation ($T_intervention) -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm relative group hover:border-indigo-300 transition flex flex-col justify-between">
        <div>
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500">Prise en charge des blocages</span>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold {{ $kpi['badge_class'] ?? 'bg-slate-100 text-slate-700' }}">
                    {{ $kpi['badge_label'] ?? 'Excellence' }}
                </span>
            </div>

            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-3xl font-extrabold text-emerald-600 tracking-tight font-mono">
                    {{ $kpi['taux_intervention_formate'] ?? '100%' }}
                </span>
            </div>

            <p class="text-xs text-slate-500 mt-2">Taux de remédiation des blocages post-Live Coding (+{{ $kpi['seuil_minutes'] ?? 10 }} min).</p>
        </div>

        <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-600">
            <span>En attente de soutien</span>
            <span class="font-bold {{ ($kpi['en_attente_soutien'] ?? 0) > 0 ? 'text-rose-600' : 'text-emerald-700' }} font-mono">
                {{ $kpi['en_attente_soutien'] ?? 0 }} apprenant{{ ($kpi['en_attente_soutien'] ?? 0) > 1 ? 's' : '' }}
            </span>
        </div>
    </div>
@elseif($type === 'responsable')
    <!-- Carte KPI Responsable : Taux Moyen de Remédiation Macro ($T_intervention) -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm relative group hover:border-indigo-300 transition flex flex-col justify-between">
        <div>
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500">Taux Moyen Remédiation</span>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    Indicateur 06
                </span>
            </div>

            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-3xl font-extrabold text-emerald-600 tracking-tight font-mono">
                    {{ $kpi['taux_moyen_global_formate'] ?? '100%' }}
                </span>
            </div>

            <p class="text-xs text-slate-500 mt-2">Couverture didactique des décrochages post-séance inter-promotions.</p>
        </div>

        <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-600">
            <span>Cible réactivité</span>
            <span class="font-bold text-slate-800">&ge; 85%</span>
        </div>
    </div>
@endif
