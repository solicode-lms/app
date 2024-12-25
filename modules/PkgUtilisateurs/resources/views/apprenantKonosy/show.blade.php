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
                    <a href="{{ route('apprenantKonosies.edit', $item->id) }}" class="btn btn-default float-right">
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
                                <label for="MatriculeEtudiant">{{ ucfirst(__('PkgUtilisateurs::apprenantKonosy.MatriculeEtudiant')) }}:</label>
                                <p>{{ $item->MatriculeEtudiant }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="Nom">{{ ucfirst(__('PkgUtilisateurs::apprenantKonosy.Nom')) }}:</label>
                                <p>{{ $item->Nom }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="Prenom">{{ ucfirst(__('PkgUtilisateurs::apprenantKonosy.Prenom')) }}:</label>
                                <p>{{ $item->Prenom }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="Sexe">{{ ucfirst(__('PkgUtilisateurs::apprenantKonosy.Sexe')) }}:</label>
                                <p>{{ $item->Sexe }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="EtudiantActif">{{ ucfirst(__('PkgUtilisateurs::apprenantKonosy.EtudiantActif')) }}:</label>
                                <p>{{ $item->EtudiantActif }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="Diplome">{{ ucfirst(__('PkgUtilisateurs::apprenantKonosy.Diplome')) }}:</label>
                                <p>{{ $item->Diplome }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="Principale">{{ ucfirst(__('PkgUtilisateurs::apprenantKonosy.Principale')) }}:</label>
                                <p>{{ $item->Principale }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="LibelleLong">{{ ucfirst(__('PkgUtilisateurs::apprenantKonosy.LibelleLong')) }}:</label>
                                <p>{{ $item->LibelleLong }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="CodeDiplome">{{ ucfirst(__('PkgUtilisateurs::apprenantKonosy.CodeDiplome')) }}:</label>
                                <p>{{ $item->CodeDiplome }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="DateNaissance">{{ ucfirst(__('PkgUtilisateurs::apprenantKonosy.DateNaissance')) }}:</label>
                                <p>{{ $item->DateNaissance }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="DateInscription">{{ ucfirst(__('PkgUtilisateurs::apprenantKonosy.DateInscription')) }}:</label>
                                <p>{{ $item->DateInscription }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="LieuNaissance">{{ ucfirst(__('PkgUtilisateurs::apprenantKonosy.LieuNaissance')) }}:</label>
                                <p>{{ $item->LieuNaissance }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="CIN">{{ ucfirst(__('PkgUtilisateurs::apprenantKonosy.CIN')) }}:</label>
                                <p>{{ $item->CIN }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="NTelephone">{{ ucfirst(__('PkgUtilisateurs::apprenantKonosy.NTelephone')) }}:</label>
                                <p>{{ $item->NTelephone }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="Adresse">{{ ucfirst(__('PkgUtilisateurs::apprenantKonosy.Adresse')) }}:</label>
                                <p>{{ $item->Adresse }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="Nationalite">{{ ucfirst(__('PkgUtilisateurs::apprenantKonosy.Nationalite')) }}:</label>
                                <p>{{ $item->Nationalite }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="Nom_Arabe">{{ ucfirst(__('PkgUtilisateurs::apprenantKonosy.Nom_Arabe')) }}:</label>
                                <p>{{ $item->Nom_Arabe }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="Prenom_Arabe">{{ ucfirst(__('PkgUtilisateurs::apprenantKonosy.Prenom_Arabe')) }}:</label>
                                <p>{{ $item->Prenom_Arabe }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="NiveauScolaire">{{ ucfirst(__('PkgUtilisateurs::apprenantKonosy.NiveauScolaire')) }}:</label>
                                <p>{{ $item->NiveauScolaire }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
