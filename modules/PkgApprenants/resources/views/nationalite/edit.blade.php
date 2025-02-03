{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgApprenants::nationalite.singular'))
@section('content')
    @include('PkgApprenants::nationalite._edit')
@endsection
