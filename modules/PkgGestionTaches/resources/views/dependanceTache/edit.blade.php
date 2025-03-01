{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgGestionTaches::dependanceTache.singular'))
@section('content')
    @include('PkgGestionTaches::dependanceTache._edit')
@endsection
