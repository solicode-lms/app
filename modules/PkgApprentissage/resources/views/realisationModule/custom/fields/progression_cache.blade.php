
 <div class="realisation-etat">

     <x-badge 
        :text="Str::limit($entity->etatRealisationModule->nom ?? '', 20)" 
        :background="$entity->etatRealisationModule->sysColor->hex ?? '#6c757d'" 
        class="badge-etat"
    /> 



    {{-- Progression pédagogique --}}
    <div class="etat-meta" title="Progression" data-toggle="tooltip">
        <x-progression-bar 
            :progression="$entity->progression_cache ?? 0" 
            :progression-ideal="$entity->progression_ideal_cache ?? 0" 
            />
    </div>

    <span class="etat-meta" title="Rythme" data-toggle="tooltip">
            <i class="far fa-clock"></i> Rythme : {{ $entity->taux_rythme_cache }} %
    </span>

    {{-- Lecture pédagogique (contenu texte/html) --}}
    <div class="etat-meta"  title="Lecture pédagogique" data-toggle="tooltip">
        <i class="fas fa-book-open"></i>
        {!! $entity->lecture_pedagogique !!}
    </div>

    <span class="etat-meta" title="Date de dernière modification" data-toggle="tooltip">
            <i class="far fa-clock"></i> {{ $entity->dernier_update }} 
    </span>

</div>


<x-progression-bar 
    :progression="$entity->progression_cache ?? 0" 
    :progression-ideal="$entity->progression_ideal_cache ?? 0" 
    :taux-rythme="$entity->taux_rythme_cache" />
 {!! $entity->lecture_pedagogique !!}

