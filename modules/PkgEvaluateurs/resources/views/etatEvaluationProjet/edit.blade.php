{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgEvaluateurs::etatEvaluationProjet.singular'))
@section('content')
    @include('PkgEvaluateurs::etatEvaluationProjet._edit')
@endsection
