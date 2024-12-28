{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.show') . ' ' . __('PkgAutorisation::user.singular'))
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('Core::msg.detail') }}</h1>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('users.edit', $itemUser->id) }}" class="btn btn-default float-right">
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
                                <label for="name">{{ ucfirst(__('PkgAutorisation::user.name')) }}:</label>
                                <p>{{ $itemUser->name }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="email">{{ ucfirst(__('PkgAutorisation::user.email')) }}:</label>
                                <p>{{ $itemUser->email }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="email_verified_at">{{ ucfirst(__('PkgAutorisation::user.email_verified_at')) }}:</label>
                                <p>{{ $itemUser->email_verified_at }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="password">{{ ucfirst(__('PkgAutorisation::user.password')) }}:</label>
                                <p>{{ $itemUser->password }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="remember_token">{{ ucfirst(__('PkgAutorisation::user.remember_token')) }}:</label>
                                <p>{{ $itemUser->remember_token }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
