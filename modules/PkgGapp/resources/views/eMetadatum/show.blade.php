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
                                <label for="value_boolean">{{ ucfirst(__('PkgGapp::eMetadatum.value_boolean')) }}:</label>
                                <p>{{ $itemEMetadatum->value_boolean }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="value_string">{{ ucfirst(__('PkgGapp::eMetadatum.value_string')) }}:</label>
                                <p>{{ $itemEMetadatum->value_string }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="value_integer">{{ ucfirst(__('PkgGapp::eMetadatum.value_integer')) }}:</label>
                                <p>{{ $itemEMetadatum->value_integer }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="value_float">{{ ucfirst(__('PkgGapp::eMetadatum.value_float')) }}:</label>
                                <p>{{ $itemEMetadatum->value_float }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="value_date">{{ ucfirst(__('PkgGapp::eMetadatum.value_date')) }}:</label>
                                <p>{{ $itemEMetadatum->value_date }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="value_datetime">{{ ucfirst(__('PkgGapp::eMetadatum.value_datetime')) }}:</label>
                                <p>{{ $itemEMetadatum->value_datetime }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="value_enum">{{ ucfirst(__('PkgGapp::eMetadatum.value_enum')) }}:</label>
                                <p>{{ $itemEMetadatum->value_enum }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="value_json">{{ ucfirst(__('PkgGapp::eMetadatum.value_json')) }}:</label>
                                <p>{{ $itemEMetadatum->value_json }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="value_text">{{ ucfirst(__('PkgGapp::eMetadatum.value_text')) }}:</label>
                                <p>{{ $itemEMetadatum->value_text }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="e_model_id">{{ ucfirst(__('PkgGapp::eMetadatum.e_model_id')) }}:</label>
                                <p>{{ $itemEMetadatum->e_model_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="e_data_field_id">{{ ucfirst(__('PkgGapp::eMetadatum.e_data_field_id')) }}:</label>
                                <p>{{ $itemEMetadatum->e_data_field_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="e_metadata_definition_id">{{ ucfirst(__('PkgGapp::eMetadatum.e_metadata_definition_id')) }}:</label>
                                <p>{{ $itemEMetadatum->e_metadata_definition_id }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
