{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgRealisationTache::dependanceTache.singular'))
@section('content')
    @include('PkgRealisationTache::dependanceTache._edit')
@endsection
