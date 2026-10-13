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

<form method="GET" action="{{ url()->current() }}" class="inline-flex items-center">
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

    <div class="relative inline-flex items-center group">
        <label for="projet_id" class="sr-only">Session Projet active</label>
        
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-indigo-600">
            <i class="fas fa-project-diagram text-xs"></i>
        </div>

        <select id="projet_id" 
                name="projet_id" 
                onchange="this.form.submit()" 
                class="bg-indigo-50/70 hover:bg-indigo-50 border border-indigo-200 hover:border-indigo-300 text-indigo-900 text-xs font-bold rounded-xl pl-8 pr-8 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white cursor-pointer shadow-2xs transition appearance-none max-w-[280px] sm:max-w-[360px] truncate">
            @forelse($items as $item)
                <option value="{{ $item->id }}" {{ (isset($active) && $active?->id == $item->id) ? 'selected' : '' }}>
                    {{ $item->titre }} @if(!empty($item->formateur)) [{{ $item->formateur->nom ?? $item->formateur }}]@endif
                </option>
            @empty
                <option value="">Aucun projet disponible</option>
            @endforelse
        </select>

        <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-indigo-500">
            <i class="fas fa-chevron-down text-[10px]"></i>
        </div>
    </div>
</form>
