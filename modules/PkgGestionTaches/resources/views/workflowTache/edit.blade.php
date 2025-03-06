{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgGestionTaches::workflowTache.singular'))
@section('content')
    @include('PkgGestionTaches::workflowTache._edit')
@endsection
