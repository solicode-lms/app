{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.show') . ' ' . __('PkgRealisationProjets::etatsRealisationProjet.singular'))
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('Core::msg.detail') }}</h1>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('etatsRealisationProjets.edit', $itemEtatsRealisationProjet->id) }}" class="btn btn-default float-right">
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
                                <label for="titre">{{ ucfirst(__('PkgRealisationProjets::etatsRealisationProjet.titre')) }}:</label>
                                <p>{{ $itemEtatsRealisationProjet->titre }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="description">{{ ucfirst(__('PkgRealisationProjets::etatsRealisationProjet.description')) }}:</label>
                                <p>{{ $itemEtatsRealisationProjet->description }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="formateur_id">{{ ucfirst(__('PkgRealisationProjets::etatsRealisationProjet.formateur_id')) }}:</label>
                                <p>{{ $itemEtatsRealisationProjet->formateur_id }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
