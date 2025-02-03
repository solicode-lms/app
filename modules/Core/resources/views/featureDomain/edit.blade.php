{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('Core::featureDomain.singular'))
@section('content')
    @include('Core::featureDomain._edit')
@endsection
