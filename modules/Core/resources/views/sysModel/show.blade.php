{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.show') . ' ' . __('Core::sysModel.singular'))
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('Core::msg.detail') }}</h1>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('sysModels.edit', $itemSysModel->id) }}" class="btn btn-default float-right">
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
                                <label for="name">{{ ucfirst(__('Core::sysModel.name')) }}:</label>
                                <p>{{ $itemSysModel->name }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="model">{{ ucfirst(__('Core::sysModel.model')) }}:</label>
                                <p>{{ $itemSysModel->model }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="description">{{ ucfirst(__('Core::sysModel.description')) }}:</label>
                                <p>{{ $itemSysModel->description }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="module_id">{{ ucfirst(__('Core::sysModel.module_id')) }}:</label>
                                <p>{{ $itemSysModel->module_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="color_id">{{ ucfirst(__('Core::sysModel.color_id')) }}:</label>
                                <p>{{ $itemSysModel->color_id }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
