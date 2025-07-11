{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgRealisationTache::typeDependanceTache.singular'))
@section('content')
    @include('PkgRealisationTache::typeDependanceTache._edit')
@endsection
