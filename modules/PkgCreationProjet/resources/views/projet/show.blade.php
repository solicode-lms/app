{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.show') . ' ' . __('PkgCreationProjet::projet.singular'))
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('Core::msg.detail') }}</h1>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('projets.edit', $item->id) }}" class="btn btn-default float-right">
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
                                <label for="titre">{{ ucfirst(__('PkgCreationProjet::projet.titre')) }}:</label>
                                <p>{{ $item->titre }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="travail_a_faire">{{ ucfirst(__('PkgCreationProjet::projet.travail_a_faire')) }}:</label>
                                <p>{{ $item->travail_a_faire }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="critere_de_travail">{{ ucfirst(__('PkgCreationProjet::projet.critere_de_travail')) }}:</label>
                                <p>{{ $item->critere_de_travail }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="description">{{ ucfirst(__('PkgCreationProjet::projet.description')) }}:</label>
                                <p>{{ $item->description }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="date_debut">{{ ucfirst(__('PkgCreationProjet::projet.date_debut')) }}:</label>
                                <p>{{ $item->date_debut }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="date_fin">{{ ucfirst(__('PkgCreationProjet::projet.date_fin')) }}:</label>
                                <p>{{ $item->date_fin }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="formateur_id">{{ ucfirst(__('PkgCreationProjet::projet.formateur_id')) }}:</label>
                                <p>{{ $item->formateur_id }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection