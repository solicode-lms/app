<article class="projet-card">
  <header class="projet-titre">
    <h2>{{ $entity->realisationUa->uniteApprentissage }}</h2>
  </header>

  <section class="projet-section">

    {{-- Prototype (style chapitre) --}}
    @php $proto = $entity->prototype ?? null; @endphp
    @if($proto)
      <div class="projet-section mt-2">
        <ul class="list-unstyled m-0">
          <li class="projet-item py-2">
            {{-- Ligne pleine pour l’état --}}
            <div class="etat-line w-100 mb-2">
              <x-badge
                :text="$proto->realisationTache?->etatRealisationTache?->nom ?? 'Non défini'"
                :background="$proto->realisationTache?->etatRealisationTache?->sysColor?->hex ?? '#6c757d'"
              />
            </div>

            {{-- Contenu principal --}}
            <div class="d-flex align-items-start">
              <div class="flex-grow-1 min-w-0">
                <div class="d-flex align-items-center gap-2">
                  <strong class="text-truncate" title="Prototype">
                    Prototype
                  </strong>

                  @php
                    $raw   = optional($proto->realisationTache)->remarques_formateur;
                    $plain = trim(strip_tags($raw ?? ''));
                  @endphp

                  @if($plain !== '')
                    <i class="fas fa-comment-dots text-muted ml-1"
                       data-toggle="tooltip"
                       title="Commentaire formateur"></i>
                  @endif
                </div>

                {{-- Commentaire formateur --}}
                @if($plain !== '')
                  @php
                    $excerpt    = \Illuminate\Support\Str::limit($plain, 160);
                    $collapseId = 'proto-remarks-'.$proto->id;
                  @endphp

                  <small class="text-muted d-block mt-1">
                    {{ $excerpt }}
                    @if(\Illuminate\Support\Str::length($plain) > 160)
                      <a class="ml-1" data-toggle="collapse" href="#{{ $collapseId }}">Afficher plus</a>
                    @endif
                  </small>

                  <div id="{{ $collapseId }}" class="collapse mt-1">
                    <div class="commentaire-content border rounded p-2">
                      {!! $raw !!}
                    </div>
                  </div>
                @endif

                {{-- Note --}}
                <section class="tache-infos mt-1">
                  <span class="badge badge-info">
                    Note :
                    {{ is_null($proto->note) ? '—' : number_format($proto->note, 2) }}
                    / {{ $proto->bareme ?? 0 }}
                  </span>
                </section>

                {{-- Live coding ? --}}
                @if($proto->realisationTache?->is_live_coding)
                  <section class="tache-infos mt-1">
                    <span class="tache-badge-live">
                      <i class="fas fa-code"></i> Live coding
                    </span>
                  </section>
                @endif
              </div>
            </div>
          </li>
        </ul>
      </div>
    @endif

    {{-- États des chapitres --}}
    <div class="projet-section mt-3">
      <ul class="list-unstyled m-0">
        @forelse($entity->realisationUa->realisationChapitres->sortBy('chapitre.ordre') as $rc)
          <li class="projet-item py-2">
            <div class="etat-line w-100 mb-2">
              <x-badge
                :text="$rc->realisationTache?->etatRealisationTache?->nom ?? 'Non défini'"
                :background="$rc->realisationTache?->etatRealisationTache?->sysColor?->hex ?? '#6c757d'"
              />
            </div>
            <div class="d-flex align-items-start">
              <div class="flex-grow-1 min-w-0">
                <div class="d-flex align-items-center gap-2">
                  <strong class="text-truncate" title="{{ $rc->chapitre?->nom }}">
                    {{ $rc->chapitre?->nom }}
                  </strong>
                  @php
                    $raw   = optional($rc->realisationTache)->remarques_formateur;
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
                      <a class="ml-1" data-toggle="collapse" href="#{{ $collapseId }}">Afficher plus</a>
                    @endif
                  </small>
                  <div id="{{ $collapseId }}" class="collapse mt-1">
                    <div class="commentaire-content border rounded p-2">
                      {!! $raw !!}
                    </div>
                  </div>
                @endif

                @if($rc->realisationTache?->is_live_coding)
                  <section class="tache-infos mt-1">
                    <span class="tache-badge-live">
                      <i class="fas fa-code"></i> Live coding
                    </span>
                  </section>
                @endif
              </div>
            </div>
          </li>
        @empty
          <li><em>Aucune réalisation de chapitre.</em></li>
        @endforelse
      </ul>
    </div>
  </section>
</article>
