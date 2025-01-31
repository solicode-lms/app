{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.show') . ' ' . __('PkgRealisationProjets::affectationProjet.singular'))
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('Core::msg.detail') }}</h1>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('affectationProjets.edit', $itemAffectationProjet->id) }}" class="btn btn-default float-right">
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
                                <label for="date_debut">{{ ucfirst(__('PkgRealisationProjets::affectationProjet.date_debut')) }}:</label>
                                <p>{{ $itemAffectationProjet->date_debut }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="date_fin">{{ ucfirst(__('PkgRealisationProjets::affectationProjet.date_fin')) }}:</label>
                                <p>{{ $itemAffectationProjet->date_fin }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="annee_formation_id">{{ ucfirst(__('PkgRealisationProjets::affectationProjet.annee_formation_id')) }}:</label>
                                <p>{{ $itemAffectationProjet->annee_formation_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="projet_id">{{ ucfirst(__('PkgRealisationProjets::affectationProjet.projet_id')) }}:</label>
                                <p>{{ $itemAffectationProjet->projet_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="description">{{ ucfirst(__('PkgRealisationProjets::affectationProjet.description')) }}:</label>
                                <p>{{ $itemAffectationProjet->description }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
