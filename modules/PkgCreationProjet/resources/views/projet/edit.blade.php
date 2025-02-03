{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgCreationProjet::projet.singular'))
@section('content')
    @include('PkgCreationProjet::projet._edit')
@endsection
