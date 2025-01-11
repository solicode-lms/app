{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.show') . ' ' . __('PkgUtilisateurs::apprenantKonosy.singular'))
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
                                <label for="Adresse">{{ ucfirst(__('PkgUtilisateurs::apprenantKonosy.Adresse')) }}:</label>
                                <p>{{ $itemApprenantKonosy->Adresse }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="CIN">{{ ucfirst(__('PkgUtilisateurs::apprenantKonosy.CIN')) }}:</label>
                                <p>{{ $itemApprenantKonosy->CIN }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="CodeDiplome">{{ ucfirst(__('PkgUtilisateurs::apprenantKonosy.CodeDiplome')) }}:</label>
                                <p>{{ $itemApprenantKonosy->CodeDiplome }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="DateInscription">{{ ucfirst(__('PkgUtilisateurs::apprenantKonosy.DateInscription')) }}:</label>
                                <p>{{ $itemApprenantKonosy->DateInscription }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="DateNaissance">{{ ucfirst(__('PkgUtilisateurs::apprenantKonosy.DateNaissance')) }}:</label>
                                <p>{{ $itemApprenantKonosy->DateNaissance }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="Diplome">{{ ucfirst(__('PkgUtilisateurs::apprenantKonosy.Diplome')) }}:</label>
                                <p>{{ $itemApprenantKonosy->Diplome }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="EtudiantActif">{{ ucfirst(__('PkgUtilisateurs::apprenantKonosy.EtudiantActif')) }}:</label>
                                <p>{{ $itemApprenantKonosy->EtudiantActif }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="LibelleLong">{{ ucfirst(__('PkgUtilisateurs::apprenantKonosy.LibelleLong')) }}:</label>
                                <p>{{ $itemApprenantKonosy->LibelleLong }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="LieuNaissance">{{ ucfirst(__('PkgUtilisateurs::apprenantKonosy.LieuNaissance')) }}:</label>
                                <p>{{ $itemApprenantKonosy->LieuNaissance }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="MatriculeEtudiant">{{ ucfirst(__('PkgUtilisateurs::apprenantKonosy.MatriculeEtudiant')) }}:</label>
                                <p>{{ $itemApprenantKonosy->MatriculeEtudiant }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="Nationalite">{{ ucfirst(__('PkgUtilisateurs::apprenantKonosy.Nationalite')) }}:</label>
                                <p>{{ $itemApprenantKonosy->Nationalite }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="NiveauScolaire">{{ ucfirst(__('PkgUtilisateurs::apprenantKonosy.NiveauScolaire')) }}:</label>
                                <p>{{ $itemApprenantKonosy->NiveauScolaire }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="Nom">{{ ucfirst(__('PkgUtilisateurs::apprenantKonosy.Nom')) }}:</label>
                                <p>{{ $itemApprenantKonosy->Nom }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="Nom_Arabe">{{ ucfirst(__('PkgUtilisateurs::apprenantKonosy.Nom_Arabe')) }}:</label>
                                <p>{{ $itemApprenantKonosy->Nom_Arabe }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="NTelephone">{{ ucfirst(__('PkgUtilisateurs::apprenantKonosy.NTelephone')) }}:</label>
                                <p>{{ $itemApprenantKonosy->NTelephone }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="Prenom">{{ ucfirst(__('PkgUtilisateurs::apprenantKonosy.Prenom')) }}:</label>
                                <p>{{ $itemApprenantKonosy->Prenom }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="Prenom_Arabe">{{ ucfirst(__('PkgUtilisateurs::apprenantKonosy.Prenom_Arabe')) }}:</label>
                                <p>{{ $itemApprenantKonosy->Prenom_Arabe }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="Principale">{{ ucfirst(__('PkgUtilisateurs::apprenantKonosy.Principale')) }}:</label>
                                <p>{{ $itemApprenantKonosy->Principale }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="Sexe">{{ ucfirst(__('PkgUtilisateurs::apprenantKonosy.Sexe')) }}:</label>
                                <p>{{ $itemApprenantKonosy->Sexe }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
