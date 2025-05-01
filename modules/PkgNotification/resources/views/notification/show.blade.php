{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.show') . ' ' . __('PkgNotification::notification.singular'))
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('Core::msg.detail') }}</h1>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('notifications.edit', $itemNotification->id) }}" class="btn btn-default float-right">
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
                                <label for="title">{{ ucfirst(__('PkgNotification::notification.title')) }}:</label>
                                <p>{{ $itemNotification->title }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="type">{{ ucfirst(__('PkgNotification::notification.type')) }}:</label>
                                <p>{{ $itemNotification->type }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="message">{{ ucfirst(__('PkgNotification::notification.message')) }}:</label>
                                <p>{{ $itemNotification->message }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="sent_at">{{ ucfirst(__('PkgNotification::notification.sent_at')) }}:</label>
                                <p>{{ $itemNotification->sent_at }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="is_read">{{ ucfirst(__('PkgNotification::notification.is_read')) }}:</label>
                                <p>{{ $itemNotification->is_read }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="user_id">{{ ucfirst(__('PkgNotification::notification.user_id')) }}:</label>
                                <p>{{ $itemNotification->user_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="data">{{ ucfirst(__('PkgNotification::notification.data')) }}:</label>
                                <p>{{ $itemNotification->data }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
