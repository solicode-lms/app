  @if($entity->note) 
  {{ $entity->note }} /  {{ $entity->projet?->total_notes }}
  @endif