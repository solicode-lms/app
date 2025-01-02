{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.show') . ' ' . __('PkgCreationProjet::livrable.singular'))
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('Core::msg.detail') }}</h1>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('livrables.edit', $itemLivrable->id) }}" class="btn btn-default float-right">
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
                                <label for="titre">{{ ucfirst(__('PkgCreationProjet::livrable.titre')) }}:</label>
                                <p>{{ $itemLivrable->titre }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="nature_livrable_id">{{ ucfirst(__('PkgCreationProjet::livrable.nature_livrable_id')) }}:</label>
                                <p>{{ $itemLivrable->nature_livrable_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="projet_id">{{ ucfirst(__('PkgCreationProjet::livrable.projet_id')) }}:</label>
                                <p>{{ $itemLivrable->projet_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="description">{{ ucfirst(__('PkgCreationProjet::livrable.description')) }}:</label>
                                <p>{{ $itemLivrable->description }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
