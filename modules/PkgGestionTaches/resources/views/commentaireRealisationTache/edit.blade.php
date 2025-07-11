{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgRealisationTache::commentaireRealisationTache.singular'))
@section('content')
    @include('PkgRealisationTache::commentaireRealisationTache._edit')
@endsection
