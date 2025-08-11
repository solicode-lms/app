{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgApprentissage::etatRealisationModule.singular'))
@section('content')
    @include('PkgApprentissage::etatRealisationModule._edit')
@endsection
