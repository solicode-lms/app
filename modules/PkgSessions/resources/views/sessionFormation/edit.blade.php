{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgSessions::sessionFormation.singular'))
@section('content')
    @include('PkgSessions::sessionFormation._edit')
@endsection
