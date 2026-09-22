{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgQcm::questionQcm.singular'))
@section('content')
    @include('PkgQcm::questionQcm._edit')
@endsection
