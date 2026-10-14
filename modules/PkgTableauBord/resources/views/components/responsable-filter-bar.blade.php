@props([
    'formateurs' => collect(),
    'selectedFormateurId' => null,
    'filieres' => collect(),
    'selectedFiliereId' => null,
    'groupes' => collect(),
    'selectedGroupeId' => null,
    'projets' => collect(),
    'selectedProjetId' => null,
    'filterType' => 'sessions',
])

@php
    $hasActiveFilters = !empty($selectedFormateurId) || !empty($selectedFiliereId) || !empty($selectedGroupeId) || !empty($selectedProjetId) || $filterType === 'all';
    $activeCount = 0;
    if (!empty($selectedFormateurId)) $activeCount++;
    if (!empty($selectedFiliereId)) $activeCount++;
    if (!empty($selectedGroupeId)) $activeCount++;
    if (!empty($selectedProjetId)) $activeCount++;
    if ($filterType === 'all') $activeCount++;
@endphp

<form method="GET" action="{{ url()->current() }}" id="responsable-filter-bar-form" class="space-y-2.5">

    <!-- En-tête de la barre de filtres : Titre, Badges contextuels & Réinitialisation -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-100 pb-2">
        <div class="flex items-center gap-2 flex-wrap">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500 flex items-center gap-1.5">
                <i class="fas fa-sliders text-indigo-600 text-xs"></i>
                <span>Périmètre d'analyse</span>
            </span>

            @if(!$hasActiveFilters)
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200 flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Vision Globale (Toutes les sessions N2)</span>
                </span>
            @else
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200 flex items-center gap-1.5">
                    <i class="fas fa-filter text-[10px] text-indigo-500"></i>
                    <span>{{ $activeCount }} filtre{{ $activeCount > 1 ? 's' : '' }} appliqué{{ $activeCount > 1 ? 's' : '' }}</span>
                </span>
            @endif
        </div>

        <div class="flex items-center gap-2">
            @if($hasActiveFilters)
                <a href="{{ url()->current() }}" 
                   class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-bold text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200 rounded-lg transition shadow-2xs" 
                   title="Réinitialiser tous les filtres et revenir à la Vision Globale">
                    <i class="fas fa-rotate-left text-[11px]"></i>
                    <span>Vision Globale (Réinitialiser)</span>
                </a>
            @endif
        </div>
    </div>

    <!-- Grille des 5 sélecteurs de filtre -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-5 gap-3">

        {{-- 1. Sélecteur de Formateur --}}
        <div>
            <label for="filter_formateur_id" class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">
                Formateur
            </label>
            <div class="relative">
                <select id="filter_formateur_id" 
                        name="formateur_id" 
                        data-hs-select='{
                            "hasSearch": true,
                            "searchPlaceholder": "Rechercher...",
                            "searchClasses": "block w-full text-xs bg-slate-50 border-slate-200 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 py-1.5 px-3 mb-1",
                            "searchWrapperClasses": "p-2 bg-white sticky top-0 border-b border-slate-100 z-10",
                            "placeholder": "Tous les formateurs",
                            "toggleTag": "<button type=\"button\" aria-expanded=\"false\"></button>",
                            "toggleClasses": "hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative py-2 ps-8 pe-7 inline-flex justify-between items-center text-start {{ !empty($selectedFormateurId) ? 'bg-indigo-50 text-indigo-900 border-indigo-300 font-bold' : 'bg-white text-slate-700 border-slate-200 hover:border-slate-300 font-medium' }} border text-xs rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 cursor-pointer shadow-2xs transition truncate w-full",
                            "dropdownClasses": "mt-2 z-50 w-full max-h-72 p-1.5 space-y-0.5 bg-white border border-slate-200 rounded-lg shadow-lg overflow-hidden overflow-y-auto",
                            "optionClasses": "py-1.5 px-2.5 w-full text-xs font-medium text-slate-800 cursor-pointer hover:bg-indigo-50 hover:text-indigo-900 rounded-md focus:outline-none transition flex items-center justify-between",
                            "optionTemplate": "<div class=\"flex justify-between items-center w-full\"><span data-title class=\"truncate\"></span><span class=\"hidden hs-selected:block ms-2 text-indigo-600\"><i class=\"fas fa-check text-[10px]\"></i></span></div>",
                            "extraMarkup": "<div class=\"absolute top-1/2 start-2.5 -translate-y-1/2 text-indigo-600 pointer-events-none\"><i class=\"fas fa-chalkboard-user text-xs\"></i></div><div class=\"absolute top-1/2 end-2 -translate-y-1/2 text-slate-400 pointer-events-none\"><i class=\"fas fa-chevron-down text-[9px]\"></i></div>"
                        }' 
                        class="hidden">
                    <option value="">Tous les formateurs</option>
                    @foreach($formateurs as $formateur)
                        <option value="{{ $formateur->id }}" {{ $selectedFormateurId == $formateur->id ? 'selected' : '' }}>
                            {{ $formateur->nom }} {{ $formateur->prenom }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- 2. Sélecteur de Filière --}}
        <div>
            <label for="filter_filiere_id" class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">
                Filière
            </label>
            <div class="relative">
                <select id="filter_filiere_id" 
                        name="filiere_id" 
                        data-hs-select='{
                            "hasSearch": true,
                            "searchPlaceholder": "Rechercher...",
                            "searchClasses": "block w-full text-xs bg-slate-50 border-slate-200 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 py-1.5 px-3 mb-1",
                            "searchWrapperClasses": "p-2 bg-white sticky top-0 border-b border-slate-100 z-10",
                            "placeholder": "Toutes les filières",
                            "toggleTag": "<button type=\"button\" aria-expanded=\"false\"></button>",
                            "toggleClasses": "hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative py-2 ps-8 pe-7 inline-flex justify-between items-center text-start {{ !empty($selectedFiliereId) ? 'bg-indigo-50 text-indigo-900 border-indigo-300 font-bold' : 'bg-white text-slate-700 border-slate-200 hover:border-slate-300 font-medium' }} border text-xs rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 cursor-pointer shadow-2xs transition truncate w-full",
                            "dropdownClasses": "mt-2 z-50 w-full max-h-72 p-1.5 space-y-0.5 bg-white border border-slate-200 rounded-lg shadow-lg overflow-hidden overflow-y-auto",
                            "optionClasses": "py-1.5 px-2.5 w-full text-xs font-medium text-slate-800 cursor-pointer hover:bg-indigo-50 hover:text-indigo-900 rounded-md focus:outline-none transition flex items-center justify-between",
                            "optionTemplate": "<div class=\"flex justify-between items-center w-full\"><span data-title class=\"truncate\"></span><span class=\"hidden hs-selected:block ms-2 text-indigo-600\"><i class=\"fas fa-check text-[10px]\"></i></span></div>",
                            "extraMarkup": "<div class=\"absolute top-1/2 start-2.5 -translate-y-1/2 text-indigo-600 pointer-events-none\"><i class=\"fas fa-layer-group text-xs\"></i></div><div class=\"absolute top-1/2 end-2 -translate-y-1/2 text-slate-400 pointer-events-none\"><i class=\"fas fa-chevron-down text-[9px]\"></i></div>"
                        }' 
                        class="hidden">
                    <option value="">Toutes les filières</option>
                    @foreach($filieres as $filiere)
                        <option value="{{ $filiere->id }}" {{ $selectedFiliereId == $filiere->id ? 'selected' : '' }}>
                            {{ $filiere->code ?? $filiere->nom }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- 3. Sélecteur de Groupe --}}
        <div>
            <label for="filter_groupe_id" class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">
                Groupe
            </label>
            <div class="relative">
                <select id="filter_groupe_id" 
                        name="groupe_id" 
                        data-hs-select='{
                            "hasSearch": true,
                            "searchPlaceholder": "Rechercher...",
                            "searchClasses": "block w-full text-xs bg-slate-50 border-slate-200 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 py-1.5 px-3 mb-1",
                            "searchWrapperClasses": "p-2 bg-white sticky top-0 border-b border-slate-100 z-10",
                            "placeholder": "Tous les groupes",
                            "toggleTag": "<button type=\"button\" aria-expanded=\"false\"></button>",
                            "toggleClasses": "hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative py-2 ps-8 pe-7 inline-flex justify-between items-center text-start {{ !empty($selectedGroupeId) ? 'bg-indigo-50 text-indigo-900 border-indigo-300 font-bold' : 'bg-white text-slate-700 border-slate-200 hover:border-slate-300 font-medium' }} border text-xs rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 cursor-pointer shadow-2xs transition truncate w-full",
                            "dropdownClasses": "mt-2 z-50 w-full max-h-72 p-1.5 space-y-0.5 bg-white border border-slate-200 rounded-lg shadow-lg overflow-hidden overflow-y-auto",
                            "optionClasses": "py-1.5 px-2.5 w-full text-xs font-medium text-slate-800 cursor-pointer hover:bg-indigo-50 hover:text-indigo-900 rounded-md focus:outline-none transition flex items-center justify-between",
                            "optionTemplate": "<div class=\"flex justify-between items-center w-full\"><span data-title class=\"truncate\"></span><span class=\"hidden hs-selected:block ms-2 text-indigo-600\"><i class=\"fas fa-check text-[10px]\"></i></span></div>",
                            "extraMarkup": "<div class=\"absolute top-1/2 start-2.5 -translate-y-1/2 text-indigo-600 pointer-events-none\"><i class=\"fas fa-users text-xs\"></i></div><div class=\"absolute top-1/2 end-2 -translate-y-1/2 text-slate-400 pointer-events-none\"><i class=\"fas fa-chevron-down text-[9px]\"></i></div>"
                        }' 
                        class="hidden">
                    <option value="">Tous les groupes</option>
                    @foreach($groupes as $groupe)
                        <option value="{{ $groupe->id }}" {{ $selectedGroupeId == $groupe->id ? 'selected' : '' }}>
                            {{ $groupe->code ?? $groupe->nom }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- 4. Sélecteur Type de Projets (Sessions N2 vs Tous) --}}
        <div>
            <label for="filter_type_projet" class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">
                Type de Projet
            </label>
            <div class="relative">
                <select id="filter_type_projet" 
                        name="type_projet" 
                        data-hs-select='{
                            "placeholder": "Sessions N2 uniquement",
                            "toggleTag": "<button type=\"button\" aria-expanded=\"false\"></button>",
                            "toggleClasses": "hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative py-2 ps-7 pe-7 inline-flex justify-between items-center text-start {{ $filterType === 'all' ? 'bg-amber-50 text-amber-900 border-amber-300 font-bold' : 'bg-white text-slate-700 border-slate-200 hover:border-slate-300 font-medium' }} border text-xs rounded-lg focus:outline-none cursor-pointer shadow-2xs transition truncate w-full",
                            "dropdownClasses": "mt-2 z-50 w-full max-h-72 p-1.5 space-y-0.5 bg-white border border-slate-200 rounded-lg shadow-lg overflow-hidden",
                            "optionClasses": "py-1.5 px-2.5 w-full text-xs font-medium text-slate-800 cursor-pointer hover:bg-indigo-50 hover:text-indigo-900 rounded-md focus:outline-none transition flex items-center justify-between",
                            "optionTemplate": "<div class=\"flex justify-between items-center w-full\"><span data-title class=\"truncate\"></span><span class=\"hidden hs-selected:block ms-2 text-indigo-600\"><i class=\"fas fa-check text-[10px]\"></i></span></div>",
                            "extraMarkup": "<div class=\"absolute top-1/2 start-2 -translate-y-1/2 text-slate-500 pointer-events-none\"><i class=\"fas fa-filter text-[11px]\"></i></div><div class=\"absolute top-1/2 end-2 -translate-y-1/2 text-slate-400 pointer-events-none\"><i class=\"fas fa-chevron-down text-[9px]\"></i></div>"
                        }' 
                        class="hidden">
                    <option value="sessions" {{ $filterType === 'sessions' ? 'selected' : '' }}>Sessions N2 uniquement</option>
                    <option value="all" {{ $filterType === 'all' ? 'selected' : '' }}>Tous les projets</option>
                </select>
            </div>
        </div>

        {{-- 5. Sélecteur de Projet / Session --}}
        <div>
            <label for="filter_projet_id" class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">
                Projet / Session
            </label>
            <div class="relative">
                <select id="filter_projet_id" 
                        name="projet_id" 
                        data-hs-select='{
                            "hasSearch": true,
                            "searchPlaceholder": "Rechercher...",
                            "searchClasses": "block w-full text-xs bg-slate-50 border-slate-200 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 py-1.5 px-3 mb-1",
                            "searchWrapperClasses": "p-2 bg-white sticky top-0 border-b border-slate-100 z-10",
                            "placeholder": "{{ $filterType === 'all' ? 'Tous les projets' : 'Toutes les sessions N2' }}",
                            "toggleTag": "<button type=\"button\" aria-expanded=\"false\"></button>",
                            "toggleClasses": "hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative py-2 ps-8 pe-7 inline-flex justify-between items-center text-start {{ !empty($selectedProjetId) ? 'bg-indigo-50 text-indigo-900 border-indigo-300 font-bold' : 'bg-white text-slate-700 border-slate-200 hover:border-slate-300 font-medium' }} border text-xs rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 cursor-pointer shadow-2xs transition truncate w-full",
                            "dropdownClasses": "mt-2 z-50 w-full max-h-72 p-1.5 space-y-0.5 bg-white border border-slate-200 rounded-lg shadow-lg overflow-hidden overflow-y-auto",
                            "optionClasses": "py-1.5 px-2.5 w-full text-xs font-medium text-slate-800 cursor-pointer hover:bg-indigo-50 hover:text-indigo-900 rounded-md focus:outline-none transition flex items-center justify-between",
                            "optionTemplate": "<div class=\"flex justify-between items-center w-full\"><span data-title class=\"truncate\"></span><span class=\"hidden hs-selected:block ms-2 text-indigo-600\"><i class=\"fas fa-check text-[10px]\"></i></span></div>",
                            "extraMarkup": "<div class=\"absolute top-1/2 start-2.5 -translate-y-1/2 text-indigo-600 pointer-events-none\"><i class=\"fas fa-project-diagram text-xs\"></i></div><div class=\"absolute top-1/2 end-2 -translate-y-1/2 text-slate-400 pointer-events-none\"><i class=\"fas fa-chevron-down text-[9px]\"></i></div>"
                        }' 
                        class="hidden">
                    <option value="">{{ $filterType === 'all' ? 'Tous les projets' : 'Toutes les sessions N2 (Global)' }}</option>
                    @foreach($projets as $projet)
                        @php
                            $isSessionN2 = $projet->has_live_coding ?? $projet->isSessionFormation();
                        @endphp
                        <option value="{{ $projet->id }}" {{ $selectedProjetId == $projet->id ? 'selected' : '' }}>
                            {{ $isSessionN2 ? '[Session N2]' : '[Standard]' }} {{ $projet->titre }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

    </div>
</form>

<script>
    (function() {
        const initFilterBar = () => {
            const form = document.getElementById('responsable-filter-bar-form');
            if (!form) return;

            const formateurSel = document.getElementById('filter_formateur_id');
            const filiereSel = document.getElementById('filter_filiere_id');
            const groupeSel = document.getElementById('filter_groupe_id');
            const typeSel = document.getElementById('filter_type_projet');
            const projetSel = document.getElementById('filter_projet_id');

            let submitting = false;
            const submitWithReset = (resetGroupe = false, resetProjet = false) => {
                if (submitting) return;
                submitting = true;
                if (resetGroupe && groupeSel) groupeSel.disabled = true;
                if (resetProjet && projetSel) projetSel.disabled = true;
                form.submit();
            };

            const attachSelectEvents = () => {
                if (formateurSel) formateurSel.addEventListener('change', () => submitWithReset(true, true));
                if (filiereSel) filiereSel.addEventListener('change', () => submitWithReset(true, true));
                if (groupeSel) groupeSel.addEventListener('change', () => submitWithReset(false, true));
                if (typeSel) typeSel.addEventListener('change', () => submitWithReset(false, true));
                if (projetSel) projetSel.addEventListener('change', () => submitWithReset(false, false));

                if (window.HSSelect) {
                    [
                        { el: formateurSel, resetG: true, resetP: true },
                        { el: filiereSel, resetG: true, resetP: true },
                        { el: groupeSel, resetG: false, resetP: true },
                        { el: typeSel, resetG: false, resetP: true },
                        { el: projetSel, resetG: false, resetP: false }
                    ].forEach(({ el, resetG, resetP }) => {
                        if (!el) return;
                        const instance = window.HSSelect.getInstance(el);
                        if (instance && !instance._respBound) {
                            instance._respBound = true;
                            instance.on('change', () => submitWithReset(resetG, resetP));
                        }
                    });
                }
            };

            attachSelectEvents();
            window.addEventListener('load', attachSelectEvents);
        };

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initFilterBar);
        } else {
            initFilterBar();
        }
    })();
</script>
