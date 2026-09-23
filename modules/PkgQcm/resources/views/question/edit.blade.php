{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgQcm::questionLib.singular'))
@section('content')
    @include('PkgQcm::questionLib._edit')
@endsection
