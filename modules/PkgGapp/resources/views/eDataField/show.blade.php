{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.show') . ' ' . __('PkgGapp::eDataField.singular'))
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('Core::msg.detail') }}</h1>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('eDataFields.edit', $itemEDataField->id) }}" class="btn btn-default float-right">
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
                                <label for="name">{{ ucfirst(__('PkgGapp::eDataField.name')) }}:</label>
                                <p>{{ $itemEDataField->name }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="data_type">{{ ucfirst(__('PkgGapp::eDataField.data_type')) }}:</label>
                                <p>{{ $itemEDataField->data_type }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="column_name">{{ ucfirst(__('PkgGapp::eDataField.column_name')) }}:</label>
                                <p>{{ $itemEDataField->column_name }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="e_model_id">{{ ucfirst(__('PkgGapp::eDataField.e_model_id')) }}:</label>
                                <p>{{ $itemEDataField->e_model_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="e_relationship_id">{{ ucfirst(__('PkgGapp::eDataField.e_relationship_id')) }}:</label>
                                <p>{{ $itemEDataField->e_relationship_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="field_order">{{ ucfirst(__('PkgGapp::eDataField.field_order')) }}:</label>
                                <p>{{ $itemEDataField->field_order }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="default_value">{{ ucfirst(__('PkgGapp::eDataField.default_value')) }}:</label>
                                <p>{{ $itemEDataField->default_value }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="db_primaryKey">{{ ucfirst(__('PkgGapp::eDataField.db_primaryKey')) }}:</label>
                                <p>{{ $itemEDataField->db_primaryKey }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="db_nullable">{{ ucfirst(__('PkgGapp::eDataField.db_nullable')) }}:</label>
                                <p>{{ $itemEDataField->db_nullable }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="db_unique">{{ ucfirst(__('PkgGapp::eDataField.db_unique')) }}:</label>
                                <p>{{ $itemEDataField->db_unique }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="calculable">{{ ucfirst(__('PkgGapp::eDataField.calculable')) }}:</label>
                                <p>{{ $itemEDataField->calculable }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="calculable_sql">{{ ucfirst(__('PkgGapp::eDataField.calculable_sql')) }}:</label>
                                <p>{{ $itemEDataField->calculable_sql }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="description">{{ ucfirst(__('PkgGapp::eDataField.description')) }}:</label>
                                <p>{{ $itemEDataField->description }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
