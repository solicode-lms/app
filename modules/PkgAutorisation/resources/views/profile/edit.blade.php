{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgAutorisation::profile.singular'))
@section('content')
    @include('PkgAutorisation::profile._edit')
@endsection
