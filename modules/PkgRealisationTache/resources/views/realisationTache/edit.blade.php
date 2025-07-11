{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgRealisationTache::realisationTache.singular'))
@section('content')
    @include('PkgRealisationTache::realisationTache._edit')
@endsection
