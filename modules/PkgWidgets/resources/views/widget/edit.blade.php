{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgWidgets::widget.singular'))
@section('content')
    @include('PkgWidgets::widget._edit')
@endsection
