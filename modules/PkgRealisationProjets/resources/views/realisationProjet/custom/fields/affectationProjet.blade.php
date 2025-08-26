<article class="tache-card">
    <header class="tache-header">
        <h2 class="tache-titre">
            {{  $entity->affectationProjet }}
        </h2>
        <small class="tache-projet"></small>
    </header>

    <section class="tache-infos">
        @if($entity->deadline)
            <span class="tache-deadline">
                <i class="far fa-clock"></i> 
                <x-deadline-display :value="$entity->affectationProjet->date_fin" />
            </span>
        @endif
    </section>

    <footer class="tache-footer">
        <i class="fas fa-user"></i>
        <strong>Apprenant :</strong> {{ $entity->apprenant }}
    </footer>
</article>

