{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.show') . ' ' . __('PkgWidgets::widget.singular'))
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('Core::msg.detail') }}</h1>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('widgets.edit', $item->id) }}" class="btn btn-default float-right">
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
                                <label for="name">{{ ucfirst(__('PkgWidgets::widget.name')) }}:</label>
                                <p>{{ $item->name }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="type_id">{{ ucfirst(__('PkgWidgets::widget.type_id')) }}:</label>
                                <p>{{ $item->type_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="model_id">{{ ucfirst(__('PkgWidgets::widget.model_id')) }}:</label>
                                <p>{{ $item->model_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="operation_id">{{ ucfirst(__('PkgWidgets::widget.operation_id')) }}:</label>
                                <p>{{ $item->operation_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="color">{{ ucfirst(__('PkgWidgets::widget.color')) }}:</label>
                                <p>{{ $item->color }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="icon">{{ ucfirst(__('PkgWidgets::widget.icon')) }}:</label>
                                <p>{{ $item->icon }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="label">{{ ucfirst(__('PkgWidgets::widget.label')) }}:</label>
                                <p>{{ $item->label }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="parameters">{{ ucfirst(__('PkgWidgets::widget.parameters')) }}:</label>
                                <p>{{ $item->parameters }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
