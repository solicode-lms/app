<article class="realisation-ua-cadre">
  <header class="projet-titre">
    <h2 class="mb-2">{{ $entity->realisationUa->uniteApprentissage }}</h2>
  </header>

  <section class="projet-section">

    {{-- ===================== PROTOTYPE ===================== --}}
    @php $proto = $entity->prototype ?? null; @endphp
    @if($proto)
      <div class="item">
        {{-- Ligne 1 : Titre + État --}}
        <div class="title-line">
          <strong title="Prototype">Prototype</strong>
          <x-badge
            :text="$proto->realisationTache?->etatRealisationTache?->nom ?? 'Non défini'"
            :background="$proto->realisationTache?->etatRealisationTache?->sysColor?->hex ?? '#6c757d'"
          />
        </div>

        {{-- Ligne 2 : Note --}}
        <div class="line">
          <span class="label">Note</span>
          <span class="note-badge">
            {{ is_null($proto->note) ? '—' : number_format($proto->note, 2) }} / {{ $proto->bareme ?? 0 }}
          </span>
        </div>

        {{-- Ligne 3 : Commentaire --}}
        @php
          $raw   = optional($proto->realisationTache)->remarques_formateur;
          $plain = trim(strip_tags($raw ?? ''));
          $pid   = 'proto-remarks-'.$proto->id;
        @endphp
        @if($plain !== '')
          <div class="line">
            <span class="label">Commentaire</span>
            <span class="flex-grow-1">
              <small class="muted">{{ \Illuminate\Support\Str::limit($plain, 160) }}</small>
              @if(\Illuminate\Support\Str::length($plain) > 160)
                <a class="ml-1" data-toggle="collapse" href="#{{ $pid }}">Afficher plus</a>
              @endif
              <div id="{{ $pid }}" class="collapse mt-2">
                <div class="commentaire-content border rounded p-2">{!! $raw !!}</div>
              </div>
            </span>
          </div>
        @endif

        {{-- Ligne 4 : Mode --}}
        @if($proto->realisationTache?->is_live_coding)
         
          <span class="label">Mode</span>
            <span>
              
              <section class="tache-infos">
                    <span class="tache-badge-live">
                        <i class="fas fa-code"></i> Live coding
                    </span>
            </section>


            </span>
        @endif
      </div>
    @endif

    {{-- ===================== CHAPITRES ===================== --}}
    @forelse($entity->realisationUa->realisationChapitres->sortBy('chapitre.ordre') as $rc)
      <div class="item">
        {{-- Ligne 1 : Titre + État --}}
        <div class="title-line">
          <strong title="{{ $rc->chapitre?->nom }}">{{ $rc->chapitre?->nom }}</strong>
          <x-badge
            :text="$rc->realisationTache?->etatRealisationTache?->nom ?? 'Non défini'"
            :background="$rc->realisationTache?->etatRealisationTache?->sysColor?->hex ?? '#6c757d'"
          />
        </div>

        {{-- Ligne 2 : Commentaire --}}
        @php
          $raw   = optional($rc->realisationTache)->remarques_formateur;
          $plain = trim(strip_tags($raw ?? ''));
          $cid   = 'rc-remarks-'.$rc->id;
        @endphp
        @if($plain !== '')
          <div class="line">
            <span class="label">Commentaire</span>
            <span class="flex-grow-1">
              <small class="muted">{{ \Illuminate\Support\Str::limit($plain, 160) }}</small>
              @if(\Illuminate\Support\Str::length($plain) > 160)
                <a class="ml-1" data-toggle="collapse" href="#{{ $cid }}">Afficher plus</a>
              @endif
              <div id="{{ $cid }}" class="collapse mt-2">
                <div class="commentaire-content border rounded p-2">{!! $raw !!}</div>
              </div>
            </span>
          </div>
        @endif

        {{-- Ligne 3 : Mode --}}
        @if($rc->realisationTache?->is_live_coding)
          <div class="line">
            <span class="label">Mode</span>
            <span>
              
              <section class="tache-infos">
                    <span class="tache-badge-live">
                        <i class="fas fa-code"></i> Live coding
                    </span>
            </section>


            </span>
          </div>
        @endif
      </div>
    @empty
      <div class="muted"><em>Aucune réalisation de chapitre.</em></div>
    @endforelse

  </section>
</article>
