{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.show') . ' ' . __('Core::sysModule.singular'))
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('Core::msg.detail') }}</h1>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('sysModules.edit', $item->id) }}" class="btn btn-default float-right">
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
                                <label for="name">{{ ucfirst(__('Core::sysModule.name')) }}:</label>
                                <p>{{ $item->name }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="slug">{{ ucfirst(__('Core::sysModule.slug')) }}:</label>
                                <p>{{ $item->slug }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="description">{{ ucfirst(__('Core::sysModule.description')) }}:</label>
                                <p>{{ $item->description }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="is_active">{{ ucfirst(__('Core::sysModule.is_active')) }}:</label>
                                <p>{{ $item->is_active }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="order">{{ ucfirst(__('Core::sysModule.order')) }}:</label>
                                <p>{{ $item->order }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="version">{{ ucfirst(__('Core::sysModule.version')) }}:</label>
                                <p>{{ $item->version }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="color_id">{{ ucfirst(__('Core::sysModule.color_id')) }}:</label>
                                <p>{{ $item->color_id }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
