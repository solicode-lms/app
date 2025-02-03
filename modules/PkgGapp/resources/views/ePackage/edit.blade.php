{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgGapp::ePackage.singular'))
@section('content')
    @include('PkgGapp::ePackage._edit')
@endsection
