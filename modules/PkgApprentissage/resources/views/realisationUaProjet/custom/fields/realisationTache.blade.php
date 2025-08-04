{{  $entity->realisationTache->tache?->titre }}
<br>
<small><b>Unité d'apprentissage</b> : {{  $entity->realisationUa->uniteApprentissage }}</small>
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

