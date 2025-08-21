{{-- Affichage relation ManyToMany --}}
<ul>
    @foreach ($entity->{$relationName} as $item)
        <li @if(strlen($item) > 30) data-toggle="tooltip" title="{{ $item }}" @endif>
            {{ \Illuminate\Support\Str::limit($item, 30) }}
        </li>
    @endforeach
</ul>