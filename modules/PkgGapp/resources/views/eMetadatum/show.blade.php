{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.show') . ' ' . __('PkgGapp::eMetadatum.singular'))
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('Core::msg.detail') }}</h1>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('eMetadata.edit', $itemEMetadatum->id) }}" class="btn btn-default float-right">
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
                                <label for="e_metadata_definition_id">{{ ucfirst(__('PkgGapp::eMetadatum.e_metadata_definition_id')) }}:</label>
                                <p>{{ $itemEMetadatum->e_metadata_definition_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="object_id">{{ ucfirst(__('PkgGapp::eMetadatum.object_id')) }}:</label>
                                <p>{{ $itemEMetadatum->object_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="object_type">{{ ucfirst(__('PkgGapp::eMetadatum.object_type')) }}:</label>
                                <p>{{ $itemEMetadatum->object_type }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="value_boolean">{{ ucfirst(__('PkgGapp::eMetadatum.value_boolean')) }}:</label>
                                <p>{{ $itemEMetadatum->value_boolean }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="value_int">{{ ucfirst(__('PkgGapp::eMetadatum.value_int')) }}:</label>
                                <p>{{ $itemEMetadatum->value_int }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="value_object">{{ ucfirst(__('PkgGapp::eMetadatum.value_object')) }}:</label>
                                <p>{{ $itemEMetadatum->value_object }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="value_string">{{ ucfirst(__('PkgGapp::eMetadatum.value_string')) }}:</label>
                                <p>{{ $itemEMetadatum->value_string }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
