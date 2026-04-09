<article class="tache-card">
    <header class="tache-header">
        @php
            $realisationChapitre = $entity->realisationChapitres?->first();
            $tacheRef = $entity->tache;
        @endphp

        <h2 class="tache-titre">
            @if($realisationChapitre?->chapitre?->lien)
                <a href="{{ $realisationChapitre->chapitre->lien }}" target="_blank">{{ $entity->tache }}</a>
            @else
                {{ $entity->tache }}
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
        <div class="text-muted"><i class="fas fa-project-diagram mr-1"></i> <strong>Projet :</strong> {{ $entity->projet_title }}</div>
        @if($tacheRef && $tacheRef->mobilisationUa)
            <div class="text-primary mt-1"><i class="fas fa-cubes mr-1"></i> <strong>Mobilisation UA :</strong> {{ $tacheRef->mobilisationUa }}</div>
        @endif
        <div class="mt-1"><i class="fas fa-user mr-1"></i> <strong>Apprenant :</strong> {{ $entity->nom_prenom_apprenant }}</div>
    </footer>
</article>
