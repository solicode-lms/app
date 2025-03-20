{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.show') . ' ' . __('PkgAutoformation::realisationChapitre.singular'))
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('Core::msg.detail') }}</h1>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('realisationChapitres.edit', $itemRealisationChapitre->id) }}" class="btn btn-default float-right">
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
                                <label for="date_debut">{{ ucfirst(__('PkgAutoformation::realisationChapitre.date_debut')) }}:</label>
                                <p>{{ $itemRealisationChapitre->date_debut }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="date_fin">{{ ucfirst(__('PkgAutoformation::realisationChapitre.date_fin')) }}:</label>
                                <p>{{ $itemRealisationChapitre->date_fin }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="chapitre_id">{{ ucfirst(__('PkgAutoformation::realisationChapitre.chapitre_id')) }}:</label>
                                <p>{{ $itemRealisationChapitre->chapitre_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="realisation_formation_id">{{ ucfirst(__('PkgAutoformation::realisationChapitre.realisation_formation_id')) }}:</label>
                                <p>{{ $itemRealisationChapitre->realisation_formation_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="etat_chapitre_id">{{ ucfirst(__('PkgAutoformation::realisationChapitre.etat_chapitre_id')) }}:</label>
                                <p>{{ $itemRealisationChapitre->etat_chapitre_id }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
