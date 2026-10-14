@extends('layouts.admin-v2')

@section('title', 'Détail Indicateur 05 — File d\'Attente Live Coding (T_file)')
@section('page_title', 'Indicateur 05 : Délai Moyen de Traitement de la File d\'Attente (T_file)')
@section('page_subtitle', 'Régulation du flux FIFO de soutenance Live Coding (Prototype N2), console d\'arbitrage solo vs mutualisation 4x4 et prévention des temps morts')

@section('actions')
<div class="flex items-center gap-2">
    <a href="{{ route($dashboardRoute ?? (auth()->user()?->hasAnyRole([\Modules\PkgAutorisation\Models\Role::ADMIN_ROLE, 'root', 'responsable']) ? 'admin.PkgTableauBord.responsable' : 'admin.PkgTableauBord.formateur'), request()->except('from')) }}" 
       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition shadow-2xs">
        <i class="fas fa-arrow-left text-slate-400"></i>
        <span>Retour Tableau de Bord</span>
    </a>
    <button onclick="window.location.reload()" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition shadow-2xs">
        <i class="fas fa-rotate text-slate-400"></i>
        <span>Actualiser File</span>
    </button>
</div>
@endsection

@section('filters')
@include('PkgTableauBord::components.responsable-filter-bar', [
    'formateurs' => $formateurs ?? collect(),
    'selectedFormateurId' => $selectedFormateurId ?? null,
    'filieres' => $filieres ?? collect(),
    'selectedFiliereId' => $selectedFiliereId ?? null,
    'groupes' => $groupes ?? collect(),
    'selectedGroupeId' => $selectedGroupeId ?? null,
    'projets' => $projets ?? collect(),
    'selectedProjetId' => $selectedProjetId ?? null,
    'filterType' => $filterType ?? 'sessions'
])
@endsection

