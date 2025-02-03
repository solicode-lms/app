{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.show') . ' ' . __('PkgApprenants::apprenantKonosy.singular'))
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('Core::msg.detail') }}</h1>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('apprenantKonosies.edit', $itemApprenantKonosy->id) }}" class="btn btn-default float-right">
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
                                <label for="MatriculeEtudiant">{{ ucfirst(__('PkgApprenants::apprenantKonosy.MatriculeEtudiant')) }}:</label>
                                <p>{{ $itemApprenantKonosy->MatriculeEtudiant }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="Nom">{{ ucfirst(__('PkgApprenants::apprenantKonosy.Nom')) }}:</label>
                                <p>{{ $itemApprenantKonosy->Nom }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="Prenom">{{ ucfirst(__('PkgApprenants::apprenantKonosy.Prenom')) }}:</label>
                                <p>{{ $itemApprenantKonosy->Prenom }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="Sexe">{{ ucfirst(__('PkgApprenants::apprenantKonosy.Sexe')) }}:</label>
                                <p>{{ $itemApprenantKonosy->Sexe }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="EtudiantActif">{{ ucfirst(__('PkgApprenants::apprenantKonosy.EtudiantActif')) }}:</label>
                                <p>{{ $itemApprenantKonosy->EtudiantActif }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="Diplome">{{ ucfirst(__('PkgApprenants::apprenantKonosy.Diplome')) }}:</label>
                                <p>{{ $itemApprenantKonosy->Diplome }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="Principale">{{ ucfirst(__('PkgApprenants::apprenantKonosy.Principale')) }}:</label>
                                <p>{{ $itemApprenantKonosy->Principale }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="LibelleLong">{{ ucfirst(__('PkgApprenants::apprenantKonosy.LibelleLong')) }}:</label>
                                <p>{{ $itemApprenantKonosy->LibelleLong }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="CodeDiplome">{{ ucfirst(__('PkgApprenants::apprenantKonosy.CodeDiplome')) }}:</label>
                                <p>{{ $itemApprenantKonosy->CodeDiplome }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="DateNaissance">{{ ucfirst(__('PkgApprenants::apprenantKonosy.DateNaissance')) }}:</label>
                                <p>{{ $itemApprenantKonosy->DateNaissance }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="DateInscription">{{ ucfirst(__('PkgApprenants::apprenantKonosy.DateInscription')) }}:</label>
                                <p>{{ $itemApprenantKonosy->DateInscription }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="LieuNaissance">{{ ucfirst(__('PkgApprenants::apprenantKonosy.LieuNaissance')) }}:</label>
                                <p>{{ $itemApprenantKonosy->LieuNaissance }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="CIN">{{ ucfirst(__('PkgApprenants::apprenantKonosy.CIN')) }}:</label>
                                <p>{{ $itemApprenantKonosy->CIN }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="NTelephone">{{ ucfirst(__('PkgApprenants::apprenantKonosy.NTelephone')) }}:</label>
                                <p>{{ $itemApprenantKonosy->NTelephone }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="Adresse">{{ ucfirst(__('PkgApprenants::apprenantKonosy.Adresse')) }}:</label>
                                <p>{{ $itemApprenantKonosy->Adresse }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="Nationalite">{{ ucfirst(__('PkgApprenants::apprenantKonosy.Nationalite')) }}:</label>
                                <p>{{ $itemApprenantKonosy->Nationalite }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="Nom_Arabe">{{ ucfirst(__('PkgApprenants::apprenantKonosy.Nom_Arabe')) }}:</label>
                                <p>{{ $itemApprenantKonosy->Nom_Arabe }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="Prenom_Arabe">{{ ucfirst(__('PkgApprenants::apprenantKonosy.Prenom_Arabe')) }}:</label>
                                <p>{{ $itemApprenantKonosy->Prenom_Arabe }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="NiveauScolaire">{{ ucfirst(__('PkgApprenants::apprenantKonosy.NiveauScolaire')) }}:</label>
                                <p>{{ $itemApprenantKonosy->NiveauScolaire }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
