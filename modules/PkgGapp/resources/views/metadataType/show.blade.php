{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.show') . ' ' . __('PkgGapp::metadataType.singular'))
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('Core::msg.detail') }}</h1>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('metadataTypes.edit', $itemMetadataType->id) }}" class="btn btn-default float-right">
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
                                <label for="name">{{ ucfirst(__('PkgGapp::metadataType.name')) }}:</label>
                                <p>{{ $itemMetadataType->name }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="code">{{ ucfirst(__('PkgGapp::metadataType.code')) }}:</label>
                                <p>{{ $itemMetadataType->code }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="type">{{ ucfirst(__('PkgGapp::metadataType.type')) }}:</label>
                                <p>{{ $itemMetadataType->type }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="scope">{{ ucfirst(__('PkgGapp::metadataType.scope')) }}:</label>
                                <p>{{ $itemMetadataType->scope }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="description">{{ ucfirst(__('PkgGapp::metadataType.description')) }}:</label>
                                <p>{{ $itemMetadataType->description }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="default_value">{{ ucfirst(__('PkgGapp::metadataType.default_value')) }}:</label>
                                <p>{{ $itemMetadataType->default_value }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="validation_rules">{{ ucfirst(__('PkgGapp::metadataType.validation_rules')) }}:</label>
                                <p>{{ $itemMetadataType->validation_rules }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
