{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.show') . ' ' . __('PkgGestionTaches::commentaireRealisationTache.singular'))
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('Core::msg.detail') }}</h1>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('commentaireRealisationTaches.edit', $itemCommentaireRealisationTache->id) }}" class="btn btn-default float-right">
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
                                <label for="commentaire">{{ ucfirst(__('PkgGestionTaches::commentaireRealisationTache.commentaire')) }}:</label>
                                <p>{{ $itemCommentaireRealisationTache->commentaire }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="dateCommentaire">{{ ucfirst(__('PkgGestionTaches::commentaireRealisationTache.dateCommentaire')) }}:</label>
                                <p>{{ $itemCommentaireRealisationTache->dateCommentaire }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="realisation_tache_id">{{ ucfirst(__('PkgGestionTaches::commentaireRealisationTache.realisation_tache_id')) }}:</label>
                                <p>{{ $itemCommentaireRealisationTache->realisation_tache_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="formateur_id">{{ ucfirst(__('PkgGestionTaches::commentaireRealisationTache.formateur_id')) }}:</label>
                                <p>{{ $itemCommentaireRealisationTache->formateur_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="apprenant_id">{{ ucfirst(__('PkgGestionTaches::commentaireRealisationTache.apprenant_id')) }}:</label>
                                <p>{{ $itemCommentaireRealisationTache->apprenant_id }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
