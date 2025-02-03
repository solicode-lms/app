{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgFormation::specialite.singular'))
@section('content')
    @include('PkgFormation::specialite._edit')
@endsection
