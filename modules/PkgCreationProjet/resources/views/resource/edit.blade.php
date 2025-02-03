{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgCreationProjet::resource.singular'))
@section('content')
    @include('PkgCreationProjet::resource._edit')
@endsection
