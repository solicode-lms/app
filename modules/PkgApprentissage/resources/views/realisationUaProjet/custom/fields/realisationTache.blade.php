<article class="realisation-ua-cadre">
  <header class="projet-titre">
    <h2 class="mb-2">{{ $entity->realisationUa->uniteApprentissage }}</h2>
  </header>

  <section class="projet-section">

    @php
      $rawProjet = $entity->remarque_formateur;
      $plainProjet = trim(strip_tags($rawProjet ?? ''));

      // Logique récupération Tâches via Mobilisation
      $ua = $entity->realisationUa->uniteApprentissage;
      $realisationTacheSource = $entity->realisationTache;
      $realisationProjet = $realisationTacheSource ? $realisationTacheSource->realisationProjet : null;
      $projet = $realisationProjet ? ($realisationProjet->affectationProjet ? $realisationProjet->affectationProjet->projet : null) : null;

      $taches = collect();
      if ($projet && $ua) {
        $mobilisation = $ua->mobilisationUas->where('projet_id', $projet->id)->first();
        if ($mobilisation) {
          $taches = $mobilisation->taches->sortBy('ordre');
        }
      }
    @endphp

    @if(!empty($rawProjet) && $plainProjet != '')
      <section class="tache-comment mb-3" data-toggle="tooltip" title="Commentaire formateur (Projet)">
        <i class="fas fa-user-tie text-info"></i>
        {!! $rawProjet !!}
      </section>
    @endif

    {{-- ===================== PROTOTYPE ===================== --}}
    @php $proto = $entity->prototype ?? null; @endphp

    @if($proto)
      <div class="item">
        {{-- Ligne 1 : Titre + État --}}
        <div class="title-line">
          <strong
            title="{{ $proto->realisationTache?->tache?->titre ?? 'Prototype' }}">{{ $proto->realisationTache?->tache?->titre ?? 'Prototype' }}</strong>
          <x-badge :text="$proto->realisationTache?->etatRealisationTache?->nom ?? 'Non défini'"
            :background="$proto->realisationTache?->etatRealisationTache?->sysColor?->hex ?? '#6c757d'" />
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
          $raw = optional($proto->realisationTache)->remarques_formateur;
          $plain = trim(strip_tags($raw ?? ''));
          $pid = 'proto-remarks-' . $proto->id;
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
          <x-badge :text="$rc->realisationTache?->etatRealisationTache?->nom ?? 'Non défini'"
            :background="$rc->realisationTache?->etatRealisationTache?->sysColor?->hex ?? '#6c757d'" />
        </div>

        {{-- Ligne 2 : Commentaire --}}
        @php
          $raw = optional($rc->realisationTache)->remarques_formateur;
          $plain = trim(strip_tags($raw ?? ''));
          $cid = 'rc-remarks-' . $rc->id;
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

    {{-- ===================== TACHES (PROJET) ===================== --}}
    @if($taches->isNotEmpty())
      @foreach($taches as $tache)
        @php
          $rt = $realisationProjet ? $realisationProjet->realisationTaches->where('tache_id', $tache->id)->first() : null;
        @endphp
        <div class="item">
          {{-- Ligne 1 : Titre + État --}}
          <div class="title-line">
            <strong title="{{ $tache->titre }}">{{ $tache->titre }}</strong>
            <x-badge :text="$rt?->etatRealisationTache?->nom ?? 'Non défini'"
              :background="$rt?->etatRealisationTache?->sysColor?->hex ?? '#6c757d'" />
          </div>

          {{-- Ligne 2 : Commentaire --}}
          @php
            $raw = optional($rt)->remarques_formateur;
            $plain = trim(strip_tags($raw ?? ''));
            $tid = 'task-remarks-' . $tache->id;
          @endphp
          @if($plain !== '')
            <div class="line">
              <span class="label">Commentaire</span>
              <span class="flex-grow-1">
                <small class="muted">{{ \Illuminate\Support\Str::limit($plain, 160) }}</small>
                @if(\Illuminate\Support\Str::length($plain) > 160)
                  <a class="ml-1" data-toggle="collapse" href="#{{ $tid }}">Afficher plus</a>
                @endif
                <div id="{{ $tid }}" class="collapse mt-2">
                  <div class="commentaire-content border rounded p-2">{!! $raw !!}</div>
                </div>
              </span>
            </div>
          @endif

          {{-- Ligne 3 : Mode --}}
          @if(optional($rt)->is_live_coding)
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
      @endforeach
    @endif

  </section>
</article>