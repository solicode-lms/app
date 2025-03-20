{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgAutoformation::chapitre.singular'))
@section('content')
    @include('PkgAutoformation::chapitre._edit')
@endsection
