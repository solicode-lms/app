@php
    // Vérifie si l'utilisateur est évaluateur ou formateur assigné comme évaluateur
    $currentEvalId = $entity->currentEvaluateurId();

    // Note à afficher (personnelle ou moyenne)
    $displayPrimary = $entity->getDisplayNote();

    // Moyenne stockée dans $entity->note
    $avgNote = $entity->getAverageNote();

    // Formatage
    $displayPrimaryFmt = $displayPrimary !== null ? number_format($displayPrimary, 2, '.', '') : '—';
    $avgNoteFmt       = $avgNote !== null ? number_format($avgNote, 2, '.', '') : '—';
@endphp

<div class="text-center align-middle">
    <div class="d-flex flex-column align-items-center">
        <span class="font-weight-bold">{{ $displayPrimaryFmt }}</span>
        <small class="text-muted">Moyenne : {{ $avgNoteFmt }}</small>
    </div>
</div>

