{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgCreationProjet::equipeProjet.singular'))
@section('content')
    @include('PkgCreationProjet::equipeProjet._edit')
@endsection
