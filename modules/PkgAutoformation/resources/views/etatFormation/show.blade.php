{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.show') . ' ' . __('PkgAutoformation::etatFormation.singular'))
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('Core::msg.detail') }}</h1>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('etatFormations.edit', $itemEtatFormation->id) }}" class="btn btn-default float-right">
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
                                <label for="nom">{{ ucfirst(__('PkgAutoformation::etatFormation.nom')) }}:</label>
                                <p>{{ $itemEtatFormation->nom }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="workflow_formation_id">{{ ucfirst(__('PkgAutoformation::etatFormation.workflow_formation_id')) }}:</label>
                                <p>{{ $itemEtatFormation->workflow_formation_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="sys_color_id">{{ ucfirst(__('PkgAutoformation::etatFormation.sys_color_id')) }}:</label>
                                <p>{{ $itemEtatFormation->sys_color_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="is_editable_only_by_formateur">{{ ucfirst(__('PkgAutoformation::etatFormation.is_editable_only_by_formateur')) }}:</label>
                                <p>{{ $itemEtatFormation->is_editable_only_by_formateur }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="formateur_id">{{ ucfirst(__('PkgAutoformation::etatFormation.formateur_id')) }}:</label>
                                <p>{{ $itemEtatFormation->formateur_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="description">{{ ucfirst(__('PkgAutoformation::etatFormation.description')) }}:</label>
                                <p>{{ $itemEtatFormation->description }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
