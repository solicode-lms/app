@php  
    $dateModification = $entity->dateModification ? \Carbon\Carbon::parse($entity->dateModification) : null;
@endphp

@if($dateModification)
<span title="{{$dateModification}}" data-toggle="tooltip">
   {{ $dateModification?->diffForHumans() }}
</span>
@endif