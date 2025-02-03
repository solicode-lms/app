{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgGapp::eMetadataDefinition.singular'))
@section('content')
    @include('PkgGapp::eMetadataDefinition._edit')
@endsection
