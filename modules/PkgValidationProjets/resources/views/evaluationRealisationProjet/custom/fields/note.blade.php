


<div class="text-center align-middle">
    <div class="d-flex flex-column align-items-center">
        @if($entity->Note)
            <span class="font-weight-bold">{{ $entity->Note }} / {{ $entity->bareme_note }}</span>
        @endif
       @if($entity->realisationProjet->note)
       <small class="text-muted">Moyenne :   {{ $entity->realisationProjet->note }} /  {{ $entity->realisationProjet->bareme_note }}</small>
        @endif
    </div>
</div>
