{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgAutoformation::realisationChapitre.singular'))
@section('content')
    @include('PkgAutoformation::realisationChapitre._edit')
@endsection
