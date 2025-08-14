<article class="projet-card">
    <section class="projet-section">
        <ul class="projet-taches two-columns compact">
            @foreach ($entity->taches as $tache)
                <li title="{{ $tache }}">
                    <i class="fas fa-circle"></i> {{ $tache }}
                </li>
            @endforeach
        </ul>
    </section>
</article>