@section('content')
<div class="space-y-6" x-data="{
    searchQuery: '',
    selectedIds: [],
    evaluationModal: false,
    evalTacheId: null,
    evalApprenantNom: '',
    evalTitre: '',
    evalDecision: 'APPROVED',
    evalNote: 16,
    evalRemarques: '',
    
    toggleSelect(id) {
        if (this.selectedIds.includes(id)) {
            this.selectedIds = this.selectedIds.filter(x => x !== id);
        } else {
            if (this.selectedIds.length < 4) {
                this.selectedIds.push(id);
            } else {
                alert('La mutualisation est limitée à 4 apprenants maximum simultanément.');
            }
        }
    },
    selectTopFour(ids) {
        this.selectedIds = ids.slice(0, 4);
    },
    clearSelection() {
        this.selectedIds = [];
    },
    openEvaluation(tache, decision = 'APPROVED') {
        this.evalTacheId = tache.id;
        this.evalApprenantNom = tache.apprenant_nom;
        this.evalTitre = tache.tache_titre;
        this.evalDecision = decision;
        this.evalNote = decision === 'APPROVED' ? 16 : 10;
        this.evalRemarques = '';
        this.evaluationModal = true;
    },
    closeEvaluation() {
        this.evaluationModal = false;
        this.evalTacheId = null;
    }
}">

    {{-- Messages Flash --}}
    @if(session('success'))
        <div class="p-4 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fas fa-circle-check text-emerald-600 text-sm"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">&times;</button>
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 rounded-lg bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fas fa-triangle-exclamation text-rose-600 text-sm"></i>
                <span>{{ session('error') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700">&times;</button>
        </div>
    @endif

    <!-- En-tête contextuel & Fiche Didactique de l'Indicateur -->
    <section class="bg-white rounded-lg border border-slate-200 p-5 sm:p-6 shadow-xs space-y-4">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div class="space-y-1.5">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200 flex items-center gap-1.5">
                        <i class="fas fa-stopwatch text-amber-500"></i>
                        <span>Indicateur Clé #05</span>
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                        Cible Fluidité : &le; {{ $detail['seuil_fluide'] }} min
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                        Vigilance : &gt; {{ $detail['seuil_fluide'] }} min
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                        Saturation : &gt; {{ $detail['seuil_vigilance'] }} min ou &ge; {{ $detail['seuil_saturation_count'] }} en attente
                    </span>
                    @if(!empty($projetActif))
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-100 text-indigo-800 border border-indigo-200 truncate max-w-[280px]">
                            Session : {{ $projetActif->titre }}
                        </span>
                    @elseif(!empty($isVisionGlobale))
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200 flex items-center gap-1">
                            <i class="fas fa-globe text-[11px] text-emerald-600"></i>
                            <span>Vision Globale ({{ count($detail['benchmark']) }} sessions)</span>
                        </span>
                    @endif
                </div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                    Délai Moyen de la File d'Attente Live Coding (T<sub>file</sub>)
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 max-w-4xl leading-relaxed">
                    Mesure continue de la latence entre la soumission du prototype Niveau 2 (<code class="text-amber-700 font-mono text-[11px]">READY_FOR_LIVE_CODING</code>) et le démarrage de la soutenance synchrone (<code class="text-indigo-700 font-mono text-[11px]">IN_LIVE_CODING</code>). Chaque apprenant passe <strong>entre 40 min et 60 min</strong> en Live Coding. En cas de goulet d'étranglement, la console permet d'activer une <strong>session mutualisée 4x4 via Discord</strong> : 4 apprenants partagent leur écran en simultané pendant 40 à 60 min ; le formateur supervise un écran mosaïque regroupant les 4 flux en direct et peut focaliser son attention sur un code spécifique ou réguler les 4 en parallèle.
                </p>
            </div>

            <!-- Synthèse Globale -->
            <div class="shrink-0 flex items-center gap-4 bg-slate-50 p-3.5 rounded-lg border border-slate-200">
                <div class="text-center px-1">
                    <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">Délai Moyen</span>
                    <span class="text-2xl sm:text-3xl font-black {{ ($detail['zone'] ?? 'optimal') === 'alerte' ? 'text-rose-700' : (($detail['zone'] ?? 'optimal') === 'vigilance' ? 'text-amber-600' : 'text-emerald-700') }} font-mono">
                        {{ $detail['delai_moyen_formate'] }}
                    </span>
                    <span class="text-[10px] text-slate-500 block font-medium">attente constatée</span>
                </div>
                <div class="h-10 w-px bg-slate-200"></div>
                <div class="text-center px-1">
                    <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">File Active</span>
                    <span class="text-2xl sm:text-3xl font-black text-slate-900 font-mono">
                        {{ $detail['total_en_attente'] }}
                    </span>
                    <span class="text-[10px] text-slate-500 block font-medium">+{{ $detail['total_en_cours'] }} en cours</span>
                </div>
            </div>
        </div>
    </section>

    <!-- 4 Cartes Métriques Clés -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

        {{-- 1. Latence File (T_file) --}}
        <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-2xs hover:shadow-sm transition">
            <div class="flex items-start justify-between gap-2 mb-2">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Délai d'Attente (T<sub>file</sub>)</span>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $detail['statut_badge'] }}">
                    {{ $detail['statut'] }}
                </span>
            </div>
            <div class="flex items-baseline gap-2 mb-1">
                <span class="text-2xl sm:text-3xl font-black text-slate-900 font-mono">
                    {{ $detail['delai_moyen_formate'] }}
                </span>
                <span class="text-xs text-slate-400 font-medium">/ &le; {{ $detail['seuil_fluide'] }} min cible</span>
            </div>
            <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">
                Temps d'attente moyen observé avant la prise en charge de l'évaluation synchrone.
            </p>
            <div class="mt-3 pt-2 border-t border-slate-100 flex items-center justify-between text-[11px]">
                <span class="text-slate-400 font-medium">Zone didactique</span>
                <span class="font-bold {{ ($detail['zone'] ?? 'optimal') === 'optimal' ? 'text-emerald-700' : (($detail['zone'] ?? 'optimal') === 'vigilance' ? 'text-amber-700' : 'text-rose-700') }}">
                    {{ ucfirst($detail['zone'] ?? 'optimal') }}
                </span>
            </div>
        </div>

        {{-- 2. File Active Instantanée --}}
        <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-2xs hover:shadow-sm transition">
            <div class="flex items-start justify-between gap-2 mb-2">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">File FIFO Instantanée</span>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $detail['total_en_attente'] >= 5 ? 'bg-rose-100 text-rose-800' : ($detail['total_en_attente'] >= 3 ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800') }}">
                    {{ $detail['total_en_attente'] >= 5 ? 'Saturation' : ($detail['total_en_attente'] >= 3 ? 'Charge active' : 'Fluide') }}
                </span>
            </div>
            <div class="flex items-baseline gap-2 mb-1">
                <span class="text-2xl sm:text-3xl font-black text-slate-900 font-mono">
                    {{ $detail['total_en_attente'] }}
                </span>
                <span class="text-xs text-slate-400 font-medium">apprenant{{ $detail['total_en_attente'] > 1 ? 's' : '' }} en file</span>
            </div>
            <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">
                Apprenants ayant validé leur livrable de tutoriel et attendant leur soutenance live.
            </p>
            <div class="mt-3 pt-2 border-t border-slate-100 flex items-center justify-between text-[11px]">
                <span class="text-slate-400 font-medium">Total engagé</span>
                <span class="font-bold text-slate-700 font-mono">{{ $detail['total_file'] }} apprenants</span>
            </div>
        </div>

        {{-- 3. Soutenance en Cours --}}
        <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-2xs hover:shadow-sm transition">
            <div class="flex items-start justify-between gap-2 mb-2">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Live Coding en Cours</span>
                @if($detail['en_cours'])
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-100 text-indigo-800 border border-indigo-200 animate-pulse flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-600"></span>
                        <span>Actif</span>
                    </span>
                @else
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600">Libre</span>
                @endif
            </div>
            <div class="flex items-baseline gap-2 mb-1">
                @if($detail['en_cours'])
                    <span class="text-xl sm:text-2xl font-black text-indigo-700 truncate max-w-[200px]" title="{{ $detail['en_cours']['apprenant_nom'] }}">
                        {{ $detail['en_cours']['apprenant_nom'] }}
                    </span>
                @else
                    <span class="text-2xl sm:text-3xl font-black text-slate-400 font-mono">--</span>
                @endif
            </div>
            <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">
                @if($detail['en_cours'])
                    En soutenance depuis <strong class="text-indigo-800 font-mono">{{ $detail['en_cours']['duree_en_cours_formate'] }}</strong> (démarré à {{ $detail['en_cours']['date_debut'] }}).
                @else
                    Aucun apprenant en cours d'évaluation. La file est prête pour un appel.
                @endif
            </p>
            <div class="mt-3 pt-2 border-t border-slate-100 flex items-center justify-between text-[11px]">
                <span class="text-slate-400 font-medium">Session Live</span>
                <span class="font-bold text-indigo-700 font-mono">
                    {{ !empty($detail['en_cours']['session_mutualisee_id']) ? 'Mutualisée 4x4' : (!empty($detail['en_cours']) ? 'Solo 1-à-1' : 'Disponible') }}
                </span>
            </div>
        </div>

        {{-- 4. Débit & Projection --}}
        <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-2xs hover:shadow-sm transition">
            <div class="flex items-start justify-between gap-2 mb-2">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Projection & Débit</span>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-sky-100 text-sky-800">
                    {{ $detail['cadence_recommandee'] }}
                </span>
            </div>
            <div class="flex items-baseline gap-2 mb-1">
                <span class="text-2xl sm:text-3xl font-black text-slate-900 font-mono">
                    {{ $detail['temps_total_estime_solo_formate'] }}
                </span>
                <span class="text-xs text-slate-400 font-medium">pour clore la file</span>
            </div>
            <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">
                Fin estimée à <strong class="text-slate-800 font-mono">{{ $detail['heure_fin_estimee_solo'] }}</strong> en solo, ou <strong class="text-indigo-700 font-mono">{{ $detail['temps_total_estime_groupe_formate'] }}</strong> en session mutualisée 4x4 Discord.
            </p>
            <div class="mt-3 pt-2 border-t border-slate-100 flex items-center justify-between text-[11px]">
                <span class="text-slate-400 font-medium">Cadence unitaire</span>
                <span class="font-bold text-slate-700 font-mono">40-60 min solo / ~45 min vague Discord</span>
            </div>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- CONSOLE FIFO OPÉRATIONNELLE LIVE CODING (Composant Principal) -->
    <!-- ========================================================================= -->
    <div class="bg-white rounded-lg border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-4 sm:p-5 border-b border-slate-200 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-slate-50/50">
            <div>
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <h2 class="text-base sm:text-lg font-black text-slate-900">
                        Console Opérationnelle FIFO — Live Coding
                    </h2>
                </div>
                <p class="text-xs text-slate-500 mt-0.5">
                    Régulation synchrone des passages pour la session : <strong>{{ $detail['projet_titre'] }}</strong> ({{ $detail['groupe_nom'] }})
                </p>
            </div>

            <div class="flex items-center gap-2 flex-wrap">
                {{-- Barre de recherche interactive --}}
                <div class="relative">
                    <input type="text" x-model="searchQuery" placeholder="Rechercher un apprenant..." 
                           class="w-48 sm:w-64 pl-8 pr-3 py-1.5 text-xs rounded-lg border border-slate-300 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 bg-white shadow-2xs">
                    <i class="fas fa-search text-slate-400 absolute left-2.5 top-2.5 text-[11px]"></i>
                </div>

                {{-- Action mutualisée 4x4 Discord --}}
                <form method="POST" action="{{ route('admin.PkgTableauBord.indicateurs.file-attente.session-mutualisee') }}" class="inline">
                    @csrf
                    <input type="hidden" name="tache_ids" :value="selectedIds.join(',')">
                    <button type="submit" :disabled="selectedIds.length < 2"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold transition shadow-2xs"
                            :class="selectedIds.length >= 2 ? 'bg-indigo-600 hover:bg-indigo-700 text-white cursor-pointer' : 'bg-slate-100 text-slate-400 cursor-not-allowed'"
                            title="Lancer une session Discord 4x4 avec partage d'écran mosaïque (4 apprenants simultanés, 40 à 60 min)">
                        <i class="fab fa-discord text-[12px]"></i>
                        <span>Session Discord 4x4 (<span x-text="selectedIds.length"></span>/4)</span>
                    </button>
                </form>

                @if(!empty($detail['file_active']))
                    <button type="button" @click="selectTopFour([{{ implode(',', array_column($detail['file_active'], 'id')) }}])"
                            class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-100 transition shadow-2xs"
                            title="Sélectionner automatiquement les 4 premiers pour mutualisation rapide">
                        <i class="fas fa-list-check text-slate-400"></i>
                        <span>Top 4</span>
                    </button>
                @endif
            </div>
        </div>

        {{-- SOUTENANCE EN COURS (SI ACTIVE) --}}
        @if($detail['en_cours'])
            <div class="p-4 sm:p-5 bg-gradient-to-r from-indigo-50/80 via-white to-indigo-50/40 border-b border-indigo-100">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex items-center gap-3.5">
                        <img src="{{ $detail['en_cours']['apprenant_avatar'] }}" alt="Avatar" class="w-12 h-12 rounded-full border-2 border-indigo-300 shadow-xs shrink-0">
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wider bg-indigo-600 text-white shadow-2xs">
                                    En cours de soutenance
                                </span>
                                <span class="text-xs font-bold text-indigo-900 font-mono">
                                    Écoulé : {{ $detail['en_cours']['duree_en_cours_formate'] }}
                                </span>
                                @if($detail['en_cours']['session_mutualisee_id'])
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                        Session Mutualisée #{{ $detail['en_cours']['session_mutualisee_id'] }}
                                    </span>
                                @endif
                            </div>
                            <h3 class="text-base font-black text-slate-900 mt-1">
                                {{ $detail['en_cours']['apprenant_nom'] }}
                            </h3>
                            <p class="text-xs text-slate-500">
                                Prototype : <strong class="text-slate-700">{{ $detail['en_cours']['tache_titre'] }}</strong>
                                @if($detail['en_cours']['nombre_tentatives'] > 0)
                                    &bull; <span class="text-amber-700 font-medium">{{ $detail['en_cours']['nombre_tentatives'] }} révision(s) passée(s)</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    {{-- Boutons d'évaluation directe --}}
                    <div class="flex items-center gap-2 shrink-0">
                        <button type="button" @click="openEvaluation({{ json_encode($detail['en_cours']) }}, 'APPROVED')"
                                class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-2xs transition">
                            <i class="fas fa-check-circle"></i>
                            <span>Valider Prototype</span>
                        </button>
                        <button type="button" @click="openEvaluation({{ json_encode($detail['en_cours']) }}, 'REVISION_NECESSAIRE')"
                                class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold shadow-2xs transition">
                            <i class="fas fa-arrows-rotate"></i>
                            <span>Demander Révision</span>
                        </button>
                    </div>
                </div>
            </div>
        @endif

        {{-- LISTE FIFO DES APPRENANTS EN ATTENTE --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-3 w-10 text-center">
                            <i class="fas fa-square-check text-slate-400"></i>
                        </th>
                        <th class="py-3 px-3 w-16 text-center">Rang</th>
                        <th class="py-3 px-4">Apprenant</th>
                        <th class="py-3 px-4">Prototype / Tâche N2</th>
                        <th class="py-3 px-3 text-center">Temps d'attente</th>
                        <th class="py-3 px-3 text-center">Passage estimé</th>
                        <th class="py-3 px-3 text-center">Tentatives</th>
                        <th class="py-3 px-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($detail['file_active'] as $f)
                        <tr class="hover:bg-slate-50/80 transition"
                            x-show="!searchQuery || '{{ strtolower($f['apprenant_nom']) }}'.includes(searchQuery.toLowerCase())">
                            
                            {{-- Checkbox mutualisation --}}
                            <td class="py-3 px-3 text-center">
                                <input type="checkbox" :checked="selectedIds.includes({{ $f['id'] }})"
                                       @change="toggleSelect({{ $f['id'] }})"
                                       class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer">
                            </td>

                            {{-- Rang FIFO --}}
                            <td class="py-3 px-3 text-center">
                                <span class="inline-flex items-center justify-center w-7 h-7 rounded-full text-xs font-black font-mono {{ $f['rang'] === 1 ? 'bg-amber-100 text-amber-900 border border-amber-300 shadow-2xs' : 'bg-slate-100 text-slate-700' }}">
                                    #{{ $f['rang'] }}
                                </span>
                            </td>

                            {{-- Apprenant --}}
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-2.5">
                                    <img src="{{ $f['apprenant_avatar'] }}" alt="Avatar" class="w-8 h-8 rounded-full border border-slate-200 shrink-0">
                                    <div>
                                        <div class="font-bold text-slate-900">{{ $f['apprenant_nom'] }}</div>
                                        <div class="text-[10px] text-slate-400">Apprenant</div>
                                    </div>
                                </div>
                            </td>

                            {{-- Prototype --}}
                            <td class="py-3 px-4">
                                <span class="font-medium text-slate-800 block line-clamp-1" title="{{ $f['tache_titre'] }}">
                                    {{ $f['tache_titre'] }}
                                </span>
                                <span class="text-[10px] text-slate-400 font-mono">Tâche #{{ $f['tache_id'] }}</span>
                            </td>

                            {{-- Temps d'attente --}}
                            <td class="py-3 px-3 text-center">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-bold font-mono {{ $f['statut_badge'] }}">
                                    <i class="fas fa-clock text-[9px]"></i>
                                    <span>{{ $f['temps_attente_formate'] }}</span>
                                </span>
                            </td>

                            {{-- Passage estimé --}}
                            <td class="py-3 px-3 text-center">
                                <div class="font-bold text-slate-800 font-mono text-xs">{{ $f['heure_passage_estimee'] }}</div>
                                <div class="text-[10px] text-slate-400">dans {{ $f['temps_estime_formate'] }}</div>
                            </td>

                            {{-- Nombre de tentatives --}}
                            <td class="py-3 px-3 text-center">
                                @if($f['nombre_tentatives'] > 0)
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                        {{ $f['nombre_tentatives'] }} révision{{ $f['nombre_tentatives'] > 1 ? 's' : '' }}
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-medium bg-slate-100 text-slate-600">
                                        1<sup>er</sup> jet
                                    </span>
                                @endif
                            </td>

                            {{-- Action individuelle : Appeler en solo --}}
                            <td class="py-3 px-4 text-right">
                                <form method="POST" action="{{ route('admin.PkgTableauBord.indicateurs.file-attente.appeler', $f['id']) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold transition shadow-2xs cursor-pointer">
                                        <i class="fas fa-headset text-[10px]"></i>
                                        <span>Appeler en Solo</span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-slate-400 text-xs">
                                <i class="fas fa-circle-check text-emerald-500 text-2xl mb-2 block"></i>
                                <span>File d'attente vide pour cette session ! Tous les prototypes soumis ont été évalués.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- HISTORIQUE RÉCENT DES SOUTENANCES TERMINÉES -->
    <!-- ========================================================================= -->
    <div class="bg-white rounded-lg border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-4 sm:p-5 border-b border-slate-200 flex items-center justify-between gap-4">
            <div>
                <h3 class="text-sm sm:text-base font-bold text-slate-900 flex items-center gap-2">
                    <i class="fas fa-history text-slate-400"></i>
                    <span>Dernières Soutenances Traitées dans la Session</span>
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">
                    Historique des évaluations conduites avec relevé de la latence de file constatée
                </p>
            </div>
            <span class="text-xs font-bold text-slate-500 font-mono">
                {{ count($detail['soutenances_recentes']) }} soutenance{{ count($detail['soutenances_recentes']) > 1 ? 's' : '' }}
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="py-2.5 px-4">Date & Heure</th>
                        <th class="py-2.5 px-4">Apprenant</th>
                        <th class="py-2.5 px-4">Prototype</th>
                        <th class="py-2.5 px-3 text-center">Latence Constatée</th>
                        <th class="py-2.5 px-3 text-center">Résultat</th>
                        <th class="py-2.5 px-3 text-center">Note</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($detail['soutenances_recentes'] as $s)
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="py-2.5 px-4 font-mono text-[11px] text-slate-500">
                                {{ $s['date_passage'] }}
                            </td>
                            <td class="py-2.5 px-4 font-bold text-slate-800">
                                <div class="flex items-center gap-2">
                                    <img src="{{ $s['apprenant_avatar'] }}" alt="Avatar" class="w-6 h-6 rounded-full shrink-0">
                                    <span>{{ $s['apprenant_nom'] }}</span>
                                </div>
                            </td>
                            <td class="py-2.5 px-4 text-slate-600 truncate max-w-[220px]" title="{{ $s['tache_titre'] }}">
                                {{ $s['tache_titre'] }}
                            </td>
                            <td class="py-2.5 px-3 text-center font-mono font-bold text-slate-700">
                                {{ $s['delai_formate'] }}
                            </td>
                            <td class="py-2.5 px-3 text-center">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $s['decision_badge'] }}">
                                    {{ $s['decision'] }}
                                </span>
                            </td>
                            <td class="py-2.5 px-3 text-center font-mono font-bold text-slate-800">
                                {{ $s['note'] !== null ? number_format($s['note'], 1) . ' / 20' : '--' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-slate-400 text-xs">
                                Aucune soutenance récente enregistrée pour cette session.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- BENCHMARK MACROSCOPIQUE MULTI-SESSIONS (Gouvernance Responsable) -->
    <!-- ========================================================================= -->
    <div class="bg-white rounded-lg border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-4 sm:p-5 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h3 class="text-sm sm:text-base font-bold text-slate-900 flex items-center gap-2">
                    <i class="fas fa-network-wired text-slate-400"></i>
                    <span>Benchmark Comparatif des Files Live Coding par Session</span>
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">
                    Surveillance multi-sessions pour équilibrer la cadence formateur et prévenir les surcharges
                </p>
            </div>
            <div class="flex items-center gap-2 text-xs">
                <span class="px-2 py-1 rounded bg-slate-100 text-slate-700 font-bold font-mono">
                    {{ count($detail['benchmark']) }} session{{ count($detail['benchmark']) > 1 ? 's' : '' }} analysée{{ count($detail['benchmark']) > 1 ? 's' : '' }}
                </span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="py-2.5 px-4">Session / Projet</th>
                        <th class="py-2.5 px-3">Groupe</th>
                        <th class="py-2.5 px-3">Formateur</th>
                        <th class="py-2.5 px-3 text-center">En attente</th>
                        <th class="py-2.5 px-3 text-center">En cours</th>
                        <th class="py-2.5 px-3 text-center">Délai Moyen (T<sub>file</sub>)</th>
                        <th class="py-2.5 px-3 text-center">Diagnostic</th>
                        <th class="py-2.5 px-4 text-right">Sélectionner</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($detail['benchmark'] as $b)
                        <tr class="hover:bg-slate-50 transition {{ $b['is_active'] ? 'bg-indigo-50/40 font-semibold' : '' }}">
                            <td class="py-2.5 px-4">
                                <div class="font-bold text-slate-800 line-clamp-1" title="{{ $b['projet_titre'] }}">
                                    {{ $b['projet_titre'] }}
                                </div>
                                <div class="text-[10px] text-slate-400">Affectation #{{ $b['affectation_id'] }}</div>
                            </td>
                            <td class="py-2.5 px-3 font-medium text-slate-700">
                                {{ $b['groupe_nom'] ?: $b['groupe_code'] }}
                            </td>
                            <td class="py-2.5 px-3 text-slate-600">
                                {{ $b['formateur_nom'] }}
                            </td>
                            <td class="py-2.5 px-3 text-center font-bold font-mono text-slate-800">
                                {{ $b['total_en_attente'] }}
                            </td>
                            <td class="py-2.5 px-3 text-center font-bold font-mono text-indigo-700">
                                {{ $b['en_cours_nom'] ? '1 en cours' : '--' }}
                            </td>
                            <td class="py-2.5 px-3 text-center font-black font-mono {{ ($b['zone'] ?? 'optimal') === 'alerte' ? 'text-rose-700' : (($b['zone'] ?? 'optimal') === 'vigilance' ? 'text-amber-600' : 'text-emerald-700') }}">
                                {{ $b['delai_moyen_formate'] }}
                            </td>
                            <td class="py-2.5 px-3 text-center">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $b['badge_class'] }}">
                                    {{ $b['badge_label'] }}
                                </span>
                            </td>
                            <td class="py-2.5 px-4 text-right">
                                <a href="{{ route('admin.PkgTableauBord.indicateurs.file-attente', array_merge(request()->query(), ['projet_id' => $b['projet_id']])) }}" 
                                   class="inline-flex items-center gap-1 px-2.5 py-1 rounded border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-white hover:text-indigo-600 transition shadow-2xs">
                                    <span>Piloter</span>
                                    <i class="fas fa-arrow-right text-[10px]"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-6 text-center text-slate-400 text-xs">
                                Aucun benchmark disponible pour le périmètre sélectionné.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- MODALE D'ÉVALUATION & CLÔTURE DE SOUTENANCE LIVE CODING -->
    <!-- ========================================================================= -->
    <div x-show="evaluationModal" style="display: none;" 
         class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        
        <div class="bg-white rounded-xl border border-slate-200 shadow-2xl max-w-lg w-full p-5 sm:p-6 space-y-4"
             @click.away="closeEvaluation()">
            
            <div class="flex items-start justify-between gap-2 border-b border-slate-100 pb-3">
                <div>
                    <h3 class="text-base font-black text-slate-900 flex items-center gap-2">
                        <i class="fas fa-clipboard-check text-indigo-600"></i>
                        <span>Évaluation Soutenance Live Coding</span>
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Apprenant : <strong class="text-slate-800" x-text="evalApprenantNom"></strong>
                    </p>
                </div>
                <button type="button" @click="closeEvaluation()" class="text-slate-400 hover:text-slate-600 text-lg">&times;</button>
            </div>

            <form method="POST" :action="'{{ url('admin/PkgTableauBord/indicateurs/file-attente') }}/' + evalTacheId + '/terminer'" class="space-y-4">
                @csrf
                
                {{-- Choix Décision --}}
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700">Décision Didactique</label>
                    <div class="grid grid-cols-2 gap-2">
                        <label class="flex items-center gap-2 p-2.5 rounded-lg border cursor-pointer transition text-xs font-bold"
                               :class="evalDecision === 'APPROVED' ? 'bg-emerald-50 border-emerald-300 text-emerald-800' : 'bg-slate-50 border-slate-200 text-slate-600'">
                            <input type="radio" name="decision" value="APPROVED" x-model="evalDecision" class="sr-only">
                            <i class="fas fa-check-circle text-emerald-600"></i>
                            <span>Valider Prototype</span>
                        </label>
                        <label class="flex items-center gap-2 p-2.5 rounded-lg border cursor-pointer transition text-xs font-bold"
                               :class="evalDecision === 'REVISION_NECESSAIRE' ? 'bg-rose-50 border-rose-300 text-rose-800' : 'bg-slate-50 border-slate-200 text-slate-600'">
                            <input type="radio" name="decision" value="REVISION_NECESSAIRE" x-model="evalDecision" class="sr-only">
                            <i class="fas fa-arrows-rotate text-rose-600"></i>
                            <span>Révision Nécessaire</span>
                        </label>
                    </div>
                </div>

                {{-- Note Prototype (si validé) --}}
                <div class="space-y-1.5" x-show="evalDecision === 'APPROVED'">
                    <label class="block text-xs font-bold text-slate-700">Note Attribuée (sur 20)</label>
                    <input type="number" step="0.5" min="0" max="20" name="note" x-model="evalNote"
                           class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 focus:ring-1 focus:ring-indigo-500 font-mono">
                </div>

                {{-- Remarques formateur --}}
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700">Remarques & Feedbacks</label>
                    <textarea name="remarques" x-model="evalRemarques" rows="3" placeholder="Points forts, architecture du code, axes d'amélioration..."
                              class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 focus:ring-1 focus:ring-indigo-500"></textarea>
                </div>

                <div class="pt-2 border-t border-slate-100 flex items-center justify-end gap-2">
                    <button type="button" @click="closeEvaluation()" class="px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-2xs transition">
                        Enregistrer l'Évaluation
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
