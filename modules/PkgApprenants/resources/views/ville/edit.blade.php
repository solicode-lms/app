{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgApprenants::ville.singular'))
@section('content')
    @include('PkgApprenants::ville._edit')
@endsection
