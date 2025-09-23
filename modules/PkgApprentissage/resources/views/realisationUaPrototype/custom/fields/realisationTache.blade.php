<article class="projet-card">
    <header class="projet-titre">
        <h2>{{  $entity->realisationUa->uniteApprentissage }}</h2>
    </header>
    <section class="projet-section">
 
<div class="projet-section mt-2">
    <ul class="list-unstyled m-0">

@forelse($entity->realisationUa->realisationChapitres->sortBy('chapitre.ordre') as $rc)
            
        
        
        
<li class="projet-item py-2">
    {{-- Ligne pleine pour l’état (occupe 100%) --}}
   

    {{-- Contenu principal --}}
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

            @if($plain !== '')
                @php
                    $excerpt    = \Illuminate\Support\Str::limit($plain, 160);
                    $collapseId = 'rc-remarks-'.$rc->id;
                @endphp

                <small class="text-muted d-block mt-1">
                    {{ $excerpt }}
                    @if(\Illuminate\Support\Str::length($plain) > 160)
                        <a class="ml-1" data-toggle="collapse" href="#{{ $collapseId }}">
                            Afficher plus
                        </a>
                    @endif
                </small>

              
                <div id="{{ $collapseId }}" class="collapse mt-1">
                    <div class="commentaire-content border rounded p-2">
                        {!! $raw !!}
                    </div>
                </div>
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
            <li><em>Aucune réalisation de chapitre.</em></li>
        @endforelse
    </ul>
</div>






    </section>
</article>

