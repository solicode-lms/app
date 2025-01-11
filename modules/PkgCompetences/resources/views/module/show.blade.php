{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.show') . ' ' . __('PkgCompetences::module.singular'))
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('Core::msg.detail') }}</h1>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('modules.edit', $itemModule->id) }}" class="btn btn-default float-right">
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
                                <label for="description">{{ ucfirst(__('PkgCompetences::module.description')) }}:</label>
                                <p>{{ $itemModule->description }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="filiere_id">{{ ucfirst(__('PkgCompetences::module.filiere_id')) }}:</label>
                                <p>{{ $itemModule->filiere_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="masse_horaire">{{ ucfirst(__('PkgCompetences::module.masse_horaire')) }}:</label>
                                <p>{{ $itemModule->masse_horaire }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="nom">{{ ucfirst(__('PkgCompetences::module.nom')) }}:</label>
                                <p>{{ $itemModule->nom }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
