{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgWidgets::sectionWidget.singular'))
@section('content')
    @include('PkgWidgets::sectionWidget._edit')
@endsection
