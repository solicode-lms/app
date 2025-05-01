{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.show') . ' ' . __('PkgGestionTaches::historiqueRealisationTache.singular'))
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('Core::msg.detail') }}</h1>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('historiqueRealisationTaches.edit', $itemHistoriqueRealisationTache->id) }}" class="btn btn-default float-right">
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
                                <label for="dateModification">{{ ucfirst(__('PkgGestionTaches::historiqueRealisationTache.dateModification')) }}:</label>
                                <p>{{ $itemHistoriqueRealisationTache->dateModification }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="changement">{{ ucfirst(__('PkgGestionTaches::historiqueRealisationTache.changement')) }}:</label>
                                <p>{{ $itemHistoriqueRealisationTache->changement }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="realisation_tache_id">{{ ucfirst(__('PkgGestionTaches::historiqueRealisationTache.realisation_tache_id')) }}:</label>
                                <p>{{ $itemHistoriqueRealisationTache->realisation_tache_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="user_id">{{ ucfirst(__('PkgGestionTaches::historiqueRealisationTache.user_id')) }}:</label>
                                <p>{{ $itemHistoriqueRealisationTache->user_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="isFeedback">{{ ucfirst(__('PkgGestionTaches::historiqueRealisationTache.isFeedback')) }}:</label>
                                <p>{{ $itemHistoriqueRealisationTache->isFeedback }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
