{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgFormation::filiere.singular'))
@section('content')
    @include('PkgFormation::filiere._edit')
@endsection
