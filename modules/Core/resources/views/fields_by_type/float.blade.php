{{-- Affichage Float avec progression (%) --}}
@if($nature === 'progression')
    <div class="progress progress-sm">
        <div class="progress-bar bg-green"
             role="progressbar"
             aria-valuenow="{{ $entity->{$column} }}"
             aria-valuemin="0"
             aria-valuemax="100"
             style="width: {{ $entity->{$column} }}%">
        </div>
    </div>
    <small>{{ $entity->{$column} }}% Terminé</small>
@else
    {{ $entity->{$column} }}
@endif