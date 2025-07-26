{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgCreationProjet::mobilisationUa.singular'))
@section('content')
    @include('PkgCreationProjet::mobilisationUa._edit')
@endsection
