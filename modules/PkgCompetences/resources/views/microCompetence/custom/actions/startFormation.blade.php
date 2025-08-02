@php
    $realisation = $entity->realisationMicroCompetences
        ->where('apprenant_id', auth()->user()->apprenant->id ?? null)
        ->first();
@endphp

@if($realisation)
    {{-- Lien vers la réalisation existante --}}
   
    <a
        data-toggle="tooltip"
        title="Consulter ma progression" 
        href="{{ route('realisationChapitres.index', [
                'showIndex' => true,
                'contextKey' => 'microCompetence.index',
                'scope.realisationChapitre.RealisationUa.Realisation_micro_competence_id' => $realisation->id,
        ]) }}"
        class="btn btn-info btn-sm context-state actionEntity showIndex d-none d-md-inline d-lg-inline "
         data-id="{{ $entity->id }}" >
        <i class="fas fas fa-code"></i>
    </a>


@else
    {{-- Lien pour démarrer la formation --}}
    <a 
        data-toggle="tooltip" 
        title="Suivre la formation" 
        href="{{ route('microCompetences.startFormation', ['id' => $entity->id]) }}" 
        data-id="{{ $entity->id }}" 
        data-url="{{ route('microCompetences.startFormation', ['id' => $entity->id]) }}" 
        data-action-type="confirm"
        class="btn btn-default btn-sm d-none d-md-inline d-lg-inline context-state actionEntity">
        <i class="fas fa-graduation-cap"></i>
    </a>
@endif
