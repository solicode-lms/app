<article class="tache-card">
    <header class="tache-header">
        @php
            $realisationChapitre = $entity->realisationChapitres?->first();
        @endphp

        <h2 class="tache-titre">
            @if($realisationChapitre?->chapitre?->lien)
                <a href="{{ $realisationChapitre->chapitre->lien }}" target="_blank">{{ $entity->tache }}</a>
            @else
                {{ $entity->tache }}
            @endif
        </h2>
        <small class="tache-projet">{{ $entity->projet_title }}</small>
    </header>

    <section class="tache-infos">
        @if($entity->is_live_coding)
            <span class="tache-badge-live">
                <i class="fas fa-code"></i> Live coding
            </span>
        @endif

        @if($entity->deadline)
            <span class="tache-deadline">
                <i class="far fa-clock"></i> 
                <x-deadline-display :value="$entity->deadline" />
            </span>
        @endif
    </section>

    <footer class="tache-footer">
        <i class="fas fa-user"></i>
        <strong>Apprenant :</strong> {{ $entity->nom_prenom_apprenant }}
    </footer>
</article>
