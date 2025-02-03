{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgCompetences::technology.singular'))
@section('content')
    @include('PkgCompetences::technology._edit')
@endsection
