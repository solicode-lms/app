<article class="projet-card">
    <header class="projet-titre">
        <h2>{{ $entity->titre }}</h2>
        <small>Session : {{ $entity->sessionFormation->titre }}</small>
    </header>

    <section class="projet-section">
        <h3><i class="fas fa-users"></i> Affectations</h3>
        <ul class="projet-affectations">
            @foreach ($entity->affectationProjets as $affectationProjet)
                <li>
                    <i class="fas fa-user-friends"></i>
                    <strong>{{ $affectationProjet->groupe->code }}</strong> 
                    <small>({{ $affectationProjet->date_debut }} → {{ $affectationProjet->date_fin }})</small>
                </li>
            @endforeach
        </ul>
    </section>

    <section class="projet-section">
        <h3><i class="fas fa-book"></i> Ressources</h3>
        <ul class="projet-ressources">
            @foreach ($entity->resources as $resource)
                <li><i class="fas fa-link"></i>{{ $resource->nom }}</li>
            @endforeach
        </ul>
    </section>

    <footer class="projet-formateur">
        <i class="fas fa-user-tie"></i>
        <strong>Formateur :</strong> {{ $entity->formateur }}
    </footer>
</article>
