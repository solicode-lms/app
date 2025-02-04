{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.show') . ' ' . __('PkgCompetences::niveauDifficulte.singular'))
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('Core::msg.detail') }}</h1>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('niveauDifficultes.edit', $itemNiveauDifficulte->id) }}" class="btn btn-default float-right">
                        <i class="far fa-edit"></i>
                        {{ __('Core::msg.edit') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="col-sm-12">
                                <label for="nom">{{ ucfirst(__('PkgCompetences::niveauDifficulte.nom')) }}:</label>
                                <p>{{ $itemNiveauDifficulte->nom }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="noteMin">{{ ucfirst(__('PkgCompetences::niveauDifficulte.noteMin')) }}:</label>
                                <p>{{ $itemNiveauDifficulte->noteMin }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="noteMax">{{ ucfirst(__('PkgCompetences::niveauDifficulte.noteMax')) }}:</label>
                                <p>{{ $itemNiveauDifficulte->noteMax }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="formateur_id">{{ ucfirst(__('PkgCompetences::niveauDifficulte.formateur_id')) }}:</label>
                                <p>{{ $itemNiveauDifficulte->formateur_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="description">{{ ucfirst(__('PkgCompetences::niveauDifficulte.description')) }}:</label>
                                <p>{{ $itemNiveauDifficulte->description }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
