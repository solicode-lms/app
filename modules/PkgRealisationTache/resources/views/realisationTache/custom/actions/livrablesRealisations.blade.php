@php
            $realisationChapitre = $entity->realisationChapitres?->first();
@endphp

@if($realisationChapitre && empty($realisationChapitre->realisationUa->realisationMicroCompetence->lien_livrable))
 <a
    data-toggle="tooltip"
    title="Autoformation"
    href="{{ route('realisationMicroCompetences.index', [
            'action' => 'edit',
            'id' =>  $realisationChapitre->realisationUa->realisationMicroCompetence->id,
            'contextKey' => 'realisationMicroCompetence.index',
    ]) }}"
    class="btn btn-default btn-sm context-state   "
    data-id="{{ $entity->id }}">
    <i class="fas fa-certificate"></i>
</a>
@endif

@if($entity->tache->livrables->isNotEmpty())
<a
    data-toggle="tooltip"
    title="Livrables"
    href="{{ route('livrablesRealisations.index', [
            'showIndex' => true,
            'contextKey' => 'livrablesRealisation.index',
            'scope.livrable.projet_id' => $entity->realisationProjet->affectationProjet->projet_id,
            'scope.livrablesRealisation.realisation_projet_id' => $entity->realisation_projet_id,
    ]) }}"
    class="btn btn-default btn-sm context-state actionEntity showIndex d-none d-md-inline d-lg-inline "
    data-id="{{ $entity->id }}">
    <i class="fas fa-file-alt"></i>
</a>
@endif