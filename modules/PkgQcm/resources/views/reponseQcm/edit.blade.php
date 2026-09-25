{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgQcm::reponseQcm.singular'))
@section('content')
    @include('PkgQcm::reponseQcm._edit')
@endsection
