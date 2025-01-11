{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.show') . ' ' . __('PkgGapp::eRelationship.singular'))
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('Core::msg.detail') }}</h1>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('eRelationships.edit', $itemERelationship->id) }}" class="btn btn-default float-right">
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
                                <label for="source_model_id">{{ ucfirst(__('PkgGapp::eRelationship.source_model_id')) }}:</label>
                                <p>{{ $itemERelationship->source_model_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="target_model_id">{{ ucfirst(__('PkgGapp::eRelationship.target_model_id')) }}:</label>
                                <p>{{ $itemERelationship->target_model_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="type">{{ ucfirst(__('PkgGapp::eRelationship.type')) }}:</label>
                                <p>{{ $itemERelationship->type }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="source_field">{{ ucfirst(__('PkgGapp::eRelationship.source_field')) }}:</label>
                                <p>{{ $itemERelationship->source_field }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="target_field">{{ ucfirst(__('PkgGapp::eRelationship.target_field')) }}:</label>
                                <p>{{ $itemERelationship->target_field }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="cascade_on_delete">{{ ucfirst(__('PkgGapp::eRelationship.cascade_on_delete')) }}:</label>
                                <p>{{ $itemERelationship->cascade_on_delete }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="description">{{ ucfirst(__('PkgGapp::eRelationship.description')) }}:</label>
                                <p>{{ $itemERelationship->description }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
