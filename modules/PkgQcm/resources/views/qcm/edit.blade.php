{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgQcm::qcm.singular'))
@section('content')
    @include('PkgQcm::qcm._edit')
@endsection
