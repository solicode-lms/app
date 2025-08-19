<x-progression-bar 
    :progression="$entity->progression_cache ?? 0" 
    :progression-ideal="$entity->progression_ideal_cache ?? 0" 
    :taux-rythme="$entity->taux_rythme_cache" />

<small class="lecture-pedagogique text-muted" style="white-space: normal;">
    {!! $entity->lecture_pedagogique !!}
</small>