{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('Core::sysController.singular'))
@section('content')
    @include('Core::sysController._edit')
@endsection
