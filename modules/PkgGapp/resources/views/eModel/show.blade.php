{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.show') . ' ' . __('PkgGapp::eModel.singular'))
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('Core::msg.detail') }}</h1>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('eModels.edit', $itemEModel->id) }}" class="btn btn-default float-right">
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
                                <label for="description">{{ ucfirst(__('PkgGapp::eModel.description')) }}:</label>
                                <p>{{ $itemEModel->description }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="e_package_id">{{ ucfirst(__('PkgGapp::eModel.e_package_id')) }}:</label>
                                <p>{{ $itemEModel->e_package_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="icon">{{ ucfirst(__('PkgGapp::eModel.icon')) }}:</label>
                                <p>{{ $itemEModel->icon }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="name">{{ ucfirst(__('PkgGapp::eModel.name')) }}:</label>
                                <p>{{ $itemEModel->name }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
