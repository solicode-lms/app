{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.show') . ' ' . __('PkgGapp::eMetadataDefinition.singular'))
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('Core::msg.detail') }}</h1>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('eMetadataDefinitions.edit', $itemEMetadataDefinition->id) }}" class="btn btn-default float-right">
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
                                <label for="code">{{ ucfirst(__('PkgGapp::eMetadataDefinition.code')) }}:</label>
                                <p>{{ $itemEMetadataDefinition->code }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="default_value">{{ ucfirst(__('PkgGapp::eMetadataDefinition.default_value')) }}:</label>
                                <p>{{ $itemEMetadataDefinition->default_value }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="description">{{ ucfirst(__('PkgGapp::eMetadataDefinition.description')) }}:</label>
                                <p>{{ $itemEMetadataDefinition->description }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="groupe">{{ ucfirst(__('PkgGapp::eMetadataDefinition.groupe')) }}:</label>
                                <p>{{ $itemEMetadataDefinition->groupe }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="name">{{ ucfirst(__('PkgGapp::eMetadataDefinition.name')) }}:</label>
                                <p>{{ $itemEMetadataDefinition->name }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="scope">{{ ucfirst(__('PkgGapp::eMetadataDefinition.scope')) }}:</label>
                                <p>{{ $itemEMetadataDefinition->scope }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="type">{{ ucfirst(__('PkgGapp::eMetadataDefinition.type')) }}:</label>
                                <p>{{ $itemEMetadataDefinition->type }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
