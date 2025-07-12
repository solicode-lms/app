{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgEvaluateurs::evaluationRealisationProjet.singular'))
@section('content')
    @include('PkgEvaluateurs::evaluationRealisationProjet._edit')
@endsection
