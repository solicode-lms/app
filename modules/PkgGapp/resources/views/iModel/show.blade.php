{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.show') . ' ' . __('PkgGapp::iModel.singular'))
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('Core::msg.detail') }}</h1>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('iModels.edit', $itemIModel->id) }}" class="btn btn-default float-right">
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
                                <label for="name">{{ ucfirst(__('PkgGapp::iModel.name')) }}:</label>
                                <p>{{ $itemIModel->name }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="icon">{{ ucfirst(__('PkgGapp::iModel.icon')) }}:</label>
                                <p>{{ $itemIModel->icon }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="description">{{ ucfirst(__('PkgGapp::iModel.description')) }}:</label>
                                <p>{{ $itemIModel->description }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="i_package_id">{{ ucfirst(__('PkgGapp::iModel.i_package_id')) }}:</label>
                                <p>{{ $itemIModel->i_package_id }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
