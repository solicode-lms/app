{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.show') . ' ' . __('PkgRealisationProjets::realisationProjet.singular'))
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('Core::msg.detail') }}</h1>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('realisationProjets.edit', $itemRealisationProjet->id) }}" class="btn btn-default float-right">
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
                                <label for="date_debut">{{ ucfirst(__('PkgRealisationProjets::realisationProjet.date_debut')) }}:</label>
                                <p>{{ $itemRealisationProjet->date_debut }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="date_fin">{{ ucfirst(__('PkgRealisationProjets::realisationProjet.date_fin')) }}:</label>
                                <p>{{ $itemRealisationProjet->date_fin }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="rapport">{{ ucfirst(__('PkgRealisationProjets::realisationProjet.rapport')) }}:</label>
                                <p>{{ $itemRealisationProjet->rapport }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="projet_id">{{ ucfirst(__('PkgRealisationProjets::realisationProjet.projet_id')) }}:</label>
                                <p>{{ $itemRealisationProjet->projet_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="etats_realisation_projet_id">{{ ucfirst(__('PkgRealisationProjets::realisationProjet.etats_realisation_projet_id')) }}:</label>
                                <p>{{ $itemRealisationProjet->etats_realisation_projet_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="apprenant_id">{{ ucfirst(__('PkgRealisationProjets::realisationProjet.apprenant_id')) }}:</label>
                                <p>{{ $itemRealisationProjet->apprenant_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="affectation_projet_id">{{ ucfirst(__('PkgRealisationProjets::realisationProjet.affectation_projet_id')) }}:</label>
                                <p>{{ $itemRealisationProjet->affectation_projet_id }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
