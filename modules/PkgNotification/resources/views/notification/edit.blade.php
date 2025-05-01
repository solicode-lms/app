{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgNotification::notification.singular'))
@section('content')
    @include('PkgNotification::notification._edit')
@endsection
