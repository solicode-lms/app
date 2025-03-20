{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgAutoformation::workflowChapitre.singular'))
@section('content')
    @include('PkgAutoformation::workflowChapitre._edit')
@endsection
