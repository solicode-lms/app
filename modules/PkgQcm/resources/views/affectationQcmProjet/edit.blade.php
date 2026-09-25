{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgQcm::affectationQcmProjet.singular'))
@section('content')
    @include('PkgQcm::affectationQcmProjet._edit')
@endsection
