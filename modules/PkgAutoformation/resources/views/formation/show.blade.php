{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.show') . ' ' . __('PkgAutoformation::formation.singular'))
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('Core::msg.detail') }}</h1>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('formations.edit', $itemFormation->id) }}" class="btn btn-default float-right">
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
                                <label for="nom">{{ ucfirst(__('PkgAutoformation::formation.nom')) }}:</label>
                                <p>{{ $itemFormation->nom }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="lien">{{ ucfirst(__('PkgAutoformation::formation.lien')) }}:</label>
                                <p>{{ $itemFormation->lien }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="competence_id">{{ ucfirst(__('PkgAutoformation::formation.competence_id')) }}:</label>
                                <p>{{ $itemFormation->competence_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="is_officiel">{{ ucfirst(__('PkgAutoformation::formation.is_officiel')) }}:</label>
                                <p>{{ $itemFormation->is_officiel }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="formateur_id">{{ ucfirst(__('PkgAutoformation::formation.formateur_id')) }}:</label>
                                <p>{{ $itemFormation->formateur_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="formation_officiel_id">{{ ucfirst(__('PkgAutoformation::formation.formation_officiel_id')) }}:</label>
                                <p>{{ $itemFormation->formation_officiel_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="description">{{ ucfirst(__('PkgAutoformation::formation.description')) }}:</label>
                                <p>{{ $itemFormation->description }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
