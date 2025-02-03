{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgFormation::anneeFormation.singular'))
@section('content')
    @include('PkgFormation::anneeFormation._edit')
@endsection
