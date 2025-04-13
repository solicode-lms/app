{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgRealisationProjets::workflowProjet.singular'))
@section('content')
    @include('PkgRealisationProjets::workflowProjet._edit')
@endsection
