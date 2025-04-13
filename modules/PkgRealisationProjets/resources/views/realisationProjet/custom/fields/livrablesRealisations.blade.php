<ul>
    @foreach ($entity->livrablesRealisations as $livrablesRealisation)
        <li>
            <a href="{{$livrablesRealisation->lien}}" target="_blank">
                {{$livrablesRealisation->livrable->titre}}
            <a>
        </li>
    @endforeach
</ul>