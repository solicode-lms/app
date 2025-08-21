{{-- Affichage Integer avec cas spéciaux --}}
@if($nature === 'duree')
    @include('duree', ['entity' => $entity, 'column' => $column])
@elseif($nature === 'ordre')
    <div class="sortable-button d-flex justify-content-left align-items-center" style="height: 100%; min-height: 26px;">
        <i class="fas fa-th-list" title="{{ $entity->{$column} }}" data-toggle="tooltip"></i>
    </div>
@else
    {{ $entity->{$column} }}
@endif
