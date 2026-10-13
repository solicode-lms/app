@props([
    'type' => 'apprenant',
    'kpi' => []
])

@if($type === 'apprenant')
    <!-- KPI 3 Apprenant : Qualité du premier rendu (F_révision) -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm relative group hover:border-indigo-300 transition">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold text-slate-500">Qualité du premier rendu</span>
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold {{ $kpi['statut_badge'] ?? 'bg-slate-50 text-slate-700 border border-slate-200' }}">
                {{ $kpi['statut'] ?? 'En attente' }}
            </span>
        </div>
        <div class="mt-3 flex items-baseline gap-2">
            <span class="text-2xl font-extrabold text-slate-900 tracking-tight">{{ $kpi['taux_premier_jet_formate'] ?? '100% de réussite directe' }}</span>
        </div>
        <p class="text-xs text-slate-500 mt-2">{{ $kpi['description'] ?? 'Mesure de la stabilité du code et de l\'autonomie dès le premier rendu soumis.' }}</p>
        <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-600">
            <span>Reprises : <strong class="text-slate-800">{{ $kpi['frequence_formatee'] ?? '0.0 reprise / tâche' }}</strong></span>
            <span class="font-bold text-emerald-600">{{ $kpi['taches_sans_reprise'] ?? 0 }} / {{ $kpi['total_taches'] ?? 0 }} validées sans retouche</span>
        </div>
    </div>

@elseif($type === 'formateur')
    <!-- KPI 3 Formateur : Fréquence Moyenne des Boucles de Révision (F_révision) -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm relative group hover:border-indigo-300 transition">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold text-slate-500">Fréquence des Révisions ($F_{\text{révision}}$)</span>
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold {{ $kpi['statut_badge'] ?? 'bg-slate-50 text-slate-700 border border-slate-200' }}">
                {{ $kpi['statut'] ?? 'En attente' }}
            </span>
        </div>
        <div class="mt-3 flex items-baseline gap-2">
            <span class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $kpi['frequence_formatee'] ?? '0.0 reprise / tâche' }}</span>
            <span class="text-xs text-slate-400">moyenne cohorte</span>
        </div>
        <p class="text-xs text-slate-500 mt-2">{{ $kpi['description'] ?? 'Suivi de la stabilité du code et détection des consignes pédagogiques ambiguës.' }}</p>
        <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-600">
            <span>{{ $kpi['taux_premier_jet_formate'] ?? '100% des tâches sans reprise' }}</span>
            <span class="font-bold text-slate-700">{{ $kpi['total_rejets'] ?? 0 }} rejets cumulés</span>
        </div>
    </div>

@elseif($type === 'responsable')
    <!-- KPI 3 Responsable : Fréquence macroscopique moyenne -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm relative group hover:border-indigo-300 transition">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold text-slate-500">Fréquence Macro des Révisions ($F_{\text{révision}}$)</span>
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                Indicateur 03
            </span>
        </div>
        <div class="mt-3 flex items-baseline gap-2">
            <span class="text-3xl font-extrabold text-indigo-700 tracking-tight">
                {{ $kpi['frequence_globale_formatee'] ?? '--' }}
            </span>
        </div>
        <p class="text-xs text-slate-500 mt-2">Moyenne de reprises par tâche sur l'ensemble des promotions et modules actifs.</p>
        <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-600">
            <span>Seuil Didactique Cible</span>
            <span class="font-bold text-slate-800">&le; 1.2 reprise / tâche</span>
        </div>
    </div>
@endif
