{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgQcm::etatRealisationQcm.singular'))
@section('content')
    @include('PkgQcm::etatRealisationQcm._edit')
@endsection
