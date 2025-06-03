{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgValidationProjets::etatEvaluationProjet.singular'))
@section('content')
    @include('PkgValidationProjets::etatEvaluationProjet._edit')
@endsection
