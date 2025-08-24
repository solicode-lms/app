<div class="realisation-etat with-progress"
     style="--etat-color: {{ $entity->etatRealisationCompetence->sysColor->hex ?? '#6c757d' }};">

    <x-badge 
        :text="$entity->etatRealisationCompetence->nom" 
        :background="$entity->etatRealisationCompetence->sysColor->hex ?? '#6c757d'" 
        class="badge-etat"
    />

    <div class="etat-meta">
        <x-progression-bar 
            :progression="$entity->progression_cache ?? 0" 
            :progression-ideal="$entity->progression_ideal_cache ?? 0"
        />
    </div>


    @if($entity->taux_rythme_cache)
    @php
    $rythme = $entity->taux_rythme_cache ?? 0;
    if ($rythme < 20) {
        $icone = 'fas fa-bed'; // très bas
    } elseif ($rythme < 40) {
        $icone = 'fas fa-walking'; // lent
    } elseif ($rythme < 60) {
        $icone = 'fas fa-running'; // normal
    } elseif ($rythme < 80) {
        $icone = 'fas fa-biking'; // rapide
    } else {
        $icone = 'fas fa-rocket'; // très rapide
    }
    @endphp
    {{-- Rythme --}}
    <span class="etat-meta" title="{!! $entity->lecture_pedagogique !!}" data-toggle="tooltip">
        <i class="{{ $icone }}"></i>
        Rythme : {{ $rythme }} %
    </span>
    @endif


    @if($entity->dernier_update)
    <span class="etat-meta">
        <i class="fas fa-history"></i>
        {{  \Carbon\Carbon::parse($entity->dernier_update)?->diffForHumans() }}
    </span>
    @endif
</div>
