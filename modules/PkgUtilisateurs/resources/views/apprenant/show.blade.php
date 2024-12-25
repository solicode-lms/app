{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.show') . ' ' . __('PkgUtilisateurs::apprenant.singular'))
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('Core::msg.detail') }}</h1>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('apprenants.edit', $item->id) }}" class="btn btn-default float-right">
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
                                <label for="nom">{{ ucfirst(__('PkgUtilisateurs::apprenant.nom')) }}:</label>
                                <p>{{ $item->nom }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="prenom">{{ ucfirst(__('PkgUtilisateurs::apprenant.prenom')) }}:</label>
                                <p>{{ $item->prenom }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="prenom_arab">{{ ucfirst(__('PkgUtilisateurs::apprenant.prenom_arab')) }}:</label>
                                <p>{{ $item->prenom_arab }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="nom_arab">{{ ucfirst(__('PkgUtilisateurs::apprenant.nom_arab')) }}:</label>
                                <p>{{ $item->nom_arab }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="tele_num">{{ ucfirst(__('PkgUtilisateurs::apprenant.tele_num')) }}:</label>
                                <p>{{ $item->tele_num }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="profile_image">{{ ucfirst(__('PkgUtilisateurs::apprenant.profile_image')) }}:</label>
                                <p>{{ $item->profile_image }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="matricule">{{ ucfirst(__('PkgUtilisateurs::apprenant.matricule')) }}:</label>
                                <p>{{ $item->matricule }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="sexe">{{ ucfirst(__('PkgUtilisateurs::apprenant.sexe')) }}:</label>
                                <p>{{ $item->sexe }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="actif">{{ ucfirst(__('PkgUtilisateurs::apprenant.actif')) }}:</label>
                                <p>{{ $item->actif }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="diplome">{{ ucfirst(__('PkgUtilisateurs::apprenant.diplome')) }}:</label>
                                <p>{{ $item->diplome }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="date_naissance">{{ ucfirst(__('PkgUtilisateurs::apprenant.date_naissance')) }}:</label>
                                <p>{{ $item->date_naissance }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="date_inscription">{{ ucfirst(__('PkgUtilisateurs::apprenant.date_inscription')) }}:</label>
                                <p>{{ $item->date_inscription }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="lieu_naissance">{{ ucfirst(__('PkgUtilisateurs::apprenant.lieu_naissance')) }}:</label>
                                <p>{{ $item->lieu_naissance }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="cin">{{ ucfirst(__('PkgUtilisateurs::apprenant.cin')) }}:</label>
                                <p>{{ $item->cin }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="adresse">{{ ucfirst(__('PkgUtilisateurs::apprenant.adresse')) }}:</label>
                                <p>{{ $item->adresse }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="groupe_id">{{ ucfirst(__('PkgUtilisateurs::apprenant.groupe_id')) }}:</label>
                                <p>{{ $item->groupe_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="niveaux_scolaire_id">{{ ucfirst(__('PkgUtilisateurs::apprenant.niveaux_scolaire_id')) }}:</label>
                                <p>{{ $item->niveaux_scolaire_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="nationalite_id">{{ ucfirst(__('PkgUtilisateurs::apprenant.nationalite_id')) }}:</label>
                                <p>{{ $item->nationalite_id }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
