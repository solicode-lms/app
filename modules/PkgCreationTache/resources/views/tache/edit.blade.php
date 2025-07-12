{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgCreationTache::tache.singular'))
@section('content')
    @include('PkgCreationTache::tache._edit')
@endsection
