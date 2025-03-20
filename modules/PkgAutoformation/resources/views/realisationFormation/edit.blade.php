{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgAutoformation::realisationFormation.singular'))
@section('content')
    @include('PkgAutoformation::realisationFormation._edit')
@endsection
