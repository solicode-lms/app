{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgValidationProjets::evaluationRealisationTache.singular'))
@section('content')
    @include('PkgValidationProjets::evaluationRealisationTache._edit')
@endsection
