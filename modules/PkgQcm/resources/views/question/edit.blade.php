{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgQcm::question.singular'))
@section('content')
    @include('PkgQcm::question._edit')
@endsection
