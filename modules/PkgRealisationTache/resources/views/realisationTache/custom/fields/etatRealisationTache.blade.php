<div class="realisation-etat"  style="--etat-color: {{ $entity->etatRealisationTache->sysColor->hex ?? '#6c757d' }};">
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
             <i class="fas fa-history"></i>
            {{ $dateModification->diffForHumans() }}
        </span>
    @endif

    {{-- Révisions si état = TO_APPROVE --}}
    @php
        $priority = $entity->tache->priorite ?? 0;
        $revisions = ($revisionsBeforePriorityGrouped[$entity->realisation_projet_id] ?? collect())
            ->filter(fn($rev) => $rev->tache->priorite < $priority && $rev->id !== $entity->id);
    @endphp

    @foreach($revisions as $tacheEnRevision)
        <span class="etat-meta" title="Révision : {{ $tacheEnRevision->tache?->titre }}" data-toggle="tooltip">
            <i class="fas fa-redo"></i> {{ $tacheEnRevision->tache?->titre }}
        </span>
    @endforeach

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


    {{-- Apprenant en live coding --}}
    @php
        $apprenant_live_coding = $entity->tacheAffectation?->apprenant_live_coding_cache ?? null;
    @endphp

    @if($apprenant_live_coding && isset($apprenant_live_coding['apprenant']))
        <span class="etat-meta" 
            title="Apprenant chargé de live coding"
            data-toggle="tooltip">
            <i class="fas fa-laptop-code text-indigo"></i>
            {{-- Si relation apprenant est dispo --}}
          
            {{$apprenant_live_coding['apprenant']}}
        </span>
    @endif
   

</div>
