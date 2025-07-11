{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgRealisationTache::labelRealisationTache.singular'))
@section('content')
    @include('PkgRealisationTache::labelRealisationTache._edit')
@endsection
