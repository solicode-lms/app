{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgAutoformation::workflowFormation.singular'))
@section('content')
    @include('PkgAutoformation::workflowFormation._edit')
@endsection
