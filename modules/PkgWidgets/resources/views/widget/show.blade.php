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
                    <a href="{{ route('widgets.edit', $itemWidget->id) }}" class="btn btn-default float-right">
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
                                <label for="ordre">{{ ucfirst(__('PkgWidgets::widget.ordre')) }}:</label>
                                <p>{{ $itemWidget->ordre }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="icon">{{ ucfirst(__('PkgWidgets::widget.icon')) }}:</label>
                                <p>{{ $itemWidget->icon }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="name">{{ ucfirst(__('PkgWidgets::widget.name')) }}:</label>
                                <p>{{ $itemWidget->name }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="label">{{ ucfirst(__('PkgWidgets::widget.label')) }}:</label>
                                <p>{{ $itemWidget->label }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="type_id">{{ ucfirst(__('PkgWidgets::widget.type_id')) }}:</label>
                                <p>{{ $itemWidget->type_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="model_id">{{ ucfirst(__('PkgWidgets::widget.model_id')) }}:</label>
                                <p>{{ $itemWidget->model_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="operation_id">{{ ucfirst(__('PkgWidgets::widget.operation_id')) }}:</label>
                                <p>{{ $itemWidget->operation_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="color">{{ ucfirst(__('PkgWidgets::widget.color')) }}:</label>
                                <p>{{ $itemWidget->color }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="sys_color_id">{{ ucfirst(__('PkgWidgets::widget.sys_color_id')) }}:</label>
                                <p>{{ $itemWidget->sys_color_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="section_widget_id">{{ ucfirst(__('PkgWidgets::widget.section_widget_id')) }}:</label>
                                <p>{{ $itemWidget->section_widget_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="parameters">{{ ucfirst(__('PkgWidgets::widget.parameters')) }}:</label>
                                <p>{{ $itemWidget->parameters }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
