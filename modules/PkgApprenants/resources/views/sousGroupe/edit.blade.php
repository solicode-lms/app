{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgApprenants::sousGroupe.singular'))
@section('content')
    @include('PkgApprenants::sousGroupe._edit')
@endsection
