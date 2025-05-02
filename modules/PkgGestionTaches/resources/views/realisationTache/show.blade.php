
@extends('layouts.admin')
@section('title', __('Core::msg.show') . ' ' . __('PkgGestionTaches::realisationTache.singular'))
@section('content')
    @include('PkgGestionTaches::realisationTache._show')
@endsection
