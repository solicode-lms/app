{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body">
    <div class="row">
      <div class="col-12">
        <h3 class="card-title">
             <h2>Liste des Unité d'apprentissage non alignées</h2>

        </h3>
    </div>

    <div class="col-12 col-md-12">
    <label for="EvaluationRealisationTache">
            Liste des Unité d'apprentissage non alignées 
    </label>
        @include('PkgCompetences::uniteApprentissage._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'realisationTache.edit_' . "1"])
    </div>

</div>




@extends('PkgSessions::alignementUa._fields')

