{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgGapp::eDataField.singular'))
@section('content')
    @include('PkgGapp::eDataField._edit')
@endsection
