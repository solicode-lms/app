{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.show') . ' ' . __('PkgGapp::metadatum.singular'))
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('Core::msg.detail') }}</h1>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('metadata.edit', $itemMetadatum->id) }}" class="btn btn-default float-right">
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
                                <label for="value_boolean">{{ ucfirst(__('PkgGapp::metadatum.value_boolean')) }}:</label>
                                <p>{{ $itemMetadatum->value_boolean }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="value_string">{{ ucfirst(__('PkgGapp::metadatum.value_string')) }}:</label>
                                <p>{{ $itemMetadatum->value_string }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="value_int">{{ ucfirst(__('PkgGapp::metadatum.value_int')) }}:</label>
                                <p>{{ $itemMetadatum->value_int }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="value_object">{{ ucfirst(__('PkgGapp::metadatum.value_object')) }}:</label>
                                <p>{{ $itemMetadatum->value_object }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="object_id">{{ ucfirst(__('PkgGapp::metadatum.object_id')) }}:</label>
                                <p>{{ $itemMetadatum->object_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="object_type">{{ ucfirst(__('PkgGapp::metadatum.object_type')) }}:</label>
                                <p>{{ $itemMetadatum->object_type }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="metadata_type_id">{{ ucfirst(__('PkgGapp::metadatum.metadata_type_id')) }}:</label>
                                <p>{{ $itemMetadatum->metadata_type_id }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
