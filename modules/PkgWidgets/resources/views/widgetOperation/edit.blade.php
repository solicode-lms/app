{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgWidgets::widgetOperation.singular'))
@section('content')
    @include('PkgWidgets::widgetOperation._edit')
@endsection
