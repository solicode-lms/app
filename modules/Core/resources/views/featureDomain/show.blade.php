{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.show') . ' ' . __('Core::featureDomain.singular'))
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('Core::msg.detail') }}</h1>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('featureDomains.edit', $itemFeatureDomain->id) }}" class="btn btn-default float-right">
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
                                <label for="name">{{ ucfirst(__('Core::featureDomain.name')) }}:</label>
                                <p>{{ $itemFeatureDomain->name }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="slug">{{ ucfirst(__('Core::featureDomain.slug')) }}:</label>
                                <p>{{ $itemFeatureDomain->slug }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="description">{{ ucfirst(__('Core::featureDomain.description')) }}:</label>
                                <p>{{ $itemFeatureDomain->description }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="sys_module_id">{{ ucfirst(__('Core::featureDomain.sys_module_id')) }}:</label>
                                <p>{{ $itemFeatureDomain->sys_module_id }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
