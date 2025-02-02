{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgApprenants::niveauxScolaire.singular'))
@section('content')
    @include('PkgApprenants::niveauxScolaire._edit')
@endsection
