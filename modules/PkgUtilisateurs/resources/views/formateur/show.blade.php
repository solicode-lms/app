{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.show') . ' ' . __('PkgUtilisateurs::formateur.singular'))
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('Core::msg.detail') }}</h1>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('formateurs.edit', $item->id) }}" class="btn btn-default float-right">
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
                                <label for="matricule">{{ ucfirst(__('PkgUtilisateurs::formateur.matricule')) }}:</label>
                                <p>{{ $item->matricule }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="nom">{{ ucfirst(__('PkgUtilisateurs::formateur.nom')) }}:</label>
                                <p>{{ $item->nom }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="prenom">{{ ucfirst(__('PkgUtilisateurs::formateur.prenom')) }}:</label>
                                <p>{{ $item->prenom }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="prenom_arab">{{ ucfirst(__('PkgUtilisateurs::formateur.prenom_arab')) }}:</label>
                                <p>{{ $item->prenom_arab }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="nom_arab">{{ ucfirst(__('PkgUtilisateurs::formateur.nom_arab')) }}:</label>
                                <p>{{ $item->nom_arab }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="tele_num">{{ ucfirst(__('PkgUtilisateurs::formateur.tele_num')) }}:</label>
                                <p>{{ $item->tele_num }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="adresse">{{ ucfirst(__('PkgUtilisateurs::formateur.adresse')) }}:</label>
                                <p>{{ $item->adresse }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="diplome">{{ ucfirst(__('PkgUtilisateurs::formateur.diplome')) }}:</label>
                                <p>{{ $item->diplome }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="echelle">{{ ucfirst(__('PkgUtilisateurs::formateur.echelle')) }}:</label>
                                <p>{{ $item->echelle }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="echelon">{{ ucfirst(__('PkgUtilisateurs::formateur.echelon')) }}:</label>
                                <p>{{ $item->echelon }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="profile_image">{{ ucfirst(__('PkgUtilisateurs::formateur.profile_image')) }}:</label>
                                <p>{{ $item->profile_image }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
