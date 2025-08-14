<ul class="livrables-list">
    @php
        $isFormateur = auth()->user()?->hasAnyRole(['formateur', 'admin']);
    @endphp

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

            function iconLivrable($type) {
                return [
                    'code' => 'fas fa-code-branch',
                    'rapport' => 'fas fa-file-alt',
                    'présentation' => 'fas fa-chalkboard',
                    'documentation' => 'fas fa-book',
                    'prototype' => 'fas fa-cube',
                    'vidéo' => 'fas fa-video',
                    'diagramme' => 'fas fa-project-diagram',
                    'test' => 'fas fa-vial',
                    'code-chapitre' => 'fas fa-file-code',
                ][strtolower($type)] ?? 'fas fa-file';
            }
        @endphp
    @endonce

    @php
        $livrablesAttendus = $entity->tache?->livrables ?? collect();
        $lienMicroCompetence = null;
        $microCompetence = null;

        if ($entity->realisationChapitres && $entity->realisationChapitres->isNotEmpty()) {
            $premierChapitre = $entity->realisationChapitres->first();
            $lienMicroCompetence = $premierChapitre->realisationUa->realisationMicroCompetence->lien_livrable ?? null;
            $microCompetence = $premierChapitre->realisationUa->realisationMicroCompetence->microCompetence;

            if ($microCompetence) {
                $livrablesAttendus->push((object) [
                    'id' => 'micro_competence_livrable',
                    'titre' =>  $microCompetence->titre,
                    'natureLivrable' => (object) ['nom' => 'code-chapitre'],
                    'is_affichable_seulement_par_formateur' => false
                ]);
            }
        }

        $realises = $entity->livrablesRealisations
            ->filter(fn($r) => $r->livrable?->taches->pluck('id')->contains($entity->tache_id))
            ?? collect();

        if ($lienMicroCompetence && $microCompetence) {
            $realises->push((object) [
                'livrable' => (object) [
                    'id' => 'micro_competence_livrable',
                    'titre' => $microCompetence->titre,
                    'natureLivrable' => (object) ['nom' => 'code-chapitre'],
                    'is_affichable_seulement_par_formateur' => false
                ],
                'livrable_id' => 'micro_competence_livrable',
                'titre' => null,
                'lien' => $lienMicroCompetence
            ]);
        }

        $livrablesRealises = $realises->pluck('livrable_id')->toArray();
        $livrablesManquants = $livrablesAttendus->filter(
            fn($l) => !in_array($l->id, $livrablesRealises)
        );
    @endphp

    @if ($livrablesAttendus->isEmpty())
        <li class="livrable-card livrable-empty">
            <span>Aucun livrable attendu pour cette tâche.</span>
        </li>
    @else
        {{-- ✅ Livrables Réalisés --}}
        @foreach ($realises as $realisation)
            @php
                $titre1 = normalize($realisation->livrable?->titre ?? '');
                $titre2 = normalize($realisation->titre ?? '');
                $icon = iconLivrable($realisation->livrable?->natureLivrable?->nom ?? '');
            @endphp
            @if (!$realisation->livrable?->is_affichable_seulement_par_formateur || $isFormateur)
                <li class="livrable-card livrable-realise">
                    <i class="{{ $icon }}"></i>
                    <div class="livrable-content">
                        <a href="{{ $realisation->lien }}" target="_blank" class="livrable-titre">
                            {{ $realisation->livrable?->titre ?? '—' }}
                        </a>
                        @if ($titre1 !== $titre2)
                            <div class="livrable-sous-titre">— {{ $realisation->titre }}</div>
                        @endif
                    </div>
                </li>
            @endif
        @endforeach

        {{-- ⚠️ Livrables Manquants --}}
        @foreach ($livrablesManquants as $livrable)
            @php 
                $icon = iconLivrable($livrable?->natureLivrable?->nom ?? '');
            @endphp
            @if (!$livrable->is_affichable_seulement_par_formateur || $isFormateur)
                <li class="livrable-card livrable-manquant">
                    <i class="{{ $icon }}"></i>
                    <div class="livrable-content">
                        <div class="livrable-titre">{{ $livrable->titre }}</div>
                        <div class="livrable-sous-titre">— Livrable non soumis</div>
                    </div>
                </li>
            @endif
        @endforeach
    @endif
</ul>
