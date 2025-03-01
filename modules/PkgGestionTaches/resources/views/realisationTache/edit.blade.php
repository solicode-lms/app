{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgGestionTaches::realisationTache.singular'))
@section('content')
    @include('PkgGestionTaches::realisationTache._edit')
@endsection
