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
            <small class="tache-projet text-muted">
                <i class="fas fa-layer-group"></i>
                {!!ucfirst(__('PkgCompetences::phaseEvaluation.singular'))!!} :
                <strong>{{ $entity->phaseEvaluation }}</strong>
            </small>
        @endif
    </header>

    <section class="tache-infos mt-2 mb-2">

        <span class="tache-priorite" data-toggle="tooltip" title="{{ ucfirst(__('PkgCreationTache::tache.priorite')) }}">
                <i class="fas fa-flag"></i> {{ $entity->priorite }}
        </span>

        @if($entity->dateDebut || $entity->dateFin)
            <span class="tache-deadline" data-toggle="tooltip" title="Période (Début - Fin)">
                <i class="far fa-calendar-alt"></i>
                @if($entity->dateDebut)
                    <span>{{ \Carbon\Carbon::parse($entity->dateDebut)->format('d/m') }}</span>
                @endif
                
                @if($entity->dateDebut && $entity->dateFin)
                    <i class="fas fa-arrow-right mx-1" style="font-size: 0.6rem;"></i>
                @endif
                
                @if($entity->dateFin)
                    <x-deadline-display :value="$entity->dateFin" />
                @endif
            </span>
        @endif

        @if($entity?->chapitre?->duree_en_heure)
            <span class="tache-duree" data-toggle="tooltip" title="{{ ucfirst(__('PkgCompetences::chapitre.duree_en_heure')) }}">
                <i class="fas fa-hourglass-half"></i>
                {{ $entity->chapitre->duree_en_heure }} h
            </span>
        @endif

        @if($entity->note)
            <span class="tache-note" data-toggle="tooltip" title="{{ ucfirst(__('PkgCreationTache::tache.note')) }}">
                <i class="fas fa-star"></i> 
                {{ $entity->note }} / {{ $entity->projet?->total_notes }}
            </span>
        @endif

        <span class="tache-live-coding" data-toggle="tooltip" title="Mode live coding : {{ $entity->is_live_coding_task ? 'Oui' : 'Non' }}">
            <i class="fas {{ $entity->is_live_coding_task ? 'fa-video text-success' : 'fa-video-slash text-secondary' }}"></i> 
        </span>
    </section>

    <footer class="tache-footer flex-column align-items-start" style="gap: 0.1rem;">
        <div class="text-muted"><i class="fas fa-project-diagram mr-1"></i> <strong>Projet :</strong> {{ $entity->projet->titre }}</div>
        @if($entity->mobilisationUa)
            <div class="text-primary mt-1"><i class="fas fa-cubes mr-1"></i> <strong>Mobilisation UA :</strong> {{ $entity->mobilisationUa }}</div>
        @endif
    </footer>
</article>
