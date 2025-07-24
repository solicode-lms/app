{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgApprentissage::etatRealisationUa.singular'))
@section('content')
    @include('PkgApprentissage::etatRealisationUa._edit')
@endsection
