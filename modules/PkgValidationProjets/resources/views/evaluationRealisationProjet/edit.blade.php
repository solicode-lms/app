{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgValidationProjets::evaluationRealisationProjet.singular'))
@section('content')
    @include('PkgValidationProjets::evaluationRealisationProjet._edit')
@endsection
