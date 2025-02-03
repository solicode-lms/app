{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgGapp::eRelationship.singular'))
@section('content')
    @include('PkgGapp::eRelationship._edit')
@endsection
