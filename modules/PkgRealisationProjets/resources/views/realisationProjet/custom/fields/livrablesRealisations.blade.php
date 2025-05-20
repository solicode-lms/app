<ul class="livrable">
    @once
        @php
            if (!function_exists('iconLivrable')) {
                function iconLivrable($type) {
                    return [
                        'code' => 'fas fa-code',
                        'rapport' => 'fas fa-file-alt',
                        'présentation' => 'fas fa-chalkboard',
                        'documentation' => 'fas fa-book',
                        'prototype' => 'fas fa-cube',
                        'vidéo' => 'fas fa-video',
                        'diagramme' => 'fas fa-project-diagram',
                        'test' => 'fas fa-vial',
                    ][strtolower($type)] ?? 'fas fa-file';
                }
            }
        @endphp
    @endonce

    @php
        $livrablesAttendus = $entity->affectationProjet->projet?->livrables ?? collect();
        $livrablesRealisesIds = $entity->livrablesRealisations->pluck('livrable_id')->toArray();
        $livrablesManquants = $livrablesAttendus->filter(function ($livrable) use ($livrablesRealisesIds) {
            return !in_array($livrable->id, $livrablesRealisesIds);
        });
    @endphp

    @foreach ($entity->livrablesRealisations as $livrablesRealisation)
        @php
            $livrable = $livrablesRealisation->livrable;
            $titre = $livrable?->titre ?? 'Livrable';
            $lien = $livrablesRealisation->lien;
            $icon = iconLivrable($livrable?->natureLivrable?->nom ?? '');
        @endphp
        <li class="text-truncate">
            @if ($lien)
                <a href="{{ $lien }}" target="_blank" class="d-block text-truncate">
                    <i class="{{ $icon }}"></i> {{ $titre }}
                </a>
            @else
                <span class="text-muted"><i class="{{ $icon }}"></i> {{ $titre }} — (aucun lien)</span>
            @endif
        </li>
    @endforeach

    {{-- ⚠️ Livrables Manquants --}}
    @foreach ($livrablesManquants as $livrable)
        @php
            $titre = $livrable->titre;
            $icon = iconLivrable($livrable?->natureLivrable?->nom ?? '');
        @endphp
        <li class="text-danger livrable-manquant text-truncate">
            <i class="{{ $icon }}"></i> {{ $titre }}
            <span class="d-block text-muted small" title="Non encore déposé">— Livrable non soumis</span>
        </li>
    @endforeach
</ul>
