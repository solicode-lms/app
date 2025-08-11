{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgApprentissage::realisationModule.singular'))
@section('content')
    @include('PkgApprentissage::realisationModule._edit')
@endsection
