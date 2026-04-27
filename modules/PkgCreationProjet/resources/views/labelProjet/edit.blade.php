{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgCreationProjet::labelProjet.singular'))
@section('content')
    @include('PkgCreationProjet::labelProjet._edit')
@endsection
