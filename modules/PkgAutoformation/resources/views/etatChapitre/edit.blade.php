{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgAutoformation::etatChapitre.singular'))
@section('content')
    @include('PkgAutoformation::etatChapitre._edit')
@endsection
