{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgWidgets::widgetUtilisateur.singular'))
@section('content')
    @include('PkgWidgets::widgetUtilisateur._edit')
@endsection
