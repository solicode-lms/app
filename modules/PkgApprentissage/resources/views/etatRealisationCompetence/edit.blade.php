{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgApprentissage::etatRealisationCompetence.singular'))
@section('content')
    @include('PkgApprentissage::etatRealisationCompetence._edit')
@endsection
