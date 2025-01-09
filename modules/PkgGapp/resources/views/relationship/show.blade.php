{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.show') . ' ' . __('PkgGapp::relationship.singular'))
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('Core::msg.detail') }}</h1>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('relationships.edit', $itemRelationship->id) }}" class="btn btn-default float-right">
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
                                <label for="source_model_id">{{ ucfirst(__('PkgGapp::relationship.source_model_id')) }}:</label>
                                <p>{{ $itemRelationship->source_model_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="target_model_id">{{ ucfirst(__('PkgGapp::relationship.target_model_id')) }}:</label>
                                <p>{{ $itemRelationship->target_model_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="type">{{ ucfirst(__('PkgGapp::relationship.type')) }}:</label>
                                <p>{{ $itemRelationship->type }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="source_field">{{ ucfirst(__('PkgGapp::relationship.source_field')) }}:</label>
                                <p>{{ $itemRelationship->source_field }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="target_field">{{ ucfirst(__('PkgGapp::relationship.target_field')) }}:</label>
                                <p>{{ $itemRelationship->target_field }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="cascade_on_delete">{{ ucfirst(__('PkgGapp::relationship.cascade_on_delete')) }}:</label>
                                <p>{{ $itemRelationship->cascade_on_delete }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="description">{{ ucfirst(__('PkgGapp::relationship.description')) }}:</label>
                                <p>{{ $itemRelationship->description }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
