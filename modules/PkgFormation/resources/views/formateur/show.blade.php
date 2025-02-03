{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.show') . ' ' . __('PkgFormation::formateur.singular'))
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('Core::msg.detail') }}</h1>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('formateurs.edit', $itemFormateur->id) }}" class="btn btn-default float-right">
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
                                <label for="matricule">{{ ucfirst(__('PkgFormation::formateur.matricule')) }}:</label>
                                <p>{{ $itemFormateur->matricule }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="nom">{{ ucfirst(__('PkgFormation::formateur.nom')) }}:</label>
                                <p>{{ $itemFormateur->nom }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="prenom">{{ ucfirst(__('PkgFormation::formateur.prenom')) }}:</label>
                                <p>{{ $itemFormateur->prenom }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="prenom_arab">{{ ucfirst(__('PkgFormation::formateur.prenom_arab')) }}:</label>
                                <p>{{ $itemFormateur->prenom_arab }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="nom_arab">{{ ucfirst(__('PkgFormation::formateur.nom_arab')) }}:</label>
                                <p>{{ $itemFormateur->nom_arab }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="tele_num">{{ ucfirst(__('PkgFormation::formateur.tele_num')) }}:</label>
                                <p>{{ $itemFormateur->tele_num }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="adresse">{{ ucfirst(__('PkgFormation::formateur.adresse')) }}:</label>
                                <p>{{ $itemFormateur->adresse }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="diplome">{{ ucfirst(__('PkgFormation::formateur.diplome')) }}:</label>
                                <p>{{ $itemFormateur->diplome }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="echelle">{{ ucfirst(__('PkgFormation::formateur.echelle')) }}:</label>
                                <p>{{ $itemFormateur->echelle }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="echelon">{{ ucfirst(__('PkgFormation::formateur.echelon')) }}:</label>
                                <p>{{ $itemFormateur->echelon }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="profile_image">{{ ucfirst(__('PkgFormation::formateur.profile_image')) }}:</label>
                                <p>{{ $itemFormateur->profile_image }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="user_id">{{ ucfirst(__('PkgFormation::formateur.user_id')) }}:</label>
                                <p>{{ $itemFormateur->user_id }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
