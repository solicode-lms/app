<article class="projet-card">
    <section class="projet-section">
        <ul class="projet-taches">
            @foreach ($entity->taches as $tache)
                <li  class="text-truncate" ><i class="fas fa-check me-2"></i> {{ $tache }}</li>
            @endforeach
        </ul>
    </section>
</article>