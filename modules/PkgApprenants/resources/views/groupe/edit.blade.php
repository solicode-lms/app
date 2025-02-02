{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgApprenants::groupe.singular'))
@section('content')
    @include('PkgApprenants::groupe._edit')
@endsection
