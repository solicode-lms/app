{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


@extends('PkgSessions::alignementUa._fields')



<div class="card-body">
    <div class="row">
        <div class="col-12 col-md-12">
        <label for="EvaluationRealisationTache">
                Liste des Unité d'apprentissage non alignées 
        </label>

        {{-- dataSource.uniteApprentissage.code=uaNonAlignee --}}
            @include('PkgCompetences::uniteApprentissage._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'realisationTache.edit_' . "1"])
        </div>
    </div>
</div>




