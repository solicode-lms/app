<x-badge 
:text="Str::limit($entity->etatRealisationTache->nom ?? '', 20)" 
:background="$entity->etatRealisationTache->sysColor->hex ?? '#6c757d'" 
/> 

@php
    $last = $entity->historiqueRealisationTaches?->last();
    $dateModification = $last?->dateModification ? \Carbon\Carbon::parse($last->dateModification) : null;
@endphp
@if($dateModification)
<span class="d-block text-muted small" title="Date de dernière modification" data-toggle="tooltip">
    — {{ $dateModification->diffForHumans() }}
</span>
@endif
@if($entity->etatRealisationTache?->workflowTache?->code == "TO_APPROVE")
{{-- Il faut l'optimiser pour le chargement : il créer plus de 500 requête SQL en cas des tâches en validation --}}
{{-- @foreach($entity->getRevisionsBeforePriority() as $tacheEnRevision)
    <span class="d-block text-muted small" title="Révision : {{ $tacheEnRevision->tache?->titre }}" data-toggle="tooltip">
        — Révision : {{ $tacheEnRevision->tache?->titre }}
    </span>
@endforeach --}}
@endif

@if( !is_null($entity->note) )
    <span class="d-block text-muted small" title="Note : {{ $entity->note }}" data-toggle="tooltip">
        — Note : {{ $entity->note }} / {{ $entity->tache?->note }}
    </span>
@endif


@if($entity->tacheAffectation?->pourcentage_realisation_cache !== null)
    @php
        $progression = $entity->tacheAffectation->pourcentage_realisation_cache;
    @endphp
    <small class="text-muted d-block" data-toggle="tooltip" title="Progression de la classe">
        —   <i class="fas fa-users me-1"></i> {{ $progression }}% réalisés
    </small>
@endif