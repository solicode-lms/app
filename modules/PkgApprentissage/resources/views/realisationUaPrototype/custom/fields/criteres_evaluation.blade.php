@php
    $criteresHtml = null;
    $realisationTache = $entity->realisationTache ?? null;

    if($realisationTache && $realisationTache->realisationProjet && $realisationTache->realisationProjet->affectationProjet) {
        $projetId = $realisationTache->realisationProjet->affectationProjet->projet_id;
        
        // Récupération de l'UA via la relation RealisationUa
        $realisationUa = $entity->realisationUa ?? null;
        $uaId = $realisationUa ? $realisationUa->unite_apprentissage_id : null;

        if ($projetId && $uaId) {
            $mobilisation = \Modules\PkgCreationProjet\Models\MobilisationUa::where('projet_id', $projetId)
                ->where('unite_apprentissage_id', $uaId)
                ->first();
            
            if($mobilisation) {
                $criteresHtml = $mobilisation->criteres_evaluation_prototype;
            }
        }
    }
@endphp

{{-- Affichage des critères depuis la mobilisation --}}
@if(!empty($criteresHtml))
    <div class="criteres-content">
        {!! $criteresHtml !!}
    </div>
@else
    {{-- Fallback: Aucune mobilisation trouvée ou champ vide --}}
    <em>Aucun critère défini pour ce projet.</em>
@endif

{{-- Affichage de la liste des livrables de l'apprenant pour cette réalisation de tâche --}}
@php
    $rt = $entity->realisationTache ?? null;
@endphp
@if($rt)
    <div class="mt-4">
        <h6 class="text-muted mb-2"><i class="fas fa-file-alt"></i> Livrables soumis :</h6>
        @include('PkgRealisationTache::realisationTache.custom.fields.nombre_livrables', ['entity' => $rt])
    </div>
@endif
