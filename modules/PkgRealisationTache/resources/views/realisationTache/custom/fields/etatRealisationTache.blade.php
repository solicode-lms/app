<div class="etat-tache">
    {{-- Badge état --}}
    <x-badge 
        :text="Str::limit($entity->etatRealisationTache->nom ?? '', 20)" 
        :background="$entity->etatRealisationTache->sysColor->hex ?? '#6c757d'" 
        class="badge-etat"
    /> 

    {{-- Date dernière modification --}}
    @php
        $last = $entity->historiqueRealisationTaches?->last();
        $dateModification = $last?->dateModification ? \Carbon\Carbon::parse($last->dateModification) : null;
    @endphp
    @if($dateModification)
        <span class="etat-meta" title="Date de dernière modification" data-toggle="tooltip">
            <i class="far fa-clock"></i> {{ $dateModification->diffForHumans() }}
        </span>
    @endif

    {{-- Révisions si état = TO_APPROVE --}}
    @if($entity->etatRealisationTache?->workflowTache?->code == "TO_APPROVE")
        @foreach($entity->getRevisionsBeforePriority() as $tacheEnRevision)
            <span class="etat-meta" title="Révision : {{ $tacheEnRevision->tache?->titre }}" data-toggle="tooltip">
                <i class="fas fa-redo"></i> {{ $tacheEnRevision->tache?->titre }}
            </span>
        @endforeach
    @endif

    {{-- Note --}}
    @if(!is_null($entity->note))
        <span class="etat-meta" title="Note : {{ $entity->note }}" data-toggle="tooltip">
            <i class="fas fa-star"></i> {{ $entity->note }} / {{ $entity->tache?->note }}
        </span>
    @endif

    {{-- Progression --}}
    @if($entity->tacheAffectation?->pourcentage_realisation_cache !== null)
       @php
        $progression = $entity->tacheAffectation->pourcentage_realisation_cache;
        if ($progression >= 90) {
            $icone = 'fas fa-battery-full text-success';
        } elseif ($progression >= 60) {
            $icone = 'fas fa-battery-three-quarters text-success';
        } elseif ($progression >= 30) {
            $icone = 'fas fa-battery-half text-warning';
        } elseif ($progression > 0) {
            $icone = 'fas fa-battery-quarter text-danger';
        } else {
            $icone = 'fas fa-battery-empty text-muted';
        }
        @endphp

        <span class="etat-meta" title="Progression de la classe" data-toggle="tooltip">
            <i class="{{ $icone }}"></i> {{ $progression }}% réalisés par la classe
        </span>
    @endif
</div>
