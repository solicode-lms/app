{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.show') . ' ' . __('PkgApprenants::apprenant.singular'))
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('Core::msg.detail') }}</h1>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('apprenants.edit', $itemApprenant->id) }}" class="btn btn-default float-right">
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
                                <label for="nom">{{ ucfirst(__('PkgApprenants::apprenant.nom')) }}:</label>
                                <p>{{ $itemApprenant->nom }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="nom_arab">{{ ucfirst(__('PkgApprenants::apprenant.nom_arab')) }}:</label>
                                <p>{{ $itemApprenant->nom_arab }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="prenom">{{ ucfirst(__('PkgApprenants::apprenant.prenom')) }}:</label>
                                <p>{{ $itemApprenant->prenom }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="prenom_arab">{{ ucfirst(__('PkgApprenants::apprenant.prenom_arab')) }}:</label>
                                <p>{{ $itemApprenant->prenom_arab }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="profile_image">{{ ucfirst(__('PkgApprenants::apprenant.profile_image')) }}:</label>
                                <p>{{ $itemApprenant->profile_image }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="cin">{{ ucfirst(__('PkgApprenants::apprenant.cin')) }}:</label>
                                <p>{{ $itemApprenant->cin }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="date_naissance">{{ ucfirst(__('PkgApprenants::apprenant.date_naissance')) }}:</label>
                                <p>{{ $itemApprenant->date_naissance }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="sexe">{{ ucfirst(__('PkgApprenants::apprenant.sexe')) }}:</label>
                                <p>{{ $itemApprenant->sexe }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="nationalite_id">{{ ucfirst(__('PkgApprenants::apprenant.nationalite_id')) }}:</label>
                                <p>{{ $itemApprenant->nationalite_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="lieu_naissance">{{ ucfirst(__('PkgApprenants::apprenant.lieu_naissance')) }}:</label>
                                <p>{{ $itemApprenant->lieu_naissance }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="diplome">{{ ucfirst(__('PkgApprenants::apprenant.diplome')) }}:</label>
                                <p>{{ $itemApprenant->diplome }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="adresse">{{ ucfirst(__('PkgApprenants::apprenant.adresse')) }}:</label>
                                <p>{{ $itemApprenant->adresse }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="niveaux_scolaire_id">{{ ucfirst(__('PkgApprenants::apprenant.niveaux_scolaire_id')) }}:</label>
                                <p>{{ $itemApprenant->niveaux_scolaire_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="tele_num">{{ ucfirst(__('PkgApprenants::apprenant.tele_num')) }}:</label>
                                <p>{{ $itemApprenant->tele_num }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="user_id">{{ ucfirst(__('PkgApprenants::apprenant.user_id')) }}:</label>
                                <p>{{ $itemApprenant->user_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="matricule">{{ ucfirst(__('PkgApprenants::apprenant.matricule')) }}:</label>
                                <p>{{ $itemApprenant->matricule }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="date_inscription">{{ ucfirst(__('PkgApprenants::apprenant.date_inscription')) }}:</label>
                                <p>{{ $itemApprenant->date_inscription }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="actif">{{ ucfirst(__('PkgApprenants::apprenant.actif')) }}:</label>
                                <p>{{ $itemApprenant->actif }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
