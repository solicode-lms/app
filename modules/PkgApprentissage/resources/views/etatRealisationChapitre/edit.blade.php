{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgApprentissage::etatRealisationChapitre.singular'))
@section('content')
    @include('PkgApprentissage::etatRealisationChapitre._edit')
@endsection
