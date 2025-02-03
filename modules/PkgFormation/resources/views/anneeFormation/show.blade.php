{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.show') . ' ' . __('PkgFormation::anneeFormation.singular'))
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('Core::msg.detail') }}</h1>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('anneeFormations.edit', $itemAnneeFormation->id) }}" class="btn btn-default float-right">
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
                                <label for="titre">{{ ucfirst(__('PkgFormation::anneeFormation.titre')) }}:</label>
                                <p>{{ $itemAnneeFormation->titre }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="date_debut">{{ ucfirst(__('PkgFormation::anneeFormation.date_debut')) }}:</label>
                                <p>{{ $itemAnneeFormation->date_debut }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="date_fin">{{ ucfirst(__('PkgFormation::anneeFormation.date_fin')) }}:</label>
                                <p>{{ $itemAnneeFormation->date_fin }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
