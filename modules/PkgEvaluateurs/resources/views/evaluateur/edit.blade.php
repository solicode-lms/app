{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgEvaluateurs::evaluateur.singular'))
@section('content')
    @include('PkgEvaluateurs::evaluateur._edit')
@endsection
