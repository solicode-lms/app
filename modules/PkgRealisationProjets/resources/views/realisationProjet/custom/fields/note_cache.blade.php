@php
    $note         = $entity->note_cache;                              // somme des notes
    $bareme       = $entity->bareme_cache;                       // barème total

    // dd($entity->affectationProjet->echelle_note_cible);
    $echelle      = $entity->affectationProjet->echelle_note_cible ?? null;
    $noteSurEchelle = ($bareme > 0 && $echelle > 0)
        ? $entity->calculerNoteAvecEchelle()
        : null;
    $pourcentage  = ($bareme > 0)
        ? round(($note ?? 0) / $bareme * 100, 2)
        : null;
@endphp



@if ($bareme > 0)


    <div class="text-center align-middle">
        <div class="d-flex flex-column align-items-center">
            <span class="font-weight-bold"> {{ $note }} / {{ $bareme }}</span>
            @if ($noteSurEchelle !== null)
                {{-- Affiche la note recalibrée --}}
                <small class"text-muted">{{ $noteSurEchelle }} / {{ $echelle }}</small>
            @endif

            @if ($pourcentage !== null)
            <small class"text-muted">  ({{ $pourcentage }} %)</small>
            @endif


        </div>
    </div>
@else
    <span>—</span>
@endif
