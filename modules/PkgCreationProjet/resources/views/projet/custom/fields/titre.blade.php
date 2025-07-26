<article class="projet-card">
    <header class="projet-titre">
        <h2>{{ $entity->titre }}</h2>
    </header>
    <section class="projet-section">
        <h3>Session de formation</h3>
        <ul class="projet-ressources">
            <li> {{ $entity->sessionFormation->titre }}</li>
        </ul>
    </section>
    <section class="projet-section">
        <h3>Affectations</h3>
        <ul class="projet-affectations">
            @foreach ($entity->affectationProjets as $affectationProjet)
                <li>
                    <strong>Groupe :</strong> {{ $affectationProjet->groupe->code }}
                    (<small>Du {{ $affectationProjet->date_debut }} au {{ $affectationProjet->date_fin }}</small>)
                </li>
            @endforeach
        </ul>
    </section>

    <section class="projet-section">
        <h3>Ressources</h3>
        <ul class="projet-ressources">
            @foreach ($entity->resources as $resource)
                <li>{{ $resource->nom }}</li>
            @endforeach
        </ul>
    </section>

    <footer class="projet-formateur">
        <strong>Formateur :</strong> {{ $entity->formateur }}
    </footer>
</article>
