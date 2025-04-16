{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.show') . ' ' . __('Core::userModelFilter.singular'))
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('Core::msg.detail') }}</h1>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('userModelFilters.edit', $itemUserModelFilter->id) }}" class="btn btn-default float-right">
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
                                <label for="user_id">{{ ucfirst(__('Core::userModelFilter.user_id')) }}:</label>
                                <p>{{ $itemUserModelFilter->user_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="model_name">{{ ucfirst(__('Core::userModelFilter.model_name')) }}:</label>
                                <p>{{ $itemUserModelFilter->model_name }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="filters">{{ ucfirst(__('Core::userModelFilter.filters')) }}:</label>
                                <p>{{ $itemUserModelFilter->filters }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
