{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.show') . ' ' . __('PkgGestionTaches::realisationTache.singular'))
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('Core::msg.detail') }}</h1>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('realisationTaches.edit', $itemRealisationTache->id) }}" class="btn btn-default float-right">
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
                                <label for="Livrables">{{ ucfirst(__('PkgGestionTaches::realisationTache.Livrables')) }}:</label>
                                <p>{{ $itemRealisationTache->Livrables }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="tache_id">{{ ucfirst(__('PkgGestionTaches::realisationTache.tache_id')) }}:</label>
                                <p>{{ $itemRealisationTache->tache_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="realisation_projet_id">{{ ucfirst(__('PkgGestionTaches::realisationTache.realisation_projet_id')) }}:</label>
                                <p>{{ $itemRealisationTache->realisation_projet_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="dateDebut">{{ ucfirst(__('PkgGestionTaches::realisationTache.dateDebut')) }}:</label>
                                <p>{{ $itemRealisationTache->dateDebut }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="dateFin">{{ ucfirst(__('PkgGestionTaches::realisationTache.dateFin')) }}:</label>
                                <p>{{ $itemRealisationTache->dateFin }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="etat_realisation_tache_id">{{ ucfirst(__('PkgGestionTaches::realisationTache.etat_realisation_tache_id')) }}:</label>
                                <p>{{ $itemRealisationTache->etat_realisation_tache_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="remarques_formateur">{{ ucfirst(__('PkgGestionTaches::realisationTache.remarques_formateur')) }}:</label>
                                <p>{{ $itemRealisationTache->remarques_formateur }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="remarques_apprenant">{{ ucfirst(__('PkgGestionTaches::realisationTache.remarques_apprenant')) }}:</label>
                                <p>{{ $itemRealisationTache->remarques_apprenant }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
