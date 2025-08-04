@php
    $criteresN2 = $entity
        ?->realisationUa
        ?->uniteApprentissage
        ?->critereEvaluations
        ?->where('phaseEvaluation.code', 'N2');
@endphp

{{-- Affichage de la liste (exemple) --}}
@if($criteresN2 && $criteresN2->count())
    <ul style="word-wrap: break-word; overflow-wrap: break-word; white-space: normal;">
        @foreach($criteresN2 as $critere)
            <li style="word-wrap: break-word; overflow-wrap: break-word; white-space: normal;">
                {{ $critere->intitule ?? 'Critère sans nom' }}
            </li>
        @endforeach
    </ul>
@else
    <em>Aucun critère N2 trouvé.</em>
@endif
