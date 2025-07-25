{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgSessions::livrableSession.singular'))
@section('content')
    @include('PkgSessions::livrableSession._edit')
@endsection
