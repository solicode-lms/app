{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgGapp::eMetadatum.singular'))
@section('content')
    @include('PkgGapp::eMetadatum._edit')
@endsection
