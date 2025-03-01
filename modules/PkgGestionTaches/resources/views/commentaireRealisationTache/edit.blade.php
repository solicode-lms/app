{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgGestionTaches::commentaireRealisationTache.singular'))
@section('content')
    @include('PkgGestionTaches::commentaireRealisationTache._edit')
@endsection
