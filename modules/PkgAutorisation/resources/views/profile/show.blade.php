{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.show') . ' ' . __('PkgAutorisation::profile.singular'))
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('Core::msg.detail') }}</h1>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('profiles.edit', $itemProfile->id) }}" class="btn btn-default float-right">
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
                                <label for="user_id">{{ ucfirst(__('PkgAutorisation::profile.user_id')) }}:</label>
                                <p>{{ $itemProfile->user_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="phone">{{ ucfirst(__('PkgAutorisation::profile.phone')) }}:</label>
                                <p>{{ $itemProfile->phone }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="address">{{ ucfirst(__('PkgAutorisation::profile.address')) }}:</label>
                                <p>{{ $itemProfile->address }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="profile_picture">{{ ucfirst(__('PkgAutorisation::profile.profile_picture')) }}:</label>
                                <p>{{ $itemProfile->profile_picture }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="bio">{{ ucfirst(__('PkgAutorisation::profile.bio')) }}:</label>
                                <p>{{ $itemProfile->bio }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
