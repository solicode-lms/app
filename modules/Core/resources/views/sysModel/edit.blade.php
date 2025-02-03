{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('Core::sysModel.singular'))
@section('content')
    @include('Core::sysModel._edit')
@endsection
