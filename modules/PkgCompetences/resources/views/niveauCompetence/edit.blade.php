{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgCompetences::niveauCompetence.singular'))
@section('content')
    @include('PkgCompetences::niveauCompetence._edit')
@endsection
