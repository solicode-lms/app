{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgCompetences::chapitre.singular'))
@section('content')
    @include('PkgCompetences::chapitre._edit')
@endsection
