{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.show') . ' ' . __('PkgFormation::specialite.singular'))
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('Core::msg.detail') }}</h1>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('specialites.edit', $itemSpecialite->id) }}" class="btn btn-default float-right">
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
                                <label for="nom">{{ ucfirst(__('PkgFormation::specialite.nom')) }}:</label>
                                <p>{{ $itemSpecialite->nom }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="description">{{ ucfirst(__('PkgFormation::specialite.description')) }}:</label>
                                <p>{{ $itemSpecialite->description }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
