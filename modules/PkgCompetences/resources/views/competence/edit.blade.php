{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgCompetences::competence.singular'))
@section('content')
    @include('PkgCompetences::competence._edit')
@endsection
