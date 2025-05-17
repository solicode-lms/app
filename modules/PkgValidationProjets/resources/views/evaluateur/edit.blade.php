{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgValidationProjets::evaluateur.singular'))
@section('content')
    @include('PkgValidationProjets::evaluateur._edit')
@endsection
