{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgAutorisation::permission.singular'))
@section('content')
    @include('PkgAutorisation::permission._edit')
@endsection
