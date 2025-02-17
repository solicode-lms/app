{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.show') . ' ' . __('PkgRealisationProjets::livrablesRealisation.singular'))
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('Core::msg.detail') }}</h1>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('livrablesRealisations.edit', $itemLivrablesRealisation->id) }}" class="btn btn-default float-right">
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
                                <label for="livrable_id">{{ ucfirst(__('PkgRealisationProjets::livrablesRealisation.livrable_id')) }}:</label>
                                <p>{{ $itemLivrablesRealisation->livrable_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="lien">{{ ucfirst(__('PkgRealisationProjets::livrablesRealisation.lien')) }}:</label>
                                <p>{{ $itemLivrablesRealisation->lien }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="titre">{{ ucfirst(__('PkgRealisationProjets::livrablesRealisation.titre')) }}:</label>
                                <p>{{ $itemLivrablesRealisation->titre }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="description">{{ ucfirst(__('PkgRealisationProjets::livrablesRealisation.description')) }}:</label>
                                <p>{{ $itemLivrablesRealisation->description }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="realisation_projet_id">{{ ucfirst(__('PkgRealisationProjets::livrablesRealisation.realisation_projet_id')) }}:</label>
                                <p>{{ $itemLivrablesRealisation->realisation_projet_id }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
