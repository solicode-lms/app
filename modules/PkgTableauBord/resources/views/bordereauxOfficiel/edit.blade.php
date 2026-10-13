{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgTableauBord::bordereauxOfficiel.singular'))
@section('content')
    @include('PkgTableauBord::bordereauxOfficiel._edit')
@endsection
