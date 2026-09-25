{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgQcm::realisationQcm.singular'))
@section('content')
    @include('PkgQcm::realisationQcm._edit')
@endsection
