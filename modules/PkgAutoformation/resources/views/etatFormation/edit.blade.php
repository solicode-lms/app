{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgAutoformation::etatFormation.singular'))
@section('content')
    @include('PkgAutoformation::etatFormation._edit')
@endsection
