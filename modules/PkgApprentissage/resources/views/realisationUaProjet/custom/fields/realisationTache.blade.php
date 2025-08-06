<article class="projet-card">
    <header class="projet-titre">
        <h2>{{  $entity->realisationUa->uniteApprentissage }}</h2>
    </header>
    <section class="projet-section">
        <small><b>Réalisation Tache</b> : {{  $entity->realisationTache->tache?->titre }}</small>
        <br>
        @if($entity->realisationTache?->etatRealisationTache)
            <small>
                <b>État</b> : 
                <x-badge 
                                    :text="$entity->realisationTache->etatRealisationTache" 
                                    :background="$entity->realisationTache->etatRealisationTache->sysColor->hex ?? '#6c757d'" 
                                    />

            </small>
            @endif
    </section>
</article>






