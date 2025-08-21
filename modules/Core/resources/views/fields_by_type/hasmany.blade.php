{{-- Affichage relation HasMany --}}
<ul>
    @foreach ($entity->{$relationName} as $item)
        <li>{{ $item }}</li>
    @endforeach
</ul>