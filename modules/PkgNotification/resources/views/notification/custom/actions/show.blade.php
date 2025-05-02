@can('view', $entity)
@if(empty($entity->data["lien"]))
<a href="{{ route('notifications.show', ['notification' => $entity->id]) }}" data-id="{{$entity->id}}" class="btn btn-default btn-sm context-state showEntity">
    <i class="far fa-eye"></i>
</a>
@else
<a href="{{ $entity->data["lien"] }}" data-id="{{$entity->id}}" class="btn btn-default btn-sm context-state">
    <i class="far fa-eye"></i>
</a>
@endif
@endcan

