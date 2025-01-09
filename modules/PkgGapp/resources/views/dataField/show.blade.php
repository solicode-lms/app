{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.show') . ' ' . __('PkgGapp::dataField.singular'))
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('Core::msg.detail') }}</h1>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('dataFields.edit', $itemDataField->id) }}" class="btn btn-default float-right">
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
                                <label for="name">{{ ucfirst(__('PkgGapp::dataField.name')) }}:</label>
                                <p>{{ $itemDataField->name }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="i_model_id">{{ ucfirst(__('PkgGapp::dataField.i_model_id')) }}:</label>
                                <p>{{ $itemDataField->i_model_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="field_type_id">{{ ucfirst(__('PkgGapp::dataField.field_type_id')) }}:</label>
                                <p>{{ $itemDataField->field_type_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="description">{{ ucfirst(__('PkgGapp::dataField.description')) }}:</label>
                                <p>{{ $itemDataField->description }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
