@props([
    'projets' => null,
    'projetActif' => null,
    'sessions' => null,
    'sessionActive' => null
])

@php
    $items = $projets ?? $sessions ?? collect();
    $active = $projetActif ?? $sessionActive ?? null;
@endphp

<form method="GET" action="{{ url()->current() }}" id="session-selector-form" class="inline-flex items-center">
    {{-- Conserver les filtres existants de l'URL hors sélection de projet --}}
    @foreach(request()->except(['projet_id', 'session_formation_id']) as $key => $value)
        @if(is_array($value))
            @foreach($value as $v)
                <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
            @endforeach
        @else
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endif
    @endforeach

    <div class="relative inline-flex items-center">
        <label for="session_projet_id" class="sr-only">Session Projet active</label>

        <select id="session_projet_id" 
                name="projet_id" 
                data-hs-select='{
                    "hasSearch": true,
                    "searchPlaceholder": "Rechercher un projet...",
                    "searchClasses": "block w-full text-xs bg-slate-50 border-slate-200 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 py-1.5 px-3 mb-1",
                    "searchWrapperClasses": "p-2 bg-white sticky top-0 border-b border-slate-100 z-10",
                    "placeholder": "Sélectionner un projet...",
                    "toggleTag": "<button type=\"button\" aria-expanded=\"false\"></button>",
                    "toggleClasses": "hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative py-2 ps-9 pe-8 inline-flex justify-between items-center text-start bg-indigo-50/70 hover:bg-indigo-50 border border-indigo-200 hover:border-indigo-300 text-indigo-900 text-xs font-bold rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 cursor-pointer shadow-2xs transition truncate min-w-[200px] max-w-[280px] sm:max-w-[360px]",
                    "dropdownClasses": "mt-2 z-50 w-full max-h-72 p-1.5 space-y-0.5 bg-white border border-slate-200 rounded-lg shadow-lg overflow-hidden overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-slate-100 [&::-webkit-scrollbar-thumb]:bg-slate-300",
                    "optionClasses": "py-2 px-3 w-full text-xs font-medium text-slate-800 cursor-pointer hover:bg-indigo-50 hover:text-indigo-900 rounded-md focus:outline-none focus:bg-indigo-50 focus:text-indigo-900 transition flex items-center justify-between",
                    "optionTemplate": "<div class=\"flex justify-between items-center w-full\"><span data-title class=\"truncate\"></span><span class=\"hidden hs-selected:block ms-2 text-indigo-600\"><i class=\"fas fa-check text-[10px]\"></i></span></div>",
                    "extraMarkup": "<div class=\"absolute top-1/2 start-3 -translate-y-1/2 text-indigo-600 pointer-events-none\"><i class=\"fas fa-project-diagram text-xs\"></i></div><div class=\"absolute top-1/2 end-2.5 -translate-y-1/2 text-indigo-500 pointer-events-none\"><i class=\"fas fa-chevron-down text-[10px]\"></i></div>"
                }' 
                class="hidden">
            @forelse($items as $item)
                @php
                    $isSessionN2 = $item->has_live_coding ?? $item->isSessionFormation();
                @endphp
                <option value="{{ $item->id }}" {{ (isset($active) && $active?->id == $item->id) ? 'selected' : '' }}>
                    {{ $isSessionN2 ? '[Session N2]' : '[Standard]' }} {{ $item->titre }} @if(!empty($item->formateur)) [{{ $item->formateur->nom ?? $item->formateur }}]@endif
                </option>
            @empty
                <option value="">Aucun projet disponible</option>
            @endforelse
        </select>
    </div>
</form>

<script>
    (function() {
        const initSelect = () => {
            const selectEl = document.getElementById('session_projet_id');
            if (!selectEl) return;

            let submitted = false;
            const submitOnce = () => {
                if (!submitted) {
                    submitted = true;
                    selectEl.form?.submit();
                }
            };

            // Écoute de l'événement change sur le select natif
            selectEl.addEventListener('change', submitOnce);

            // Écoute sur l'instance Preline HSSelect
            const bindPreline = () => {
                if (window.HSSelect) {
                    const instance = window.HSSelect.getInstance(selectEl);
                    if (instance && !instance._autoSubmitBound) {
                        instance._autoSubmitBound = true;
                        instance.on('change', submitOnce);
                    }
                }
            };

            bindPreline();
            window.addEventListener('load', bindPreline);
        };

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initSelect);
        } else {
            initSelect();
        }
    })();
</script>
