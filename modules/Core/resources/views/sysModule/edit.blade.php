{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('Core::sysModule.singular'))
@section('content')
    @include('Core::sysModule._edit')
@endsection
