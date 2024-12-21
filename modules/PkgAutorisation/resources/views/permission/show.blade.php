{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.show') . ' ' . __('PkgAutorisation::permission.singular'))
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('Core::msg.detail') }}</h1>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('permissions.edit', $item->id) }}" class="btn btn-default float-right">
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
                                <label for="name">{{ ucfirst(__('PkgAutorisation::permission.name')) }}:</label>
                                <p>{{ $item->name }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="module">{{ ucfirst(__('PkgAutorisation::permission.module')) }}:</label>
                                <p>{{ $item->module }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="type">{{ ucfirst(__('PkgAutorisation::permission.type')) }}:</label>
                                <p>{{ $item->type }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="guard_name">{{ ucfirst(__('PkgAutorisation::permission.guard_name')) }}:</label>
                                <p>{{ $item->guard_name }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
