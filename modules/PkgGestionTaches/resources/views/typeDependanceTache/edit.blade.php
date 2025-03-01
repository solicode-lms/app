{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgGestionTaches::typeDependanceTache.singular'))
@section('content')
    @include('PkgGestionTaches::typeDependanceTache._edit')
@endsection
