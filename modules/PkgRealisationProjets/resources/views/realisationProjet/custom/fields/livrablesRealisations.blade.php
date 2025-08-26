<ul class="livrables-list">
    @once
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
            $titre1 = normalize($livrablesRealisation->livrable?->titre ?? '');
            $titre2 = normalize($livrablesRealisation->titre ?? 'Livrable');
            $lien = $livrablesRealisation->lien;
            $icon = iconLivrable($livrable?->natureLivrable?->nom ?? '');
        @endphp
        <li class="livrable-card livrable-realise">
                    <i class="{{ $icon }}"></i>
                    <div class="livrable-content">
                        <a href="{{ $lien }}" target="_blank" class="livrable-titre">
                            {{ $titre1 ?? '—' }}
                        </a>
                        @if ($titre1 !== $titre2)
                            <div class="livrable-sous-titre">— {{ $titre2 }}</div>
                        @endif
                    </div>
        </li>
    @endforeach

    {{-- ⚠️ Livrables Manquants --}}
    @foreach ($livrablesManquants as $livrable)
        @php
            $titre = $livrable->titre;
            $icon = iconLivrable($livrable?->natureLivrable?->nom ?? '');
        @endphp
        <li class="livrable-card livrable-manquant">
                    <i class="{{ $icon }}"></i>
                    <div class="livrable-content">
                        <div class="livrable-titre">{{ $titre }}</div>
                        <div class="livrable-sous-titre">— Livrable non soumis</div>
                    </div>
        </li>
    @endforeach
</ul>
