{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgApprentissage::realisationUa.singular'))
@section('content')
    @include('PkgApprentissage::realisationUa._edit')
@endsection
