<article class="tache-card">
    <header class="tache-header">
        <h2 class="tache-titre">
            @if($entity?->chapitre?->lien)
                <a href="{{ $entity->chapitre->lien }}" target="_blank">{{ $entity->titre }}</a>
            @else
                {{ $entity->titre }}
            @endif
        </h2>

        @if($entity->phaseEvaluation)
            <small class="tache-projet">
                {!!ucfirst(__('PkgCompetences::phaseEvaluation.singular'))!!} :
                <strong>{{ $entity->phaseEvaluation }}</strong>
            </small>
        @endif
    </header>

    <section class="tache-infos">

        <span class="tache-priorite" data-toggle="tooltip" title="{{ ucfirst(__('PkgCreationTache::tache.priorite')) }}">
                <i class="fas fa-flag"></i> {{ $entity->priorite }}
        </span>


        @if($entity->dateFin)
            <span class="tache-deadline" data-toggle="tooltip" title="{{ ucfirst(__('PkgCreationTache::tache.dateFin')) }}">
                <i class="far fa-clock"></i>
                <x-deadline-display :value="$entity->dateFin" />
            </span>
        @endif

        @if($entity?->chapitre?->duree_en_heure)
            <span class="tache-duree" data-toggle="tooltip" title="{{ ucfirst(__('PkgCompetences::chapitre.duree_en_heure')) }}">
                <i class="fas fa-hourglass-half"></i>
                {{ $entity->chapitre->duree_en_heure }} h
            </span>
        @endif

        @if($entity->note)
       <span class="tache-note" data-toggle="tooltip" 
            title="{{ ucfirst(__('PkgCreationTache::tache.note')) }}">
            <i class="fas fa-star"></i> 
             {{ $entity->note }} /  {{ $entity->projet?->total_notes }}
        </span>
        @endif


    </section>

    <footer class="tache-footer">
        <strong>Projet :</strong> {{ $entity->projet->titre }}
    </footer>
</article>
