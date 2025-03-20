{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.show') . ' ' . __('PkgAutoformation::workflowFormation.singular'))
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('Core::msg.detail') }}</h1>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('workflowFormations.edit', $itemWorkflowFormation->id) }}" class="btn btn-default float-right">
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
                                <label for="code">{{ ucfirst(__('PkgAutoformation::workflowFormation.code')) }}:</label>
                                <p>{{ $itemWorkflowFormation->code }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="titre">{{ ucfirst(__('PkgAutoformation::workflowFormation.titre')) }}:</label>
                                <p>{{ $itemWorkflowFormation->titre }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="sys_color_id">{{ ucfirst(__('PkgAutoformation::workflowFormation.sys_color_id')) }}:</label>
                                <p>{{ $itemWorkflowFormation->sys_color_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="description">{{ ucfirst(__('PkgAutoformation::workflowFormation.description')) }}:</label>
                                <p>{{ $itemWorkflowFormation->description }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
