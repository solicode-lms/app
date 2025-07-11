{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgRealisationTache::etatRealisationTache.singular'))
@section('content')
    @include('PkgRealisationTache::etatRealisationTache._edit')
@endsection
