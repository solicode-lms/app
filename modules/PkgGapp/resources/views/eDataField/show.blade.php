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
                                <label for="type">{{ ucfirst(__('PkgGapp::eDataField.type')) }}:</label>
                                <p>{{ $itemEDataField->type }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="e_model_id">{{ ucfirst(__('PkgGapp::eDataField.e_model_id')) }}:</label>
                                <p>{{ $itemEDataField->e_model_id }}</p>
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
