<div class="realisation-etat"
     style="--etat-color: {{ $entity->etatRealisationModule->sysColor->hex ?? '#6c757d' }};">

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

    {{-- Rythme --}}
    <span class="etat-meta" title="Rythme" data-toggle="tooltip">
        <i class="far fa-clock"></i>
        Rythme : {{ $entity->taux_rythme_cache }} %
    </span>

    {{-- Lecture pédagogique --}}
    <div class="etat-meta" title="Lecture pédagogique" data-toggle="tooltip">
        <i class="fas fa-book-open"></i>
        {!! $entity->lecture_pedagogique !!}
    </div>

    {{-- Dernière mise à jour --}}
    <span class="etat-meta" title="Date de dernière modification" data-toggle="tooltip">
        <i class="far fa-clock"></i>
        {{ $entity->dernier_update }}
    </span>
</div>
