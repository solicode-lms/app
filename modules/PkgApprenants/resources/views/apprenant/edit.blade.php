{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgApprenants::apprenant.singular'))
@section('content')
    @include('PkgApprenants::apprenant._edit')
@endsection
