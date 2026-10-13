{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgTableauBord::logActiviteFormateur.singular'))
@section('content')
    @include('PkgTableauBord::logActiviteFormateur._edit')
@endsection
