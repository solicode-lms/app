

 <article class="projet-card">
    <header class="projet-titre">
        <h2>{{ $entity->realisationTache->tache }}</h2>
        <p>
            {!! $entity->realisationTache->tache->description !!}
        </p>
    </header>

    {{-- <section class="projet-section">
        <h3>section titre</h3>
        <ul class="projet-affectations">
           
                <li>
                    <strong>aa</strong>  
                    (<small>bb </small>)
                </li>
           
        </ul>
    </section> --}}
    <footer class="projet-formateur">
        <strong>Évaluateur : </strong> {{ $entity->evaluateur }} 
    </footer>
</article>
