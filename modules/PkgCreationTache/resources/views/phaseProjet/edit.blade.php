{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgCreationTache::phaseProjet.singular'))
@section('content')
    @include('PkgCreationTache::phaseProjet._edit')
@endsection
