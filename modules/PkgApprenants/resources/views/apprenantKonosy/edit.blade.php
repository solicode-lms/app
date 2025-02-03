{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgApprenants::apprenantKonosy.singular'))
@section('content')
    @include('PkgApprenants::apprenantKonosy._edit')
@endsection
