{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.show') . ' ' . __('PkgGestionTaches::etatRealisationTache.singular'))
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('Core::msg.detail') }}</h1>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('etatRealisationTaches.edit', $itemEtatRealisationTache->id) }}" class="btn btn-default float-right">
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
                                <label for="nom">{{ ucfirst(__('PkgGestionTaches::etatRealisationTache.nom')) }}:</label>
                                <p>{{ $itemEtatRealisationTache->nom }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="workflow_tache_id">{{ ucfirst(__('PkgGestionTaches::etatRealisationTache.workflow_tache_id')) }}:</label>
                                <p>{{ $itemEtatRealisationTache->workflow_tache_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="sys_color_id">{{ ucfirst(__('PkgGestionTaches::etatRealisationTache.sys_color_id')) }}:</label>
                                <p>{{ $itemEtatRealisationTache->sys_color_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="is_editable_only_by_formateur">{{ ucfirst(__('PkgGestionTaches::etatRealisationTache.is_editable_only_by_formateur')) }}:</label>
                                <p>{{ $itemEtatRealisationTache->is_editable_only_by_formateur }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="formateur_id">{{ ucfirst(__('PkgGestionTaches::etatRealisationTache.formateur_id')) }}:</label>
                                <p>{{ $itemEtatRealisationTache->formateur_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="description">{{ ucfirst(__('PkgGestionTaches::etatRealisationTache.description')) }}:</label>
                                <p>{{ $itemEtatRealisationTache->description }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
