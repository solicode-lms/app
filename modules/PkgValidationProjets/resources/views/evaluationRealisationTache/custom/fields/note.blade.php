@php
    // Formatage des notes
    $noteFmt    = $entity->note !== null ? number_format($entity->note, 2, '.', '') : '—';
    $avgNote    = $entity->realisationTache?->note;
    $avgNoteFmt = $avgNote !== null ? number_format($avgNote, 2, '.', '') : null;
@endphp

<div class="text-center align-middle">
    <div class="d-flex flex-column align-items-center">
        <span class="font-weight-bold">{{ $noteFmt }}</span>
        @if($avgNoteFmt !== null)
            <small class="text-muted">Moyenne : {{ $avgNoteFmt }}</small>
        @endif
    </div>
</div>
