{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.show') . ' ' . __('PkgAutoformation::chapitre.singular'))
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('Core::msg.detail') }}</h1>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('chapitres.edit', $itemChapitre->id) }}" class="btn btn-default float-right">
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
                                <label for="nom">{{ ucfirst(__('PkgAutoformation::chapitre.nom')) }}:</label>
                                <p>{{ $itemChapitre->nom }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="lien">{{ ucfirst(__('PkgAutoformation::chapitre.lien')) }}:</label>
                                <p>{{ $itemChapitre->lien }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="coefficient">{{ ucfirst(__('PkgAutoformation::chapitre.coefficient')) }}:</label>
                                <p>{{ $itemChapitre->coefficient }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="description">{{ ucfirst(__('PkgAutoformation::chapitre.description')) }}:</label>
                                <p>{{ $itemChapitre->description }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="ordre">{{ ucfirst(__('PkgAutoformation::chapitre.ordre')) }}:</label>
                                <p>{{ $itemChapitre->ordre }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="is_officiel">{{ ucfirst(__('PkgAutoformation::chapitre.is_officiel')) }}:</label>
                                <p>{{ $itemChapitre->is_officiel }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="formation_id">{{ ucfirst(__('PkgAutoformation::chapitre.formation_id')) }}:</label>
                                <p>{{ $itemChapitre->formation_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="niveau_competence_id">{{ ucfirst(__('PkgAutoformation::chapitre.niveau_competence_id')) }}:</label>
                                <p>{{ $itemChapitre->niveau_competence_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="formateur_id">{{ ucfirst(__('PkgAutoformation::chapitre.formateur_id')) }}:</label>
                                <p>{{ $itemChapitre->formateur_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="chapitre_officiel_id">{{ ucfirst(__('PkgAutoformation::chapitre.chapitre_officiel_id')) }}:</label>
                                <p>{{ $itemChapitre->chapitre_officiel_id }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
