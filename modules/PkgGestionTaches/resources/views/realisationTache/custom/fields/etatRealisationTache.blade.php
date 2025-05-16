<x-badge 
:text="Str::limit($entity->etatRealisationTache->nom ?? '', 20)" 
:background="$entity->etatRealisationTache->sysColor->hex ?? '#6c757d'" 
/> 

@if($entity->etatRealisationTache?->workflowTache->code == "EN_VALIDATION")
@foreach($entity->getRevisionsBeforePriority() as $tacheEnRevision)
    <span class="d-block text-muted small" title="Révision nécessaire" data-toggle="tooltip">
        — {{ $tacheEnRevision->tache?->titre }}
    </span>
@endforeach
@endif