<ul>
        @php
            $isFormateur = auth()->user()?->hasAnyRole(['formateur', 'admin']);
        @endphp
        @foreach ($entity->getRealisationLivrable() as $realisationLivrable)
            @if(!$realisationLivrable->livrable->is_affichable_seulement_par_formateur  || $isFormateur)
            <li><a href="{{ $realisationLivrable->lien }}" target="_blank">{{ $realisationLivrable->livrable->titre }}</a></li>
            @else
            <li>{{ $realisationLivrable->livrable->titre }}</li>
            @endif
        @endforeach
</ul>