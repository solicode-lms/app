{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgWidgets::widgetType.singular'))
@section('content')
    @include('PkgWidgets::widgetType._edit')
@endsection
