{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgApprentissage::etatRealisationMicroCompetence.singular'))
@section('content')
    @include('PkgApprentissage::etatRealisationMicroCompetence._edit')
@endsection
