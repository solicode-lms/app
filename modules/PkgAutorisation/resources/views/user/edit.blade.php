{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgAutorisation::user.singular'))
@section('content')
    @include('PkgAutorisation::user._edit')
@endsection
