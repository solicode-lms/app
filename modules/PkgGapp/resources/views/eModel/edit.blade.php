{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgGapp::eModel.singular'))
@section('content')
    @include('PkgGapp::eModel._edit')
@endsection
