{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.show') . ' ' . __('PkgBlog::user.singular'))
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('Core::msg.detail') }}</h1>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('users.edit', $item->id) }}" class="btn btn-default float-right">
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
                                <label for="name">{{ ucfirst(__('PkgBlog::user.name')) }}:</label>
                                <p>{{ $item->name }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="email">{{ ucfirst(__('PkgBlog::user.email')) }}:</label>
                                <p>{{ $item->email }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="email_verified_at">{{ ucfirst(__('PkgBlog::user.email_verified_at')) }}:</label>
                                <p>{{ $item->email_verified_at }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="password">{{ ucfirst(__('PkgBlog::user.password')) }}:</label>
                                <p>{{ $item->password }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="remember_token">{{ ucfirst(__('PkgBlog::user.remember_token')) }}:</label>
                                <p>{{ $item->remember_token }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
