@php
    // Formatage des notes
    $noteFmt    = $entity->note !== null ? number_format($entity->note, 2, '.', '') : '—';
    $avgNote    = $entity->realisationTache?->note;
    $bareme        = $entity->realisationTache?->tache?->note;
    $baremeFmt = $bareme !== null ? number_format($bareme, 2, '.', '') : null;
    $avgNoteFmt = $avgNote !== null ? number_format($avgNote, 2, '.', '') : null;
@endphp

<div class="text-center align-middle">
    <div class="d-flex flex-column align-items-center">
        <span class="font-weight-bold">{{ $noteFmt }} / {{$baremeFmt}}</span>
        @if($avgNoteFmt !== null)
            {{-- l'évaluateur ne doit pas voir la moyenne --}}
            {{-- <small class="text-muted">Moyenne : {{ $avgNoteFmt }}</small> --}}
        @endif
    </div>
</div>
