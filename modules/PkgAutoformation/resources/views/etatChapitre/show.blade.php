{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.show') . ' ' . __('PkgAutoformation::etatChapitre.singular'))
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('Core::msg.detail') }}</h1>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('etatChapitres.edit', $itemEtatChapitre->id) }}" class="btn btn-default float-right">
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
                                <label for="code">{{ ucfirst(__('PkgAutoformation::etatChapitre.code')) }}:</label>
                                <p>{{ $itemEtatChapitre->code }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="nom">{{ ucfirst(__('PkgAutoformation::etatChapitre.nom')) }}:</label>
                                <p>{{ $itemEtatChapitre->nom }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="workflow_chapitre_id">{{ ucfirst(__('PkgAutoformation::etatChapitre.workflow_chapitre_id')) }}:</label>
                                <p>{{ $itemEtatChapitre->workflow_chapitre_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="description">{{ ucfirst(__('PkgAutoformation::etatChapitre.description')) }}:</label>
                                <p>{{ $itemEtatChapitre->description }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
