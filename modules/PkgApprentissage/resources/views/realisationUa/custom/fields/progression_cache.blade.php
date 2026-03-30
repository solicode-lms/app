<x-progression-bar 
    :progression="$entity->progression_cache ?? 0" 
    :progression-ideal="$entity->progression_ideal_cache ?? 0" 
    :pourcentage-non-valide="$entity->pourcentage_non_valide_cache ?? 0"
    :bareme-non-evalue="$entity->bareme_non_evalue_cache ?? 0"
    :taux-rythme="$entity->taux_rythme_cache" />

@if(isset($entity->bareme_non_evalue_cache) && $entity->bareme_non_evalue_cache > 0)
    <span class="etat-meta" title="En attente d'évaluation" data-toggle="tooltip">
        <i class="fas fa-hourglass-half text-warning"></i>
        À évaluer : {{ $entity->bareme_non_evalue_cache }} Pts
    </span>
@endif

<small class="lecture-pedagogique text-muted" style="white-space: normal;">
    {!! $entity->lecture_pedagogique !!}
</small>