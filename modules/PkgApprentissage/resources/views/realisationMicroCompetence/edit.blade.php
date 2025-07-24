{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgApprentissage::realisationMicroCompetence.singular'))
@section('content')
    @include('PkgApprentissage::realisationMicroCompetence._edit')
@endsection
