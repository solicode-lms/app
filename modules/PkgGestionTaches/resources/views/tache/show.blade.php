{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.show') . ' ' . __('PkgGestionTaches::tache.singular'))
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('Core::msg.detail') }}</h1>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('taches.edit', $itemTache->id) }}" class="btn btn-default float-right">
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
                                <label for="ordre">{{ ucfirst(__('PkgGestionTaches::tache.ordre')) }}:</label>
                                <p>{{ $itemTache->ordre }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="titre">{{ ucfirst(__('PkgGestionTaches::tache.titre')) }}:</label>
                                <p>{{ $itemTache->titre }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="priorite_tache_id">{{ ucfirst(__('PkgGestionTaches::tache.priorite_tache_id')) }}:</label>
                                <p>{{ $itemTache->priorite_tache_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="projet_id">{{ ucfirst(__('PkgGestionTaches::tache.projet_id')) }}:</label>
                                <p>{{ $itemTache->projet_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="description">{{ ucfirst(__('PkgGestionTaches::tache.description')) }}:</label>
                                <p>{{ $itemTache->description }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="dateDebut">{{ ucfirst(__('PkgGestionTaches::tache.dateDebut')) }}:</label>
                                <p>{{ $itemTache->dateDebut }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="dateFin">{{ ucfirst(__('PkgGestionTaches::tache.dateFin')) }}:</label>
                                <p>{{ $itemTache->dateFin }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
