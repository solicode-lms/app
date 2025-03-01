{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgGestionTaches::etatRealisationTache.singular'))
@section('content')
    @include('PkgGestionTaches::etatRealisationTache._edit')
@endsection
