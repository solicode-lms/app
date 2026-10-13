{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgTableauBord::remediation.singular'))
@section('content')
    @include('PkgTableauBord::remediation._edit')
@endsection
