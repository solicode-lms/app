{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgCompetences::categoryTechnology.singular'))
@section('content')
    @include('PkgCompetences::categoryTechnology._edit')
@endsection
