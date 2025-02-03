{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgCreationProjet::livrable.singular'))
@section('content')
    @include('PkgCreationProjet::livrable._edit')
@endsection
