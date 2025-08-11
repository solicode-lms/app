{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgApprentissage::realisationCompetence.singular'))
@section('content')
    @include('PkgApprentissage::realisationCompetence._edit')
@endsection
