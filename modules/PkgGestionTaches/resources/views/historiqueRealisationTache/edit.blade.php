{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgGestionTaches::historiqueRealisationTache.singular'))
@section('content')
    @include('PkgGestionTaches::historiqueRealisationTache._edit')
@endsection
