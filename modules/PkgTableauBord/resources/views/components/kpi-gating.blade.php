@props([
    'type' => 'apprenant',
    'kpi' => []
])

@if($type === 'apprenant')
    <!-- KPI 2 Apprenant : Validation du Prototype & Gating -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm relative group hover:border-indigo-300 transition">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold text-slate-500">Validation Prototype (Gating)</span>
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold {{ $kpi['statut_badge'] ?? 'bg-slate-50 text-slate-700 border border-slate-200' }}">
                {{ $kpi['statut'] ?? 'Non évalué' }}
            </span>
        </div>
        <div class="mt-3 flex items-baseline gap-2">
            @if(($kpi['is_debloque'] ?? false))
                <span class="text-2xl font-extrabold text-emerald-600 tracking-tight flex items-center gap-2">
                    <i class="fas fa-unlock-alt text-xl text-emerald-500"></i>
                    Projet Débloqué
                </span>
            @else
                <span class="text-2xl font-extrabold text-amber-600 tracking-tight flex items-center gap-2">
                    <i class="fas fa-lock text-xl text-amber-500"></i>
                    {{ ($kpi['tentatives'] ?? 0) > 0 ? 'En remédiation' : 'En attente d\'évaluation' }}
                </span>
            @endif
        </div>
        <p class="text-xs text-slate-500 mt-2">{{ $kpi['description'] ?? 'Validation autonome des fondamentaux lors de l\'épreuve de Live Coding Niveau 2.' }}</p>
        <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-600">
            <span>Passage(s) : <strong class="text-slate-800">{{ $kpi['tentatives'] ?? 0 }}</strong></span>
            <span class="{{ $kpi['acces_badge'] ?? 'text-slate-600' }}">{{ $kpi['acces_projet'] ?? 'Non défini' }}</span>
        </div>
    </div>

@elseif($type === 'formateur')
    <!-- KPI 2 Formateur : Taux de validation au 1er passage (R_gating) -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm relative group hover:border-indigo-300 transition">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold text-slate-500">Validation Prototype au 1er essai ($R_{\text{gating}}$)</span>
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold {{ $kpi['statut_badge'] ?? 'bg-slate-50 text-slate-700 border border-slate-200' }}">
                {{ $kpi['statut'] ?? 'En attente' }}
            </span>
        </div>
        <div class="mt-3 flex items-baseline gap-2">
            <span class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $kpi['taux_gating_formate'] ?? '0%' }}</span>
            <span class="text-xs text-slate-400">validés au 1er essai</span>
        </div>
        <p class="text-xs text-slate-500 mt-2">{{ $kpi['description'] ?? 'Taux d\'assimilation autonome des prérequis techniques dès la première tentative.' }}</p>
        <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-600">
            <span>{{ $kpi['ratio_texte'] ?? '0 / 0' }}</span>
            @if(($kpi['en_remediation'] ?? 0) > 0)
                <span class="font-bold text-amber-600">{{ $kpi['en_remediation'] }} en remédiation</span>
            @else
                <span class="font-bold text-emerald-600">Aucun retard</span>
            @endif
        </div>
    </div>

@elseif($type === 'responsable')
    <!-- KPI 2 Responsable : Taux macroscopique global de validation directe -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm relative group hover:border-indigo-300 transition">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold text-slate-500">Taux Macro Validation 1er Essai ($R_{\text{gating}}$)</span>
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                Indicateur 02
            </span>
        </div>
        <div class="mt-3 flex items-baseline gap-2">
            <span class="text-3xl font-extrabold text-indigo-700 tracking-tight">
                {{ $kpi['taux_gating_global_formate'] ?? '--' }}
            </span>
        </div>
        <p class="text-xs text-slate-500 mt-2">Moyenne de validation directe au Live Coding Niveau 2 sur l'ensemble des cohortes.</p>
        <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-600">
            <span>Total évalués : <strong class="text-slate-800">{{ $kpi['total_evalues_global'] ?? 0 }}</strong></span>
            <span class="font-bold text-slate-800">{{ $kpi['valides_premier_essai_global'] ?? 0 }} validés au 1er essai</span>
        </div>
    </div>
@endif
