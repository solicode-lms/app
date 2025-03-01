{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.show') . ' ' . __('PkgGestionTaches::dependanceTache.singular'))
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('Core::msg.detail') }}</h1>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('dependanceTaches.edit', $itemDependanceTache->id) }}" class="btn btn-default float-right">
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
                                <label for="tache_id">{{ ucfirst(__('PkgGestionTaches::dependanceTache.tache_id')) }}:</label>
                                <p>{{ $itemDependanceTache->tache_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="tache_cible_id">{{ ucfirst(__('PkgGestionTaches::dependanceTache.tache_cible_id')) }}:</label>
                                <p>{{ $itemDependanceTache->tache_cible_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="type_dependance_tache_id">{{ ucfirst(__('PkgGestionTaches::dependanceTache.type_dependance_tache_id')) }}:</label>
                                <p>{{ $itemDependanceTache->type_dependance_tache_id }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
