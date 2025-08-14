<ul class="livrable ">
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
       
        // Si lien_livrable dans realisationMicroCompetence, on l’ajoute comme livrable spécial
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
       


         // ⚡ Optimisation : utiliser la relation eager loaded
        $realises = $entity->livrablesRealisations ->filter(fn($r) => $r->livrable?->taches->pluck('id')->contains($entity->tache_id))
         ?? collect();

         // 4️⃣ Ajouter le livrable spécial comme "réalisé" s’il existe
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
        <li class="text-muted text-truncate">Aucun livrable attendu pour cette tâche.</li>
    @else
        {{-- 🌟 Livrables Réalisés --}}
        @foreach ($realises as $realisation)
            @php
                $titre1 = normalize($realisation->livrable?->titre ?? '');
                $titre2 = normalize($realisation->titre ?? '');
                $icon = iconLivrable($realisation->livrable?->natureLivrable?->nom ?? '');
                $lienMicroCompetence = $entity->realisationMicroCompetence->lien_livrable ?? null;
            @endphp
            @if (optional($realisation->livrable)->is_affichable_seulement_par_formateur !== true || $isFormateur)
                <li class="livrable-realise"> 
                    <a href="{{ $realisation->lien }}" target="_blank" class="d-block text-truncate">

                         <i class="{{ $icon }}"></i>
                        {{ $realisation->livrable?->titre ?? '—' }}
                        @if ($titre1 !== $titre2)
                            <span class="d-block text-muted small">— {{ $realisation->titre }}</span>
                        @endif
                    </a>
                </li>
            @else
                <li class="livrable-realise d-block text-truncate"> 
                     

                        <i class="{{ $icon }}"></i>
                        {{ $realisation->livrable?->titre ?? '—' }}
                        @if ($titre1 !== $titre2)
                            <span class="d-block text-muted small">— {{ $realisation->titre }}</span>
                        @endif
                    
                </li>
            @endif


        @endforeach

        {{-- ⚠️ Livrables Manquants --}}
        @foreach ($livrablesManquants as $livrable)
            @php 
                $icon = iconLivrable($livrable?->natureLivrable?->nom ?? '');
                // 🔍 Chercher lien livrable depuis la micro-compétence
                $lienMicroCompetence = null;
                if ($entity->realisationChapitres && $entity->realisationChapitres->isNotEmpty()) {
                    $premierChapitre = $entity->realisationChapitres->first();
                    $lienMicroCompetence = $premierChapitre->realisationMicroCompetence->lien_livrable ?? null;
                }
            @endphp
            @if (optional($livrable)->is_affichable_seulement_par_formateur !== true || $isFormateur)
                <li class="text-danger livrable-manquant text-truncate">
                     <i class="{{ $icon }}"></i> {{ $livrable->titre }}
                    <span class="d-block text-muted small" title="Non encore déposé">— Livrable non soumis</span>
                </li>
            @endif
        @endforeach
    @endif
</ul>