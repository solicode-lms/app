{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.show') . ' ' . __('PkgAutoformation::realisationFormation.singular'))
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('Core::msg.detail') }}</h1>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('realisationFormations.edit', $itemRealisationFormation->id) }}" class="btn btn-default float-right">
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
                                <label for="date_debut">{{ ucfirst(__('PkgAutoformation::realisationFormation.date_debut')) }}:</label>
                                <p>{{ $itemRealisationFormation->date_debut }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="date_fin">{{ ucfirst(__('PkgAutoformation::realisationFormation.date_fin')) }}:</label>
                                <p>{{ $itemRealisationFormation->date_fin }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="formation_id">{{ ucfirst(__('PkgAutoformation::realisationFormation.formation_id')) }}:</label>
                                <p>{{ $itemRealisationFormation->formation_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="apprenant_id">{{ ucfirst(__('PkgAutoformation::realisationFormation.apprenant_id')) }}:</label>
                                <p>{{ $itemRealisationFormation->apprenant_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="etat_formation_id">{{ ucfirst(__('PkgAutoformation::realisationFormation.etat_formation_id')) }}:</label>
                                <p>{{ $itemRealisationFormation->etat_formation_id }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
