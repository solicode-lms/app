<x-badge 
:text="Str::limit($entity->etatRealisationTache->nom ?? '', 20)" 
:background="$entity->etatRealisationTache->sysColor->hex ?? '#6c757d'" 
/> 

@if($entity->etatRealisationTache?->workflowTache->code == "EN_VALIDATION")
@foreach($entity->getRevisionsBeforePriority() as $tacheEnRevision)
    <span class="d-block text-muted small" title="Révision : {{ $tacheEnRevision->tache?->titre }}" data-toggle="tooltip">
        — Révision : {{ $tacheEnRevision->tache?->titre }}
    </span>
@endforeach
@endif

@if( !is_null($entity->note) )
    <span class="d-block text-muted small" title="Note : {{ $entity->note }}" data-toggle="tooltip">
        — Note : {{ $entity->note }} / {{ $entity->tache?->note }}
    </span>
@endif