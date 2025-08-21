{{-- Affichage String avec cas spéciaux --}}
@if($nature === 'icone')
    <div class="d-flex justify-content-center align-items-center" style="height: 100%;">
        <i class="{{ $entity->{$column} }}"></i>
    </div>

@elseif($nature === 'badge')
    <x-badge 
        :text="$entity->{$column} ?? ''" 
        :background="$entity->sysColor->hex ?? '#6c757d'" 
    />

@elseif($nature === 'lien')
    @if($entity->{$column})
        <a href="{{ $entity->{$column} }}" target="_blank">
            <i class="fas fa-link"></i>
        </a>
    @else
        —
    @endif

@else
    {{ $entity->{$column} }}
@endif
