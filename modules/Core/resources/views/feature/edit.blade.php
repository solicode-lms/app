{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('Core::feature.singular'))
@section('content')
    @include('Core::feature._edit')
@endsection
