{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgGestionTaches::tache.singular'))
@section('content')
    @include('PkgGestionTaches::tache._edit')
@endsection
