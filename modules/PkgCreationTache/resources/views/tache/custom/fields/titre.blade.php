<article class="projet-card">
<header class="projet-titre">
    <h2>  {{ $entity->titre }}  </h2>
</header>
@if($entity->dateFin || $entity?->chapitre?->duree_en_heure )
<section class="projet-section">
    <ul class="projet-ressources">
        @if($entity->dateFin)
        <li><strong>{!!ucfirst(__('PkgCreationTache::tache.dateFin'))!!}</strong>  <x-deadline-display :value="$entity->dateFin" /></li>
        @endif
        @if($entity?->chapitre?->duree_en_heure)
            <li>Durée estimée de réalisation : <strong>  {{$entity?->chapitre?->duree_en_heure}} h </strong> </li>
        @endif
        @if($entity->phaseEvaluation)
            <li>{!!ucfirst(__('PkgCompetences::phaseEvaluation.singular'))!!} : <strong> {{  $entity->phaseEvaluation }} </strong> </li>
        @endif
    </ul>
</section>
@endif

</article>
