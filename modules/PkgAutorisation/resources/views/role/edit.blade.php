{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgAutorisation::role.singular'))
@section('content')
    @include('PkgAutorisation::role._edit')
@endsection
