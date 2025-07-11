{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgRealisationTache::workflowTache.singular'))
@section('content')
    @include('PkgRealisationTache::workflowTache._edit')
@endsection
