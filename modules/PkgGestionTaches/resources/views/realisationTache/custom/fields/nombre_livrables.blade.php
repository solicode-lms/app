<ul>
        @php
            $isFormateur = auth()->user()?->hasAnyRole(['formateur', 'admin']);
        @endphp
        @php
            if (!function_exists('normalize')) {
                function normalize($string) {
                    $string = strtolower($string);
                    $string = preg_replace('/\s+/', '', $string);
                    $string = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $string);
                    $string = preg_replace('/[^a-z0-9]/', '', $string);
                    return $string;
                }
            }
        @endphp
        @foreach ($entity->getRealisationLivrable() as $realisationLivrable)
            @php
                        $titre1 = normalize($realisationLivrable->livrable->titre);
                        $titre2 = normalize($realisationLivrable->titre);
            @endphp
            @if(!$realisationLivrable->livrable->is_affichable_seulement_par_formateur  || $isFormateur)
            <li>
                <a href="{{ $realisationLivrable->lien }}" target="_blank" class="d-block">
                    {{ $realisationLivrable->livrable->titre }}
                   
                    @if ($titre1 !== $titre2)
                    <span class="d-block text-muted small">
                        — {{ $realisationLivrable->titre }}
                    </span>
                    @endif
                </a>
            </li>
            @else
            <li class="d-block">

                {{ $realisationLivrable->livrable->titre }}
                @if ($titre1 !== $titre2)
                    <span class="d-block text-muted small">
                        — {{ $realisationLivrable->titre }}
                    </span>
                @endif
            </li>
            @endif
        @endforeach
</ul>