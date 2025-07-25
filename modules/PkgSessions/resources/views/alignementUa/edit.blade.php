{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgSessions::alignementUa.singular'))
@section('content')
    @include('PkgSessions::alignementUa._edit')
@endsection
