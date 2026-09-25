@if(!is_null($entity->note_obtenu))
    @php
        $bareme = $entity->qcm ? $entity->qcm->questions()->sum('bareme') : 0;
        $note = number_format($entity->note_obtenu, 2, '.', '');
        $baremeText = number_format($bareme, 0, '.', '');
        $percentage = $bareme > 0 ? ($entity->note_obtenu / $bareme) * 100 : 0;
        $colorClass = $percentage >= 50 ? 'text-success' : 'text-danger';
    @endphp
    <span class="font-weight-bold {{ $colorClass }}" style="font-size: 1.1em;">{{ $note }}</span> 
    <span class="text-muted">/ {{ $baremeText }}</span>
@else
    <span class="text-muted">—</span>
@endif
