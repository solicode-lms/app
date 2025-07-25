
 <article class="projet-card">
    <section class="projet-section">
       <h3>Unité d'apprentissages</h3>
       <ul>
        @foreach ($entity->alignementUas as $alignementUa)
            <li>{{$alignementUa}} </li>
        @endforeach
        </ul>
    </section>
</article>