{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.show') . ' ' . __('PkgCompetences::appreciation.singular'))
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('Core::msg.detail') }}</h1>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('appreciations.edit', $item->id) }}" class="btn btn-default float-right">
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
                                <label for="nom">{{ ucfirst(__('PkgCompetences::appreciation.nom')) }}:</label>
                                <p>{{ $item->nom }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="description">{{ ucfirst(__('PkgCompetences::appreciation.description')) }}:</label>
                                <p>{{ $item->description }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="noteMin">{{ ucfirst(__('PkgCompetences::appreciation.noteMin')) }}:</label>
                                <p>{{ $item->noteMin }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="noteMax">{{ ucfirst(__('PkgCompetences::appreciation.noteMax')) }}:</label>
                                <p>{{ $item->noteMax }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="niveau_competence_id">{{ ucfirst(__('PkgCompetences::appreciation.niveau_competence_id')) }}:</label>
                                <p>{{ $item->niveau_competence_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="formateur_id">{{ ucfirst(__('PkgCompetences::appreciation.formateur_id')) }}:</label>
                                <p>{{ $item->formateur_id }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection