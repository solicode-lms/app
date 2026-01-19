<article class="projet-card">
    <header class="projet-titre">
        <h2>{{  $entity->realisationUa->uniteApprentissage }}</h2>
    </header>
    <section class="projet-section">

        @php
            $rawPrototype   = $entity->remarque_formateur;
            $plainPrototype = trim(strip_tags($rawPrototype ?? ''));
        @endphp

        @if( !empty($rawPrototype) && $plainPrototype != '' )
        <section class="tache-comment mb-3" data-toggle="tooltip" title="Commentaire formateur (Prototype)">
            <i class="fas fa-user-tie text-info"></i>
            {!! $rawPrototype !!}
        </section>
        @endif
 
@php
    // 1. Récupérer l'UA
    $ua = $entity->realisationUa->uniteApprentissage;
    
    // 2. Récupérer le Projet via la RealisationTache liée au prototype
    $realisationTacheSource = $entity->realisationTache; 
    $realisationProjet = $realisationTacheSource ? $realisationTacheSource->realisationProjet : null;
    $projet = $realisationProjet ? ($realisationProjet->affectationProjet ? $realisationProjet->affectationProjet->projet : null) : null;
    
    $taches = collect();

    if ($projet && $ua) {
        // 3. Trouver la bonne mobilisation pour ce projet
        $mobilisation = $ua->mobilisationUas->where('projet_id', $projet->id)->first();
        
        if ($mobilisation) {
            $taches = $mobilisation->taches->sortBy('ordre');
        }
    }
@endphp

<div class="projet-section mt-2">
    <!-- Section: Par Chapitres -->
    <ul class="list-unstyled m-0 mb-4">
        @forelse($entity->realisationUa->realisationChapitres->sortBy('chapitre.ordre') as $rc)
            <li class="projet-item py-2">
                <div class="d-flex align-items-start">
                    <div class="flex-grow-1 min-w-0">
                        <div class="d-flex align-items-center gap-2">
                            <strong class="text-truncate" title="{{ $rc->chapitre?->nom }}">
                                {{ $rc->chapitre?->nom }}
                            </strong>

                            @php
                                $raw   = optional($rc->realisationTache)->remarques_formateur;
                                $is_live_coding = optional($rc->realisationTache)->is_live_coding;
                                $plain = trim(strip_tags($raw ?? ''));
                            @endphp

                            @if($plain !== '')
                                <i class="fas fa-comment-dots text-muted ml-1"
                                   data-toggle="tooltip"
                                   title="Commentaire formateur"></i>
                            @endif
                        </div>

                        @if( !empty($raw) && $plain != '' )
                            <section class="tache-comment" data-toggle="tooltip" title="Commentaire formateur">
                                <i class="fas fa-user-tie text-info"></i>
                                {!! $raw !!}
                            </section>
                        @endif

                        @if($rc->realisationTache?->is_live_coding)
                            <section class="tache-infos">
                                <span class="tache-badge-live">
                                    <i class="fas fa-code"></i> Live coding
                                </span>
                            </section>
                        @endif
                    </div>
                </div>
                <div class="etat-line w-100 mb-2">
                    <x-badge
                        :text="$rc->realisationTache?->etatRealisationTache?->nom ?? 'Non défini'"
                        :background="$rc->realisationTache?->etatRealisationTache?->sysColor?->hex ?? '#6c757d'"
                    />
                </div>
            </li>
        @empty
            <li><small class="text-muted">Aucune réalisation de chapitre.</small></li>
        @endforelse
    </ul>

    <!-- Section: Par Tâches (Mobilisation Project) -->
    
    <ul class="list-unstyled m-0">
    @forelse($taches as $tache)
        @php
            $rt = $realisationProjet ? $realisationProjet->realisationTaches->where('tache_id', $tache->id)->first() : null;
        @endphp
        
        <li class="projet-item py-2">
            <div class="d-flex align-items-start">
                <div class="flex-grow-1 min-w-0">
                    <div class="d-flex align-items-center gap-2">
                        <strong class="text-truncate" title="{{ $tache->titre }}">
                            {{ $tache->titre }}
                        </strong>

                        @php
                            $raw   = optional($rt)->remarques_formateur;
                            $plain = trim(strip_tags($raw ?? ''));
                        @endphp

                        @if($plain !== '')
                            <i class="fas fa-comment-dots text-muted ml-1"
                               data-toggle="tooltip"
                               title="Commentaire formateur"></i>
                        @endif
                    </div>

                    @if(!empty($raw) && $plain != '')
                        <section class="tache-comment" data-toggle="tooltip" title="Commentaire formateur">
                            <i class="fas fa-user-tie text-info"></i>
                            {!! $raw !!}
                        </section>
                    @endif
                    
                    @if(optional($rt)->is_live_coding)
                        <section class="tache-infos">
                            <span class="tache-badge-live">
                                <i class="fas fa-code"></i> Live coding
                            </span>
                        </section>
                    @endif
                </div>
            </div>
             <div class="etat-line w-100 mb-2">
                <x-badge
                    :text="$rt?->etatRealisationTache?->nom ?? 'Non défini'"
                    :background="$rt?->etatRealisationTache?->sysColor?->hex ?? '#6c757d'"
                />
            </div>
        </li>
    @empty
        <li><small class="text-muted">Aucune tâche mobilisée pour ce projet.</small></li>
    @endforelse
    </ul>
</div>






    </section>
</article>

