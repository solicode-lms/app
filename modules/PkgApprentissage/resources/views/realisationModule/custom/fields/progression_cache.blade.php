
 <div class="realisation-etat">

     <x-badge 
        :text="Str::limit($entity->etatRealisationModule->nom ?? '', 20)" 
        :background="$entity->etatRealisationModule->sysColor->hex ?? '#6c757d'" 
        class="badge-etat"
    /> 


    <span class="etat-meta" title="Rythme" data-toggle="tooltip">
            <i class="fas fa-bolt"></i> Rythme : {{ $entity->taux_rythme_cache }} %
    </span>

    {{-- Lecture pédagogique (contenu texte/html) --}}
    <div class="etat-meta"  title="Lecture pédagogique" data-toggle="tooltip">
        <i class="fas fa-user-md"></i>
        {!! $entity->lecture_pedagogique !!}
    </div>

    <span class="etat-meta" title="Date de dernière modification" data-toggle="tooltip">
            <i class="far fa-clock"></i> 
            {{ \Carbon\Carbon::parse($entity->dernier_update)?->diffForHumans() }} 
    </span>


    <span class="etat-meta" title="Progression" data-toggle="tooltip">
        <x-progression-bar 
            :progression="$entity->progression_cache ?? 0" 
            :progression-ideal="$entity->progression_ideal_cache ?? 0" 
            />
    </span>


<span class="etat-meta progression-meta" title="Progression" data-toggle="tooltip">
    <i class="fas fa-chart-line"></i> 
    <div class="progression-wrapper">
        <div class="progression-label">
            {{ round($entity->progression_cache ?? 0) }} %
        </div>
        <x-progression-bar 
            :progression="$entity->progression_cache ?? 0" 
            :progression-ideal="$entity->progression_ideal_cache ?? 0" 
        />
    </div>
</span>

</div>