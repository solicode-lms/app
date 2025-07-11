<div class="text-center align-middle">
    @php
        // ID de l'évaluateur ou formateur assigné
        $currentEvalId = $entity->currentEvaluateurId();
        // Récupère le message personnel ou vide
        $displayRemark = $entity->getDisplayMessage();
    @endphp

    @if($currentEvalId)
        <div>
            {!! $displayRemark !!}
        </div>
    @else
        <span class="text-muted">—</span>
    @endif
</div>