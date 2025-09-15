<article class="projet-card">
    <header class="projet-titre">
        <h2>
            <a href="{{ $entity->chapitre->lien }}" target="_blank">{{  $entity->chapitre }}</a>
        </h2>
        <small> <b>Unité d'apprentissage</b> :  {{  $entity->realisationUa->uniteApprentissage->nom }}</small>
        <br>
        <small> <b>Autoformation </b> : {{  $entity->realisationUa->realisationMicroCompetence->microCompetence->titre }}</small>

        @if($entity->realisationTache)
            <br>
            <small> <b>Projet </b> :  {{  $entity->realisationTache->realisationProjet->affectationProjet->projet}}</small>
        @endif
    </header>
</article>
