<article class="tache-card">
    <header class="tache-header">
        {{-- Titre de la tâche avec lien si chapitre dispo --}}
        @php
            $realisationChapitre = $entity->realisationChapitres?->first();
        @endphp

        <h2 class="tache-titre mb-1">
            @if($realisationChapitre?->chapitre?->lien)
                <a href="{{ $realisationChapitre->chapitre->lien }}" target="_blank">
                    {{ $entity->tache }}
                </a>
            @else
                {{ $entity->tache }}
            @endif
        </h2>

        {{-- Projet associé --}}
        <small class="tache-projet text-muted">{{ $entity->projet_title }}</small>
    </header>

    <section class="tache-section">
        {{-- Badge Live Coding --}}
        @if($entity->is_live_coding)
            <span class="tache-badge-live" 
                  title="Cette tâche a été validée en live coding" 
                  data-toggle="tooltip">
                Live coding
            </span>
        @endif

        {{-- Deadline --}}
        @if($entity->deadline)
            <div class="mt-1">
                <small class="tache-deadline">
                    ⏳ <x-deadline-display :value="$realisationTache->deadline" />
                </small>
            </div>
        @endif
    </section>

    <footer class="tache-footer">
        <strong>Apprenant :</strong> {{ $entity->nom_prenom_apprenant }}
    </footer>
</article>
