<div class="realisation-etat with-progress"  style="--etat-color: {{ $entity->etatsRealisationProjet->sysColor->hex ?? '#6c757d' }};">
    <x-badge 
        :text="$entity->etatsRealisationProjet->titre" 
        :background="$entity->etatsRealisationProjet->sysColor->hex ?? '#6c757d'" 
        class="badge-etat"
    /> 

    @include('PkgRealisationProjets::realisationProjet.custom.fields.progression_validation_cache', ['entity' => $entity])
   

   


    {{-- Note --}}
    @if(!is_null($entity->note_cache))
        <span class="etat-meta" title="Note : {{ $entity->note_cache }}" data-toggle="tooltip">
            <i class="fas fa-star"></i>
            @include('PkgRealisationProjets::realisationProjet.custom.fields.note_cache', ['entity' => $entity])
        </span>
    @endif

    {{-- Date dernière modification --}}
    @php
        $last = $entity->updated_at;
        $dateModification = $last ? \Carbon\Carbon::parse($last) : null;
    @endphp
    @if($dateModification)
        <span class="etat-meta" title="Date de dernière modification" data-toggle="tooltip">
             <i class="fas fa-history"></i>
            {{ $dateModification->diffForHumans() }}
        </span>
    @endif

</div>
