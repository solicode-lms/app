@php
  $moyenneFmt = $entity->realisationProjet->note ?   number_format($entity->realisationProjet->note, 2, '.', '') : '—';
@endphp


<div class="text-center align-middle">
    <div class="d-flex flex-column align-items-center">
        @if($entity->note)
            <span class="font-weight-bold">{{ $entity->note }} / {{ $entity->bareme_note }}</span>
        @endif
       @if($entity->realisationProjet->note)
       <small class="text-muted">Moyenne :   {{ $moyenneFmt }} /  {{ $entity->realisationProjet->bareme_note }}</small>
        @endif
    </div>
</div>
