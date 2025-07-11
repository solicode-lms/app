{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgRealisationTache::historiqueRealisationTache.singular'))
@section('content')
    @include('PkgRealisationTache::historiqueRealisationTache._edit')
@endsection
