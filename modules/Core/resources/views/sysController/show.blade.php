{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.show') . ' ' . __('Core::sysController.singular'))
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('Core::msg.detail') }}</h1>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('sysControllers.edit', $itemSysController->id) }}" class="btn btn-default float-right">
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
                                <label for="module_id">{{ ucfirst(__('Core::sysController.module_id')) }}:</label>
                                <p>{{ $itemSysController->module_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="name">{{ ucfirst(__('Core::sysController.name')) }}:</label>
                                <p>{{ $itemSysController->name }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="slug">{{ ucfirst(__('Core::sysController.slug')) }}:</label>
                                <p>{{ $itemSysController->slug }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="description">{{ ucfirst(__('Core::sysController.description')) }}:</label>
                                <p>{{ $itemSysController->description }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="is_active">{{ ucfirst(__('Core::sysController.is_active')) }}:</label>
                                <p>{{ $itemSysController->is_active }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
