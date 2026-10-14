@props([
    'groupes' => collect(),
    'selectedGroupeId' => null,
    'apprenants' => collect(),
    'selectedApprenantId' => null
])

<form method="GET" action="{{ url()->current() }}" id="apprenant-filter-form" class="inline-flex items-center gap-2 flex-wrap sm:flex-nowrap">
    {{-- Conserver les autres filtres URL éventuels hors groupe, apprenant et projet --}}
    @foreach(request()->except(['groupe_id', 'apprenant_id', 'projet_id', 'session_formation_id']) as $key => $value)
        @if(is_array($value))
            @foreach($value as $v)
                <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
            @endforeach
        @else
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endif
    @endforeach

    {{-- 1. Sélecteur de Groupe (si au moins 1 groupe disponible) --}}
    @if(isset($groupes) && $groupes->count() > 0)
        <div class="relative inline-flex items-center">
            <label for="select_groupe_id" class="sr-only">Filtrer par Groupe</label>
            <select id="select_groupe_id" 
                    name="groupe_id" 
                    data-hs-select='{
                        "placeholder": "Tous les groupes",
                        "toggleTag": "<button type=\"button\" aria-expanded=\"false\"></button>",
                        "toggleClasses": "hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative py-2 ps-8 pe-7 inline-flex justify-between items-center text-start bg-slate-100 hover:bg-slate-200/80 border border-slate-300 text-slate-800 text-xs font-semibold rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 cursor-pointer shadow-2xs transition truncate min-w-[140px] max-w-[200px]",
                        "dropdownClasses": "mt-2 z-50 w-full max-h-72 p-1.5 space-y-0.5 bg-white border border-slate-200 rounded-xl shadow-lg overflow-hidden overflow-y-auto",
                        "optionClasses": "py-2 px-3 w-full text-xs font-medium text-slate-800 cursor-pointer hover:bg-indigo-50 hover:text-indigo-900 rounded-lg focus:outline-none focus:bg-indigo-50 focus:text-indigo-900 transition flex items-center justify-between",
                        "optionTemplate": "<div class=\"flex justify-between items-center w-full\"><span data-title class=\"truncate\"></span><span class=\"hidden hs-selected:block ms-2 text-indigo-600\"><i class=\"fas fa-check text-[10px]\"></i></span></div>",
                        "extraMarkup": "<div class=\"absolute top-1/2 start-2.5 -translate-y-1/2 text-slate-500 pointer-events-none\"><i class=\"fas fa-users text-xs\"></i></div><div class=\"absolute top-1/2 end-2 -translate-y-1/2 text-slate-400 pointer-events-none\"><i class=\"fas fa-chevron-down text-[9px]\"></i></div>"
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
    @endif

    {{-- 2. Sélecteur d'Apprenant avec Recherche Preline UI --}}
    <div class="relative inline-flex items-center">
        <label for="select_apprenant_id" class="sr-only">Choisir l'Apprenant</label>
        <select id="select_apprenant_id" 
                name="apprenant_id" 
                data-hs-select='{
                    "hasSearch": true,
                    "searchPlaceholder": "Rechercher un apprenant...",
                    "searchClasses": "block w-full text-xs bg-slate-50 border-slate-200 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 py-1.5 px-3 mb-1",
                    "searchWrapperClasses": "p-2 bg-white sticky top-0 border-b border-slate-100 z-10",
                    "placeholder": "Sélectionner un apprenant...",
                    "toggleTag": "<button type=\"button\" aria-expanded=\"false\"></button>",
                    "toggleClasses": "hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative py-2 ps-8 pe-7 inline-flex justify-between items-center text-start bg-indigo-50/70 hover:bg-indigo-50 border border-indigo-200 hover:border-indigo-300 text-indigo-900 text-xs font-bold rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 cursor-pointer shadow-2xs transition truncate min-w-[180px] max-w-[260px] sm:max-w-[320px]",
                    "dropdownClasses": "mt-2 z-50 w-full max-h-72 p-1.5 space-y-0.5 bg-white border border-slate-200 rounded-xl shadow-lg overflow-hidden overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-slate-100 [&::-webkit-scrollbar-thumb]:bg-slate-300",
                    "optionClasses": "py-2 px-3 w-full text-xs font-medium text-slate-800 cursor-pointer hover:bg-indigo-50 hover:text-indigo-900 rounded-lg focus:outline-none focus:bg-indigo-50 focus:text-indigo-900 transition flex items-center justify-between",
                    "optionTemplate": "<div class=\"flex justify-between items-center w-full\"><span data-title class=\"truncate\"></span><span class=\"hidden hs-selected:block ms-2 text-indigo-600\"><i class=\"fas fa-check text-[10px]\"></i></span></div>",
                    "extraMarkup": "<div class=\"absolute top-1/2 start-2.5 -translate-y-1/2 text-indigo-600 pointer-events-none\"><i class=\"fas fa-user-graduate text-xs\"></i></div><div class=\"absolute top-1/2 end-2 -translate-y-1/2 text-indigo-500 pointer-events-none\"><i class=\"fas fa-chevron-down text-[9px]\"></i></div>"
                }' 
                class="hidden">
            @forelse($apprenants as $itemApprenant)
                <option value="{{ $itemApprenant->id }}" {{ $selectedApprenantId == $itemApprenant->id ? 'selected' : '' }}>
                    {{ $itemApprenant->nom }} {{ $itemApprenant->prenom }}
                </option>
            @empty
                <option value="">Aucun apprenant</option>
            @endforelse
        </select>
    </div>
</form>

<script>
    (function() {
        const initFilterForm = () => {
            const form = document.getElementById('apprenant-filter-form');
            if (!form) return;

            const groupeSelect = document.getElementById('select_groupe_id');
            const apprenantSelect = document.getElementById('select_apprenant_id');

            let submitting = false;
            const submitWithReset = (resetApprenant = false) => {
                if (submitting) return;
                submitting = true;
                if (resetApprenant && apprenantSelect) {
                    apprenantSelect.disabled = true; // Empêche l'envoi de l'ancien apprenant_id pour sélectionner automatiquement le 1er du nouveau groupe
                }
                form.submit();
            };

            if (groupeSelect) {
                groupeSelect.addEventListener('change', () => submitWithReset(true));
            }

            if (apprenantSelect) {
                apprenantSelect.addEventListener('change', () => submitWithReset(false));
            }

            // Liaison avec Preline UI HSSelect
            const bindPrelineInstances = () => {
                if (window.HSSelect) {
                    if (groupeSelect) {
                        const instanceG = window.HSSelect.getInstance(groupeSelect);
                        if (instanceG && !instanceG._filterBound) {
                            instanceG._filterBound = true;
                            instanceG.on('change', () => submitWithReset(true));
                        }
                    }
                    if (apprenantSelect) {
                        const instanceA = window.HSSelect.getInstance(apprenantSelect);
                        if (instanceA && !instanceA._filterBound) {
                            instanceA._filterBound = true;
                            instanceA.on('change', () => submitWithReset(false));
                        }
                    }
                }
            };

            bindPrelineInstances();
            window.addEventListener('load', bindPrelineInstances);
        };

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initFilterForm);
        } else {
            initFilterForm();
        }
    })();
</script>
