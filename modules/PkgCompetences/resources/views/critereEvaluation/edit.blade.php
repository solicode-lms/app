{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgCompetences::critereEvaluation.singular'))
@section('content')
    @include('PkgCompetences::critereEvaluation._edit')
@endsection
