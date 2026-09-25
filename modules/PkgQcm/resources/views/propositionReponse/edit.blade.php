{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgQcm::propositionReponse.singular'))
@section('content')
    @include('PkgQcm::propositionReponse._edit')
@endsection
