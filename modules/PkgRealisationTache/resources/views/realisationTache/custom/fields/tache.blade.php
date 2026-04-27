<article class="tache-card">
    <header class="tache-header">
        @php
            $realisationChapitre = $entity->realisationChapitres?->first();
            $tacheRef = $entity->tache;
            $affectationId = $entity->realisationProjet?->affectation_projet_id;
            $apprenantId   = $entity->realisationProjet?->apprenant_id;
            $chapitreUrl   = $realisationChapitre?->chapitre?->lien;

            // Lien filtre Projet seul
            $projetFilterUrl = $affectationId
                ? route('realisationTaches.index', [
                    'filter.realisationTache.RealisationProjet.Affectation_projet_id' => $affectationId,
                  ])
                : null;

            // Lien filtre Projet + Tâche
            $tacheFilterUrl = $affectationId && $entity->tache_id
                ? route('realisationTaches.index', [
                    'filter.realisationTache.RealisationProjet.Affectation_projet_id' => $affectationId,
                    'filter.realisationTache.tache_id'                                => $entity->tache_id,
                  ])
                : null;

            // Lien filtre Projet + Apprenant
            $apprenantFilterUrl = $affectationId && $apprenantId
                ? route('realisationTaches.index', [
                    'filter.realisationTache.RealisationProjet.Affectation_projet_id' => $affectationId,
                    'filter.realisationTache.RealisationProjet.Apprenant_id'          => $apprenantId,
                  ])
                : null;
        @endphp

        <h2 class="tache-titre d-flex align-items-center gap-1">
            {{-- Titre = lien filtre projet+tâche --}}
            @if($tacheFilterUrl)
                <a href="{{ $tacheFilterUrl }}" class="context-state" data-toggle="tooltip" title="Filtrer par cette tâche">
                    {{ $entity->tache }}
                </a>
            @else
                {{ $entity->tache }}
            @endif

            {{-- Icône séparée pour consulter le chapitre --}}
            @if($chapitreUrl)
                <a href="{{ $chapitreUrl }}" target="_blank"
                   class="ml-1 text-info"
                   data-toggle="tooltip"
                   title="Consulter le chapitre"
                   style="font-size: 0.8rem; flex-shrink: 0;">
                    <i class="fas fa-external-link-alt"></i>
                </a>
            @endif
        </h2>

        @if($tacheRef && $tacheRef->phaseEvaluation)
            <small class="tache-projet text-muted">
                <i class="fas fa-layer-group"></i>
                {!!ucfirst(__('PkgCompetences::phaseEvaluation.singular'))!!} :
                <strong>{{ $tacheRef->phaseEvaluation }}</strong>
            </small>
        @endif
    </header>

    <section class="tache-infos mt-2 mb-2">
        @if($entity->is_live_coding)
            <span class="tache-live-coding" data-toggle="tooltip" title="Mode live coding : Oui">
                <i class="fas fa-video text-success"></i> Live coding
            </span>
        @endif

        @if($tacheRef && ($tacheRef->dateDebut || $tacheRef->dateFin))
            <span class="tache-deadline" data-toggle="tooltip" title="Période (Début - Fin)">
                <i class="far fa-calendar-alt"></i> 
                @if($tacheRef->dateDebut)
                    <span>{{ \Carbon\Carbon::parse($tacheRef->dateDebut)->format('d/m') }}</span>
                @endif
                
                @if($tacheRef->dateDebut && $tacheRef->dateFin)
                    <i class="fas fa-arrow-right mx-1" style="font-size: 0.6rem;"></i>
                @endif
                
                @if($tacheRef->dateFin)
                    <x-deadline-display :value="$tacheRef->dateFin" />
                @endif
            </span>
        @endif
    </section>

    @if( !empty($entity->remarques_formateur) && trim(strip_tags($entity->remarques_formateur))  != '' )
    <section class="tache-comment" data-toggle="tooltip" title="Commentaire formateur">
        <i class="fas fa-user-tie text-info"></i> 
        {!! $entity->remarques_formateur !!}
    </section>
    @endif

    @if( !empty($entity->remarques_apprenant) && trim(strip_tags($entity->remarques_apprenant))  != '' )
    <section class="tache-comment" data-toggle="tooltip" title="Commentaire apprenant">
      <i class="fas fa-user text-info"></i> 
      {!! $entity->remarques_apprenant !!}
    </section>
    @endif

    <footer class="tache-footer flex-column align-items-start" style="gap: 0.1rem;">
        {{-- Projet --}}
        <div class="text-muted">
            <i class="fas fa-project-diagram mr-1"></i>
            <strong>Projet :</strong>
            @if($projetFilterUrl)
                <a href="{{ $projetFilterUrl }}" class="text-muted context-state" data-toggle="tooltip" title="Filtrer par ce projet">
                    {{ $entity->projet_title }}
                </a>
            @else
                {{ $entity->projet_title }}
            @endif
        </div>

        {{-- Mobilisation UA --}}
        @if($tacheRef && $tacheRef->mobilisationUa)
            <div class="text-primary mt-1"><i class="fas fa-cubes mr-1"></i> <strong>Mobilisation UA :</strong> {{ $tacheRef->mobilisationUa }}</div>
        @endif

        {{-- Apprenant = lien filtre projet+apprenant --}}
        <div class="mt-1">
            <i class="fas fa-user mr-1"></i>
            <strong>Apprenant :</strong>
            @if($apprenantFilterUrl)
                <a href="{{ $apprenantFilterUrl }}" class="context-state" data-toggle="tooltip" title="Filtrer par cet apprenant et ce projet">
                    {{ $entity->nom_prenom_apprenant }}
                </a>
            @else
                {{ $entity->nom_prenom_apprenant }}
            @endif
        </div>

        {{-- Labels --}}
        @if($entity->labelProjets && $entity->labelProjets->count() > 0)
            <div class="mt-2 d-flex flex-wrap gap-1">
                @foreach($entity->labelProjets as $label)
                    <x-badge 
                        :text="$label->nom" 
                        :background="$label->sysColor?->hex ?? '#6c757d'" 
                    />
                @endforeach
            </div>
        @endif
    </footer>
</article>
