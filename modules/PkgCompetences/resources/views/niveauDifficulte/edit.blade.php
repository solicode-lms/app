{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgCompetences::niveauDifficulte.singular'))
@section('content')
    @include('PkgCompetences::niveauDifficulte._edit')
@endsection
