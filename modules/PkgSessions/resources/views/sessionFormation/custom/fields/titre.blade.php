<article class="projet-card">
    <header class="projet-titre">
        <h2>{{ $entity->titre }}</h2>
    </header>

    <section class="projet-section">
        <h3>Période</h3>
        <ul class="projet-affectations">
                <li>
                    Du {{ $entity->date_debut  }} au {{ $entity->date_fin    }}
                    <small>Semaines : 41–42</small>
                </li>
                @if(!empty($entity->jour_feries_vacances))
                    <li>
                        Jours fériés / Vacances : {!! $entity->jour_feries_vacances !!}
                    </li>
                @endif
        </ul>
    </section>

    <footer class="projet-formateur">
        <strong>aa :</strong> bbb
    </footer>
</article>
