{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgEvaluateurs::evaluationRealisationTache.singular'))
@section('content')
    @include('PkgEvaluateurs::evaluationRealisationTache._edit')
@endsection
